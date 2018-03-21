<?php

namespace App\Http\Controllers\Api;
use DB;

class BannerController extends BaseController
{
    public function index()
    {
        $banners = DB::table('cscart_banners as b')
            ->join('cscart_banner_descriptions as bd', 'b.banner_id', '=', 'bd.banner_id')
            ->join('cscart_banner_images as bi', 'b.banner_id', '=', 'bi.banner_id')
            ->join('cscart_images_links as il', function($join) {
                $join->on('bi.banner_image_id', '=', 'il.object_id');
                $join->on('il.object_type', '=', 'promo');
            })
            ->join('cscart_images as i', 'il.image_id', '=', 'i.image_id')
            ->select('b.banner_id', 'bd.banner', 'bd.url', 'i.image_path', 'i.image_id')
            ->where('b.position', '>', 0)
            ->orderBy('banner_id', 'DESC')
            ->get();

        foreach ($banners as $key => $value) {
            $banners[$key]->image_path = 'https://goha.jp/images/promo/' . $this->floor($value->image_id/1000) . '/' . $value->image_path
        }

        return $this->responseSuccess($banners,[]);
    }

    public static function floor($x)
    {
        // (int) (string) emulates floor() call for positive values.
        // This is required because floor((double) 201) may be equal to 200, because of
        // PHP's internal floating point numbers representation (i.e. 201.0 is stored as 200.999999...)
        $is_negative = $x < 0;
        $x = (string) $x;
        // negative values have to be floored down
        if ($is_negative && strpos($x, '.')) {
            list($x, $dec) = explode('.', $x);
            $x = $x - ($dec ? 1 : 0);
        }

        return (int) $x;
    }
}