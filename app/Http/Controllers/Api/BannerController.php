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
            ->join('cscart_images as i', 'i.banner_id', '=', 'bi.banner_id')
            ->select('b.banner_id', 'bd.banner', 'bd.url', )
            ->where('b.position', '>', 0)
            ->orderBy('banner_id', 'DESC')
            ->get();

        return $this->responseSuccess($banners,[]);
    }
}