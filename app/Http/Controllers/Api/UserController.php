<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

            return $this->responseSuccess($user,[]);
        } catch (Exception $e) {
            Log::error($e);

            return $this->responseError(self::HTTP_SERVER_ERROR, $e->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
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

        DB::beginTransaction();
        try {
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
}