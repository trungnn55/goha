<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ProductSale;
use App\Models\Product;
use DB;

class ProductController extends BaseController
{
    public function productSale()
    {
        $data =[];
        $params = [];
        $sortBy = 'product_id';
        $sortOrder = 'DESC';

        $products = Product::join('cscart_product_sales as ps', 'cscart_products.product_id', '=', 'ps.product_id')
            ->with([
                'imagePairs' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'A');
                    $query->where('cscart_images_links.object_type', '=', 'product');
                },
                'imagePairs.detailed',
                'mainPairs' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'M');
                    $query->where('cscart_images_links.object_type', '=', 'product');
                },
                'mainPairs.detailed',
            ])
            ->where('ps.amount', '>', 0)
            ->orderBy('cscart_products.' . $sortBy, $sortOrder)
            ->paginate(config('app.per_page'));

        if ($products) {
            $params = $this->params($products, $sortBy, $sortOrder);
            $data = $products->items();
        }

        return $this->responseSuccess($data, $params);
    }

    public function hotDeal()
    {
        $data = [];
        $params = [];
        $sortBy = 'viewed';
        $sortOrder = 'DESC';

        $products = Product::join('cscart_product_popularity as pp', 'cscart_products.product_id', '=', 'pp.product_id')
            ->with([
                'imagePairs' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'A');
                    $query->where('cscart_images_links.object_type', '=', 'product');
                },
                'imagePairs.detailed',
                'mainPairs' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'M');
                    $query->where('cscart_images_links.object_type', '=', 'product');
                },
                'mainPairs.detailed',
            ])
            ->orderBy('pp.' . $sortBy, $sortOrder)
            ->paginate(config('app.per_page'));

        if ($products) {
            $params = $this->params($products, $sortBy, $sortOrder);
            $data = $products->items();
        }

        return $this->responseSuccess($data, $params);
    }

    public function favorite(Request $request)
    {
        $data = [];
        $params = [];
        $sortBy = 'product_id';
        $sortOrder = 'ASC';
        $userId = $this->getIdFromToken($request);

        $products = Product::join('cscart_user_session_products as usp', 'cscart_products.product_id', '=', 'usp.product_id')
            ->with([
                'imagePairs' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'A');
                    $query->where('cscart_images_links.object_type', '=', 'product');
                },
                'imagePairs.detailed',
                'mainPairs' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'M');
                    $query->where('cscart_images_links.object_type', '=', 'product');
                },
                'mainPairs.detailed',
            ])
            ->where('usp.user_id', $userId)
            ->orderBy('usp.' . $sortBy, $sortOrder)
            ->paginate(config('app.per_page'));

        if ($products) {
            $params = $this->params($products, $sortBy, $sortOrder);
            $data = $products->items();
        }

        return $this->responseSuccess($data, $params);
    }

    public function addFavorite(Request $request)
    {
        
    }
}