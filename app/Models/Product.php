<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'cscart_products';

    protected $appends = ['price', 'base_price', 'discussion_type', 'discussion_thread_id', 'product'];

    // RELATIONSHIP
    public function imagePairs()
    {
        return $this->hasMany(ImageLink::class, 'object_id', 'product_id');
    }

    public function productPrice()
    {
        return $this->belongsTo(ProductPrice::class, 'product_id', 'product_id');
    }

    public function mainPairs()
    {
        return $this->hasMany(ImageLink::class, 'object_id', 'product_id');
    }

    public function discussion()
    {
        return $this->hasOne(Discussion::class, 'object_id', 'product_id');
    }

    public function productDescription()
    {
        return $this->hasOne(ProductDescription::class, 'product_id', 'product_id');
    }


    // GET ATTRIBUTE
    public function getPriceAttribute()
    {
        return $this->productPrice->price;
    }

    public function getDiscussionTypeAttribute()
    {
        return $this->discussion->type;
    }

    public function getDiscussionThreadIdAttribute()
    {
        return $this->discussion->thread_id;
    }

    public function getProductAttribute()
    {
        return $this->productDescription->product;
    }

    public function getBasePriceAttribute()
    {
        return $this->productPrice->price;
    }
}
