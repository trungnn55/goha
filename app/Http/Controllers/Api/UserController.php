<?php

namespace App\Http\Controllers\Api;
use DB;

class UserController extends BaseController
{
    public function show($id)
    {
        $user = DB::table('cscart_users')
            ->select('email', 'user_type', 'company_id', 'status', 'firstname', 'lastname', 'company', 'is_root', 'user_id', 'user_login')
            ->where('user_id', $id)
            ->first();

        return $this->responseSuccess($user,[]);
    }
}