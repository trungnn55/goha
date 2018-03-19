<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    public function responseSuccess($data, $params)
    {
        return response()->json([
            'data' => $data,
            'params' => $params,
        ]);
    }

    public function responseError($message, $code)
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
        ]);
    }
}
