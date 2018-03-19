<?php

namespace App\Http\Controllers\Api;

class AuthController extends BaseController
{
    public function login(\Request $request)
    {
        // dd(base64_encode('trung'));
        dd($this->hashPassword('trung1992'));
        return $this->responseSuccess([],[]);
    }

    function hashPassword($str)
    {
        return hash("md5", $str . "salt");
    }
}