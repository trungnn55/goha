<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use DB;
use Validator;

class AuthController extends BaseController
{
    public function signIn(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseError(self::VALIDATE_ERROR, $validator->errors()->first());
        }

        $user = DB::table('cscart_users')
            ->where('email', $email)
            ->first();

        if ($user) {
            $hashPassword = $this->hashPassword($password, $user->salt);
            $userConfirm = DB::table('cscart_users')
                ->where('email', $email)
                ->where('password', $hashPassword)
                ->first();
            if ($userConfirm) {
                $token = encode_token($userConfirm->user_id);
                $dataResponse = [
                    'status' => true,
                    'token' => $token,
                ];
                return $this->responseSuccess(['data' => $dataResponse]);
            }
        }

        return $this->responseError(self::RECORD_NOT_FOUND, 'email or password is incorrect');
    }

    public function signUp(Request $request)
    {
        try {
            $email = $request->get('email');
            $password = $request->get('password');
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:cscart_users,email',
                'password' => 'required',
                'birthday' => 'date',
            ]);

            if ($validator->fails()) {
                return $this->responseError(self::VALIDATE_ERROR, $validator->errors()->first());
            }
            $salt = $this->fnGenerateSalt();
            $dataUser = [
                'user_login' => 'User_' . (DB::table('cscart_users')->orderBy('user_id', 'DESC')->first()->user_id + 1),
                'email' => $email,
                'password' => $this->hashPassword($password, $salt),
                'birthday' => $request->get('birthday') ? strtotime($request->get('birthday')) : 0,
                'salt' => $salt,
                'timestamp' => time(),
                'lang_code' => 'ja',
            ];

            DB::beginTransaction();

            $user = DB::table('cscart_users')->insertGetId($dataUser);
            $userProfile = DB::table('cscart_user_profiles')->insert([
                'user_id' => $user,
                'profile_type' => 'P',
                'profile_name' => 'メイン',
            ]);

            if ($user && $userProfile) {
                DB::commit();
                return $this->responseSuccess([
                    'status' => true,
                    'message' => 'Sign up successfuly',
                ]);
            }
            DB::rollback();

            return $this->responseError(self::HTTP_SERVER_ERROR, 'Failed to sign up');
        } catch (Exception $e) {
            Log::error($e);

            return $this->responseError(self::HTTP_SERVER_ERROR, $e->getMessage());
        }

    }
}