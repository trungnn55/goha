<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ProductSale;
use App\Models\Product;
use DB;

class ProductController extends BaseController
{
    public function productSale(Request $request)
    {
        $data =[];
        $params = [];
        $sortBy = 'product_id';
        $sortOrder = 'DESC';
        $itemPerPage = $request->get('items_per_page') ? $request->get('items_per_page') : config('app.per_page');

        $products = Product::join('cscart_product_sales as ps', 'cscart_products.product_id', '=', 'ps.product_id')
            ->with([
                'imagePairs' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'A');
                    $query->where('cscart_images_links.object_type', '=', 'product');
                },
                'imagePairs.detailed',
                'mainPair' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'M');
                    $query->where('cscart_images_links.object_type', '=', 'product');
                },
                'mainPair.detailed',
            ])
            ->where('ps.amount', '>', 0)
            ->orderBy('cscart_products.' . $sortBy, $sortOrder)
            ->paginate($itemPerPage);

        if ($products) {
            $params = $this->params($products, $sortBy, $sortOrder);
            $data = $products->items();
        }

        return $this->responseSuccess(['products' => $data], $params);
    }

    public function hotDeal(Request $request)
    {
        $data = [];
        $params = [];
        $sortBy = 'viewed';
        $sortOrder = 'DESC';
        $itemPerPage = $request->get('items_per_page') ? $request->get('items_per_page') : config('app.per_page');

        $products = Product::join('cscart_product_popularity as pp', 'cscart_products.product_id', '=', 'pp.product_id')
            ->with([
                'imagePairs' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'A');
                    $query->where('cscart_images_links.object_type', '=', 'product');
                },
                'imagePairs.detailed',
                'mainPair' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'M');
                    $query->where('cscart_images_links.object_type', '=', 'product');
                },
                'mainPair.detailed',
            ])
            ->orderBy('pp.' . $sortBy, $sortOrder)
            ->paginate($itemPerPage);

        if ($products) {
            $params = $this->params($products, $sortBy, $sortOrder);
            $data = $products->items();
        }

        return $this->responseSuccess(['products' => $data], $params);
    }

    public function favorite(Request $request)
    {
        $data = [];
        $params = [];
        $sortBy = 'product_id';
        $sortOrder = 'ASC';
        $userId = $this->getIdFromToken($request);
        $itemPerPage = $request->get('items_per_page') ? $request->get('items_per_page') : config('app.per_page');

        $products = Product::join('cscart_user_session_products as usp', 'cscart_products.product_id', '=', 'usp.product_id')
            ->with([
                'imagePairs' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'A');
                    $query->where('cscart_images_links.object_type', '=', 'product');
                },
                'imagePairs.detailed',
                'mainPair' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'M');
                    $query->where('cscart_images_links.object_type', '=', 'product');
                },
                'mainPair.detailed',
            ])
            ->where('usp.user_id', $userId)
            ->where('usp.type', 'W')
            ->orderBy('usp.' . $sortBy, $sortOrder)
            ->paginate($itemPerPage);

        if ($products) {
            $params = $this->params($products, $sortBy, $sortOrder);
            $data = $products->items();
        }

        return $this->responseSuccess(['products' => $data], $params);
    }

    public function cart(Request $request)
    {
        $data = [];
        $params = [];
        $sortBy = 'product_id';
        $sortOrder = 'ASC';
        $userId = $this->getIdFromToken($request);
        $itemPerPage = $request->get('items_per_page') ? $request->get('items_per_page') : config('app.per_page');

        $products = Product::join('cscart_user_session_products as usp', 'cscart_products.product_id', '=', 'usp.product_id')
            ->with([
                'imagePairs' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'A');
                    $query->where('cscart_images_links.object_type', '=', 'product');
                },
                'imagePairs.detailed',
                'mainPair' => function ($query) {
                    $query->where('cscart_images_links.type', '=', 'M');
                    $query->where('cscart_images_links.object_type', '=', 'product');
                },
                'mainPair.detailed',
            ])
            ->where('usp.user_id', $userId)
            ->where('usp.type', 'C')
            ->orderBy('usp.' . $sortBy, $sortOrder)
            ->paginate($itemPerPage);

        if ($products) {
            $params = $this->params($products, $sortBy, $sortOrder);
            $data = $products->items();
        }

        return $this->responseSuccess(['products' => $data], $params);
    }
}