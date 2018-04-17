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

// 決済区分をセット
function epsilon_get_st_code($processor_param)
{
    $st_array = array();

    $st_array[0] = ($processor_param['cc'] == 'true') ? '10' : '00';
    $st_array[1] = ($processor_param['cvs'] == 'true') ? '1' : '0';
    $st_array[2] = ($processor_param['jnb'] == 'true') ? '1' : '0';
    $st_array[3] = ($processor_param['rakutenbank'] == 'true') ? '1' : '0';
    $st_array[4] = '-0';
    $st_array[5] = ($processor_param['pez'] == 'true') ? '1' : '0';
    $st_array[6] = ($processor_param['wm'] == 'true') ? '1' : '0';
    $st_array[7] = '0';
    $st_array[8] = '-0';
    $st_array[9] = ($processor_param['paypal'] == 'true') ? '1' : '0';
    $st_array[10] = ($processor_param['bitcash'] == 'true') ? '1' : '0';
    $st_array[11] = ($processor_param['chocom'] == 'true') ? '1' : '0';
    $st_array[12] = '0';

    return implode('', $st_array);
}

function fn_detect_encoding($resource, $resource_type = 'S', $lang_code = 'ja')
{
    $enc = '';
    $str = $resource;

    if ($resource_type == 'F') {
        $str = file_get_contents($resource);
        if ($str == false) {
            return $enc;
        }
    }

    if (!fn_is_utf8($str)) {
        if (in_array($lang_code, array('en', 'fr', 'es', 'it', 'nl', 'da', 'fi', 'sv', 'pt', 'nn', 'no'))) {
            $enc = 'ISO-8859-1';
        } elseif (in_array($lang_code, array('hu', 'cs', 'pl', 'bg', 'ro'))) {
            $enc = 'ISO-8859-2';
        } elseif (in_array($lang_code, array('et', 'lv', 'lt'))) {
            $enc = 'ISO-8859-4';
        } elseif ($lang_code == 'ru') {
            $enc = fn_detect_cyrillic_charset($str);
        } elseif ($lang_code == 'ar') {
            $enc = 'ISO-8859-6';
        } elseif ($lang_code == 'el') {
            $enc = 'ISO-8859-7';
        } elseif ($lang_code == 'he') {
            $enc = 'ISO-8859-8';
        } elseif ($lang_code == 'tr') {
            $enc = 'ISO-8859-9';
            ///////////////////////////////////////////////////////////////
            // Modified for Japanese Ver by tommy from cs-cart.jp 2016 BOF
            ///////////////////////////////////////////////////////////////
        } elseif ($lang_code == 'ja') {
            // 日本語版インポートデータの文字コード（SJIS）を追加
            mb_detect_order('UTF-8,SJIS,EUC-JP,JIS,ASCII');
            $enc = mb_detect_encoding($str);
            if($enc == 'SJIS') {
                // サーバーにて利用可能な文字コードにSJIS-winがあれば優先的に利用する
                $char_codes = mb_list_encodings();
                $enc = (in_array('SJIS-win', $char_codes) ? 'SJIS-win' : 'SJIS');
            }
            ///////////////////////////////////////////////////////////////
            // Modified for Japanese Ver by tommy from cs-cart.jp 2016 EOF
            ///////////////////////////////////////////////////////////////
        }
    } else {
        $enc = 'UTF-8';
    }

    return $enc;
}