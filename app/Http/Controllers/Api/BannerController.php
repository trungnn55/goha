<?php

namespace App\Http\Controllers\Api;
use DB;

class BannerController extends BaseController
{
    public function index()
    {
        $data = [];
        $params = [];
        $banners = DB::table('cscart_banners as b')
            ->join('cscart_banner_descriptions as bd', 'b.banner_id', '=', 'bd.banner_id')
            ->join('cscart_banner_images as bi', 'b.banner_id', '=', 'bi.banner_id')
            ->join('cscart_images_links as il', 'bi.banner_image_id', '=', 'il.object_id')
            ->join('cscart_images as i', 'il.image_id', '=', 'i.image_id')
            ->select('b.banner_id', 'bd.banner', 'bd.url', 'i.image_path', 'i.image_id')
            ->where('b.position', '>', 0)
            ->where('il.object_type', 'promo')
            ->where('bd.lang_code', 'ja')
            ->where('bi.lang_code', 'ja')
            ->orderBy('banner_id', 'DESC')
            ->distinct()
            ->paginate(config('app.per_page'));

        foreach ($banners as $key => $value) {
            $banners[$key]->image_path = 'https://goha.jp/images/promo/' . $this->floor($value->image_id/1000) . '/' . $value->image_path;
        }

        if ($banners) {
            $params = $this->params($banners, 'banner_id', 'DESC');
            $data = $banners->items();
        }

        return $this->responseSuccess($data,$params);
    }


}