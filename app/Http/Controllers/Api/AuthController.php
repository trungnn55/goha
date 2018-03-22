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
                return $this->responseSuccess($dataResponse);
            }
        }

        return $this->responseError(self::RECORD_NOT_FOUND, 'not found');
    }

    public function signUp(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:cscart_users,email',
            'password' => 'required|min:6',
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
        $user = DB::table('cscart_users')->insert($dataUser);

        if ($user) {
            return $this->responseSuccess([], []);
        }

        return $this->responseError([], []);
    }

    function hashPassword($password, $salt)
    {
        return md5(md5($password) . md5($salt));
    }

    function fnGenerateSalt($length = 10)
    {
        $length = $length > 10 ? 10 : $length;

        $salt = '';

        for ($i = 0; $i < $length; $i++) {
            $salt .= chr(rand(33, 126));
        }

        return $salt;
    }
}