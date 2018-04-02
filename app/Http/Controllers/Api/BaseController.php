<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Log;
use Exception;
use DB;

class BaseController extends Controller
{
    const HTTP_OK = 200;
    const HTTP_SERVER_ERROR = 500;
    const VALIDATE_ERROR = 701;
    const RECORD_NOT_FOUND = 702;
    const RECORD_EXIST = 703;

    public function responseSuccess($data, $params = [])
    {
        $data['params'] = $params;

        return response()->json($data);
    }

    public function responseError($code, $message)
    {
        return response()->json([
            'code' => $code,
            'status' => false,
            'message' => $message,
        ]);
    }

    public function params($dataPaginate, $sortBy, $sortOrder)
    {
        return [
            'total_item' => $dataPaginate->total(),
            'current_page' => $dataPaginate->currentPage(),
            'per_page' => $dataPaginate->perPage(),
            'query' => $dataPaginate->nextPageUrl(),
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ];
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

    public function getIdFromToken(Request $request)
    {
        try {
            $tokenKey = $request->header('token-key');
            if ($tokenKey) {
                $token = decode_token($tokenKey);
                if ($token !== false) {
                    $user = DB::table('cscart_users')
                        ->where('user_id', $token['id'])
                        ->first();
                    if ($user && $token > time()) {
                        return $token['id'];
                    }
                }
                throw new Exception('Invalid token key');
            }
        } catch(Exception $e) {
            Log::error($e);
            throw new Exception($e->getMessage());
        }

    }
}
