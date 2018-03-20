<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    const VALIDATE_ERROR = 701;
    const RECORD_NOT_FOUND = 702;

    public function responseSuccess($data, $params = [])
    {
        return response()->json([
            'data' => $data,
            'params' => $params,
        ]);
    }

    public function responseError($code, $message)
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
        ]);
    }
}
