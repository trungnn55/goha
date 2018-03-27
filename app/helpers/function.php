<?php

function encode_token($id)
{
    $data = [
        'id' => $id,
        'expire' => config('app.expire_token'),
    ];

    return base64_encode(serialize($data));
}

function decode_token($data)
{
    try {
        return unserialize(base64_decode($data));
    } catch (\Exception $e) {
        return false;
    }
}

function is_json($string)
{
   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

function floor_image($x)
{
    $is_negative = $x < 0;
    $x = (string) $x;
    if ($is_negative && strpos($x, '.')) {
        list($x, $dec) = explode('.', $x);
        $x = $x - ($dec ? 1 : 0);
    }

    return (int) $x;
}
