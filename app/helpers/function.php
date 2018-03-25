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
