<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    protected $table = 'cscart_product_sales';

    protected $fillable = [
        'category_id',
        'product_id',
        'amount',
    ];

    public function imageLinks()
    {
        return $this->hasMany(ImageLink::class, 'object_id', 'product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
