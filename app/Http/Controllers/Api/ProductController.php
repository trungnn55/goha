<?php

namespace App\Http\Controllers\Api;
use DB;

class ProductController extends BaseController
{
    public function productSale()
    {
        $products = DB::table('cscart_product_sales as ps')
            ->join('cscart_products as p', 'ps.product_id', '=', 'p.product_id')
            ->join('cscart_product_prices as pp', 'ps.product_id', '=', 'pp.product_id')
            ->select(
                'p.*',
                'pp.price',
            )
            ->where('ps.amount', '>', 0)
            ->get();


        return $this->responseSuccess($products,[]);
    }
}