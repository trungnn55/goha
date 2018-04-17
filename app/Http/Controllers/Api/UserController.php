<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\UserSessionProduct;
use App\Models\ProductPopularity;
use App\Models\Product;
use App\Models\User;
use Validator;
use Exception;
use Log;
use DB;

class UserController extends BaseController
{
    public function getProfile(Request $request)
    {
        try {
            $id = $this->getIdFromToken($request);
            $user = DB::table('cscart_users as u')
                ->join('cscart_user_profiles as up', 'u.user_id', '=', 'up.user_id')
                ->where('u.user_id', $id)
                ->first();

            return $this->responseSuccess(['data' => $user]);
        } catch (Exception $e) {
            Log::error($e);

            return $this->responseError(self::HTTP_SERVER_ERROR, $e->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
        DB::beginTransaction();
        try {
            $billing = $request->get('billing');
            $shipping = $request->get('shipping');
            $id = $this->getIdFromToken($request);
            $user = DB::table('cscart_users')
                ->where('user_id', $id)
                ->first();

            $ruleRequest = [
                'email' => [
                    'required',
                    'email',
                    Rule::unique('cscart_users')->ignore($id, 'user_id')
                ],
                'password' => 'required',
                'birthday' => 'date',
                'billing' => 'required',
                'shipping' => 'json',
            ];

            $ruleCommon = [
                'firstname' => 'required',
                'lastname' => 'required',
                'phone' => 'required',
                'country' => 'max:2',
            ];

            $validator = Validator::make($request->all(), $ruleRequest);

            if ($validator->fails()) {
                return $this->responseError(self::VALIDATE_ERROR, $validator->errors()->first());
            }

            if (!is_json($billing) || ($shipping && !is_json($shipping))) {
                return $this->responseError(self::VALIDATE_ERROR, 'billing and shipping must be a json format');
            }

            $billing = json_decode($billing, true);

            $hashPassword = $this->hashPassword($request->get('password'), $user->salt);

            $dataUserUpdate = [
                'email' => $request->get('email'),
                'birthday' => $request->get('birthday') ? strtotime($request->get('birthday')) : 0,
                'firstname' => $billing['firstname'],
                'lastname' => $billing['lastname'],
                'phone' => $billing['phone'],
            ];

            $validateBilling = Validator::make($billing, $ruleCommon);

            if ($validateBilling->fails()) {
                return $this->responseError(self::VALIDATE_ERROR, $validateBilling->errors()->first());
            }

            $dataProfileBilling = [
                'b_firstname' => $billing['firstname'],
                'b_lastname' => $billing['lastname'],
                'b_phone' => $billing['phone'],
                'b_address' => isset($billing['address']) ? $billing['address'] : '',
                'b_address_2' => isset($billing['address_2']) ? $billing['address_2'] : '',
                'b_city' => isset($billing['city']) ? $billing['city'] : '',
                'b_state' => isset($billing['state']) ? $billing['state'] : '',
                'b_country' => isset($billing['country']) ? $billing['country'] : '',
                'b_zipcode' => isset($billing['zipcode']) ? $billing['zipcode'] : '',
            ];

            if ($shipping) {
                $shipping = json_decode($shipping, true);
            }  else {
                $shipping = $billing;
            }

            $validateShipping = Validator::make($shipping, $ruleCommon);

            if ($validateShipping->fails()) {
                return $this->responseError(self::VALIDATE_ERROR, $validateShipping->errors()->first());
            }

            $dataProfileShipping = [
                's_firstname' => $shipping['firstname'],
                's_lastname' => $shipping['lastname'],
                's_phone' => $shipping['phone'],
                's_address' => isset($shipping['address']) ? $shipping['address'] : '',
                's_address_2' => isset($shipping['address_2']) ? $shipping['address_2'] : '',
                's_city' => isset($shipping['city']) ? $shipping['city'] : '',
                's_state' => isset($shipping['state']) ? $shipping['state'] : '',
                's_country' => isset($shipping['country']) ? $shipping['country'] : '',
                's_zipcode' => isset($shipping['zipcode']) ? $shipping['zipcode'] : '',
            ];
            $dataUserProfile = array_merge($dataProfileBilling, $dataProfileShipping);

            if ($hashPassword !== $user->password) {
                $dataUserUpdate['password'] = $this->hashPassword($request->get('password'), $this->fnGenerateSalt());
                $dataUserUpdate['salt'] = $user->salt;
                $dataUserUpdate['password_change_timestamp'] = time();
            }

            $userUpdate = DB::table('cscart_users')
                ->where('user_id', $id)
                ->update($dataUserUpdate);
            $userProfileUpdate = DB::table('cscart_user_profiles')
                ->where('user_id', $id)
                ->update($dataUserProfile);

            DB::commit();

            return $this->responseSuccess([
                'status' => true,
                'message' => 'Update successfuly',
            ]);
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();

            return $this->responseError(self::HTTP_SERVER_ERROR, $e->getMessage());
        }

    }

    public function addFavorite(Request $request)
    {
        try {
            $userId = $this->getIdFromToken($request);
            $productId = $request->get('product_id');
            $user = User::where('user_id', $userId)->first();
            $rule = ['product_id' => 'required|numeric|exists:cscart_products,product_id'];
            $validator = Validator::make($request->all(), $rule);

            if ($validator->fails()) {
                return $this->responseError(self::VALIDATE_ERROR, $validator->errors()->first());
            }

            $product = Product::where('product_id', $productId)->first();
            $checkExist = UserSessionProduct::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('type', 'W')
                ->first();
            if ($checkExist) {
                return $this->responseError(self::RECORD_EXIST, 'This product is already exists in wishlist');
            }

            $dataUserSessionProduct = [
                'user_id' => $userId,
                'timestamp' => time(),
                'type' => 'W',
                'user_type' => 'R',
                'item_id' => generate_cart_id($productId),
                'item_type' => $product->product_type,
                'product_id' => $productId,
                'amount' => $product->amount ? $product->amount : 1,
                'price' => $product->price,
                'session_id' => '',
                'ip_address' => encode_ip($request->ip()),
                'order_id' => 0,
            ];
            $dataExtra = $dataUserSessionProduct;
            $dataExtra['product_options'] = $product->product_options ? $product->product_options : [];
            $dataExtra['extra'] = ['prodcut_options' => []];
            $extra = serialize($dataExtra);
            $dataUserSessionProduct['extra'] = $extra;

            $userSessionProduct = UserSessionProduct::insert($dataUserSessionProduct);

            if ($userSessionProduct) {
                return $this->responseSuccess([
                    'status' => true,
                    'message' => 'Added to wishlist',
                ]);
            }

            return $this->responseError(self::HTTP_SERVER_ERROR, 'Can not add to wishlist');
        } catch (Exception $e) {
            return $this->responseError(self::HTTP_SERVER_ERROR, $e->getMessage());
        }
    }

    public function deleteFavorite(Request $request)
    {
        try {
            $userId = $this->getIdFromToken($request);
            $productId = $request->get('product_id');
            $clear = $request->get('clear');
            $rule = [
                'product_id' => 'numeric|exists:cscart_user_session_products,product_id',
                'clear' => 'numeric|between:0,1',
            ];
            $validator = Validator::make($request->all(), $rule);

            if ($validator->fails()) {
                return $this->responseError(self::VALIDATE_ERROR, $validator->errors()->first());
            }

            if (!$clear && !$productId) {
                return $this->responseError(self::VALIDATE_ERROR, 'Missing product id or clear');
            }

            if ($clear == 1) {
                $product = UserSessionProduct::where('user_id', $userId)
                    ->where('type', 'W');
            } elseif ($productId) {
                $product = UserSessionProduct::where('product_id', $productId)
                    ->where('user_id', $userId)
                    ->where('type', 'W');
            }

            if ($product->delete()) {
                return $this->responseSuccess([
                    'status' => true,
                    'message' => 'Deleted to wishlist',
                ]);
            } else {
                return $this->responseError(self::HTTP_SERVER_ERROR, 'Can not delete product wishlist');
            }
        } catch (Exception $e) {
            return $this->responseError(self::HTTP_SERVER_ERROR, $e->getMessage());
        }
    }

    public function addCart(Request $request)
    {
        try {
            $userId = $this->getIdFromToken($request);
            $productId = $request->get('product_id');
            $amount = $request->get('amount') ? $request->get('amount') : 1;
            $user = User::where('user_id', $userId)->first();
            $rule = [
                'product_id' => 'required|numeric|exists:cscart_products,product_id',
                'amount' => 'numeric',
            ];
            $validator = Validator::make($request->all(), $rule);

            if ($validator->fails()) {
                return $this->responseError(self::VALIDATE_ERROR, $validator->errors()->first());
            }

            $product = Product::with([
                'mainPair' => function ($query) {
                        $query->where('cscart_images_links.type', '=', 'M');
                        $query->where('cscart_images_links.object_type', '=', 'product');
                    },
                    'mainPair.detailed',
                ])
                ->where('product_id', $productId)
                ->first();

            $productPopularity = $product->productPopularity;
            $checkExist = UserSessionProduct::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('type', 'C');

            DB::beginTransaction();

            if ($checkExist->first()) {
                $amountUpdate = $checkExist->first()->amount + $amount;
                $productPopularityData = [
                    'added' => $productPopularity->added + $amount,
                    'total' => $productPopularity->total + 5 *$amount,
                ];

                if ($checkExist->update(['amount' => $amountUpdate])
                    && ProductPopularity::where('product_id', $productId)->update($productPopularityData)
                ) {
                    DB::commit();
                    return $this->responseSuccess([
                        'status' => true,
                        'message' => 'Added to cart',
                    ]);
                }
                DB::rollback();

                return $this->responseError(self::HTTP_SERVER_ERROR, 'Add to cart is failed');
            }

            $dataUserSessionProduct = [
                'user_id' => $userId,
                'timestamp' => time(),
                'type' => 'C',
                'user_type' => 'R',
                'item_id' => generate_cart_id($productId),
                'item_type' => $product->product_type,
                'product_id' => $productId,
                'amount' => $amount,
                'price' => $product->price,
                'session_id' => '',
                'ip_address' => encode_ip($request->ip()),
                'order_id' => 0,
            ];

            $dataExtra = $dataUserSessionProduct;
            $dataExtra['product_code'] = $product->product_code;
            $dataExtra['product'] = $product->productDescription->product;
            $dataExtra['company_id'] = $product->company_id;
            $dataExtra['is_edp'] = $product->is_edp ? $product->is_edp : 0;
            $dataExtra['edp_shipping'] = $product->edp_shipping;
            $dataExtra['exceptions_type'] = $product->exceptions_type;
            $dataExtra['display_price'] = $product->price;
            $dataExtra['return_period'] = $product->return_period;
            $dataExtra['product_options'] = $product->product_options ? $product->product_options : [];
            $dataExtra['main_pair'] = $product->mainPair ? $product->mainPair->toArray() : [];
            $dataExtra['amount_total'] = $amount;
            $dataExtra['modifiers_price'] = 0;
            $dataExtra['stored_discount'] = $product->stored_discount ? $product->stored_discount : 'N';
            $dataExtra['stored_price'] = $product->stored_price ? $product->stored_price : 'N';
            $dataExtra['category_ids'] = $product->category_ids ? $product->category_ids : [];
            $dataExtra['discount'] = $product->discount ? $product->discount : 0;
            $dataExtra['base_price'] = $product->price;
            $dataExtra['promotions'] = [];

            if (isset($product->options_type)) {
                $dataExtra['options_type'] = $product->options_type;
            }
            if (isset($product->tracking)) {
                $dataExtra['tracking'] = $product->tracking;
            }
            $dataExtra['extra'] = [
                'prodcut_options' => [],
                'unlimited_download' => $product->unlimited_download,
                'return_period' => $product->return_period,
            ];

            $extra = serialize($dataExtra);
            $dataUserSessionProduct['extra'] = $extra;

            $userSessionProduct = UserSessionProduct::insert($dataUserSessionProduct);

            $productPopularityData = [
                'added' => $productPopularity->added + 1,
                'total' => $productPopularity->total + 5,
            ];

            if ($userSessionProduct && ProductPopularity::where('product_id', $productId)->update($productPopularityData)) {
                DB::commit();

                return $this->responseSuccess([
                    'status' => true,
                    'message' => 'Added to cart',
                ]);
            }
            DB::rollback();

            return $this->responseError(self::HTTP_SERVER_ERROR, 'Add to cart is failed');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);

            return $this->responseError(self::HTTP_SERVER_ERROR, $e->getMessage());
        }
    }

    public function deleteCart(Request $request)
    {
        try {
            $userId = $this->getIdFromToken($request);
            $productId = $request->get('product_id');
            $clear = $request->get('clear');
            $rule = [
                'product_id' => 'numeric|exists:cscart_user_session_products,product_id',
                'clear' => 'numeric|between:0,1',
            ];
            $validator = Validator::make($request->all(), $rule);

            if ($validator->fails()) {
                return $this->responseError(self::VALIDATE_ERROR, $validator->errors()->first());
            }

            if (!$clear && !$productId) {
                return $this->responseError(self::VALIDATE_ERROR, 'Missing product id or clear');
            }

            if ($clear == 1) {
                $product = UserSessionProduct::where('user_id', $userId)
                    ->where('type', 'C');
            } elseif ($productId) {
                $product = UserSessionProduct::where('product_id', $productId)
                    ->where('user_id', $userId)
                    ->where('type', 'C');
            }
            DB::beginTransaction();

            $countProduct = $product->count();
            $productPopularity = ProductPopularity::where('product_id', $productId)->first();
            $productPopularityData = [
                'added' => ($productPopularity->added - $countProduct) > 0 ? ($productPopularity->added - $countProduct) : 0,
                'total' => ($productPopularity->total - 5*$countProduct) > 0 ? ($productPopularity->total - 5*$countProduct) : 0,
            ];

            if ($product->delete() && ProductPopularity::where('product_id', $productId)->update($productPopularityData)) {
                DB::commit();

                return $this->responseSuccess([
                    'status' => true,
                    'message' => 'Deleted to cart',
                ]);
            }

            DB::rollback();

            return $this->responseError(self::HTTP_SERVER_ERROR, 'Can not delete product to cart');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);

            return $this->responseError(self::HTTP_SERVER_ERROR, $e->getMessage());
        }
    }

