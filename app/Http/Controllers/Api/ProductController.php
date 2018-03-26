<?php

namespace App\Http\Controllers\Api;
use App\Models\ProductSale;
use App\Models\Product;
use DB;

class ProductController extends BaseController
{
    public function productSale()
    {
        $data =[];
        $params = [];

        $products = Product::join('cscart_product_sales as ps', 'cscart_products.product_id', '=', 'ps.product_id')
            ->with([
                'imagePairs' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'A');
                },
                'mainPairs' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'M');
                },
            ])
            ->where('ps.amount', '>', 0)
            ->orderBy('cscart_products.product_id', 'DESC')
            ->paginate(config('app.per_page'));

        if ($products) {
            $params = $this->params($products, 'product_id', 'DESC');
            $data = $products->items();
        }

        return $this->responseSuccess($data, $params);
    }
}