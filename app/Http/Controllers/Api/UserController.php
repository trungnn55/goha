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

            return $this->responseError(false, $e->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
        $id = $this->getIdFromToken($request);
        $user = DB::table('cscart_users')->where('user_id', $id)->first();
        $rules = [
            'email' => [
                'required',
                'email',
                Rule::unique('cscart_users')->ignore($id, 'user_id')
            ],
            'password' => 'required|min:6',
            'birthday' => 'date',
            'b_firstname' => 'required',
            'b_lastname' => 'required',
            'b_phone' => 'required',
            'b_country' => '',
            'b_zipcode' => '',
            'b_state' => '',
            'b_address' => '',
            'b_address_2' => '',
            'ship_to_another' => '',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->responseError(self::VALIDATE_ERROR, $validator->errors()->first());
        }

        $hashPassword = $this->hashPassword($request->get('password'), $user->salt);

        $dataUserUpdate = [
            'firstname' => $request->get('b_firstname'),
            'lastname' => $request->get('b_lastname'),
            'email' => $request->get('email'),
            'phone' => $request->get('b_phone'),
            'birthday' => $request->get('birthday') ? strtotime($request->get('birthday')) : 0,
        ];

        $dataUserProfile = [
            'b_firstname' => $request->get('b_firstname'),
            'b_lastname' => $request->get('b_lastname'),
            'b_address' => $request->get('b_address') ? $request->get('b_address') : '',
            'b_address_2' => $request->get('b_address_2') ? $request->get('b_address_2') : '',
            'b_city' => $request->get('b_city') ? $request->get('b_city') : '',
            'b_state' => $request->get('b_state') ? $request->get('b_state') : '',
            'b_country' => $request->get('b_country') ? $request->get('b_country') : '',
            'b_zipcode' => $request->get('b_zipcode') ? $request->get('b_zipcode') : '',
            'b_phone' => $request->get('b_phone') ? $request->get('b_phone') : '',

            's_firstname' => $request->get('b_firstname'),
            's_lastname' => $request->get('b_lastname'),
            's_address' => $request->get('b_address') ? $request->get('b_address') : '',
            's_address_2' => $request->get('b_address_2') ? $request->get('b_address_2') : '',
            's_city' => $request->get('b_city') ? $request->get('b_city') : '',
            's_state' => $request->get('b_state') ? $request->get('b_state') : '',
            's_country' => $request->get('b_country') ? $request->get('b_country') : '',
            's_zipcode' => $request->get('b_zipcode') ? $request->get('b_zipcode') : '',
            's_phone' => $request->get('b_phone') ? $request->get('b_phone') : '',
        ];

        if ($hashPassword !== $user->password) {
            $dataUserUpdate['password'] = $this->hashPassword($request->get('password'), $this->fnGenerateSalt());
            $dataUserUpdate['salt'] = $user->salt;
            $dataUserUpdate['password_change_timestamp'] = time();
        }
        // dd($dataUserUpdate);
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

            return $this->responseError(false, $e->getMessage());
        }

    }
}