    public function paymentLink(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
            $order = DB::table('cscart_orders')->where('order_id', $orderId)->first();
            $user = User::where('user_id', $order->user_id)->first();
            $payment = DB::table('cscart_payments')->where('payment_id', $order->payment_id)->first();
            $processorParams = unserialize($payment->processor_params);
            $option = ["timeout" => "20"];
            $request = new \HTTP_Request2('http://secure.epsilon.jp/cgi-bin/order/receive_order3.cgi', \HTTP_Request2::METHOD_POST, $option);
            $request->addPostParameter('version', '2' );
            $request->addPostParameter('contract_code', $processorParams['contract_code']);
            $request->addPostParameter('user_id', $user->user_id);
            $request->addPostParameter('user_name', mb_convert_encoding($user->firstname . $user->lastname, 'EUC-JP' , 'UTF-8'));
            $request->addPostParameter('user_mail_add', $user->email);
            $request->addPostParameter('item_code', 'EPSILON-0001');
            $request->addPostParameter('item_name', mb_convert_encoding('お買い上げ商品', "UTF-8", "auto"));
            $request->addPostParameter('order_number', $orderId . date('ymdHis'));
            $request->addPostParameter('st_code', epsilon_get_st_code($processorParams));
            $request->addPostParameter('mission_code', '1');
            $request->addPostParameter('item_price', round($order->total));
            $request->addPostParameter('process_code', '1');
            $request->addPostParameter('memo1', '');
            $request->addPostParameter('memo2', '');
            $request->addPostParameter('xml', '1');
            $request->addPostParameter('character_code', 'UTF8' );

            // HTTPリクエスト実行
            $response = $request->send();

            // 応答内容(XML)の解析
            $res_code = $response->getStatus();
            $res_content = $response->getBody();
            // dd($res_content);

            $temp_xml_res = str_replace("x-sjis-cp932", "EUC-JP", $res_content);
            $unserializer = new \XML_Unserializer();
            $unserializer->setOption('parseAttributes', TRUE);
            $unseriliz_st = $unserializer->unserialize($temp_xml_res);
            if ($unseriliz_st === true) {
                //xmlを解析
                $res_array = $unserializer->getUnserializedData();
                $xml_redirect_url = "";
                $xml_error_cd = "";
                $xml_error_msg = "";
                $xml_memo1_msg = "";
                $xml_memo2_msg = "";
                $is_error = false;

                foreach($res_array['result'] as $uns_k => $uns_v){

                    list($result_atr_key, $result_atr_val) = each($uns_v);

                    switch ($result_atr_key) {
                        case 'redirect':
                            $xml_redirect_url = rawurldecode($result_atr_val);
                            break;
                        case 'err_code':
                            $is_error = true;
                            $xml_error_msg = $result_atr_val;
                            break;
                        case 'err_detail':
                            $xml_error_msg = $result_atr_val;
                            break;
                        case 'memo1':
                            // コンバート元の文字コードを検出するよう変更
                            $xml_error_msg = mb_convert_encoding(urldecode($result_atr_val), "UTF-8", fn_detect_encoding(urldecode($result_atr_val), 'S'));

                            break;
                        case 'memo2':
                            // コンバート元の文字コードを検出するよう変更
                            $xml_error_msg = mb_convert_encoding(urldecode($result_atr_val), "UTF-8", fn_detect_encoding(urldecode($result_atr_val), 'S'));
                            break;
                        default:
                            break;
                    }
                }

            // XMLのパースに失敗した場合
            }else{
                $is_error = true;
            }

            if($is_error){
                return $this->responseError(self::HTTP_SERVER_ERROR, $xml_error_msg);
            }else{
                return $this->responseSuccess(['url' => $xml_redirect_url]);
            }

        } catch(Exception $e) {
            Log::error($e);

            return $this->responseError(self::HTTP_SERVER_ERROR, $e->getMessage());
        }
    }
}