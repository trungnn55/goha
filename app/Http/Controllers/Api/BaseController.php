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

    public static function floor($x)
    {
        $is_negative = $x < 0;
        $x = (string) $x;
        if ($is_negative && strpos($x, '.')) {
            list($x, $dec) = explode('.', $x);
            $x = $x - ($dec ? 1 : 0);
        }

        return (int) $x;
    }

    public function checkToken($token)
    {
        try {
            $tokenHeader = $request->header('Authorization');
            if ($tokenHeader) {
                $token = decode_token($tokenHeader);
                $user = DB::table('cscart_users')
                    ->where('user_id', $token['id'])
                    ->first();
                if ($user && $token > time()) {
                    return $next($request);
                }
            }
        } catch(\Exception $e) {
            \Log::error($e);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid token'
        ]);
    }
}
