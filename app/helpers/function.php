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

function decode_ip($ip)
{
    // Empty or not encoded IP
    if (empty($ip) || strpos($ip, '.') !== false || strpos($ip, ':') !== false) {
        return $ip;
    }

    return inet_ntop($this->hex2bin($ip));
}

if (!function_exists('hex2bin')) {
    /**
     * Convert hex to binary
     * @param  string $hex
     * @return string Returns the binary representation of the given data.
     */
    function hex2bin($hex)
    {
        return pack("H*", $hex);
    }
}

function encode_ip($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        return bin2hex(inet_pton($ip));
    }

    return '';
}


function generate_cart_id($product_id)
{
    $_cid = array();
    array_unshift($_cid, $product_id);
    $cart_id = fn_crc32(implode('_', $_cid));

    return $cart_id;
}

function fn_crc32($key)
{
    return sprintf('%u', crc32($key));
}
