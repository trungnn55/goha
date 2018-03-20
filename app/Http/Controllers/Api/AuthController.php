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
                return $this->responseSuccess(['sign_in' => true]);
            }
        }

        return $this->responseError(self::RECORD_NOT_FOUND, 'not found');
    }

    function hashPassword($password, $salt)
    {
        return md5(md5($password) . md5($salt));
    }
}