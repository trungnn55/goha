<?php

function encode_token($id) {
    $data = [
        'id' => $id,
        'expire' => config('app.expire_token'),
    ];

    return base64_encode(serialize($data));
}

function decode_token($data) {
    try {
        return unserialize(base64_decode($data));
    } catch (\Exception $e) {
        return false;
    }
}

function is_json($string){
   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}
