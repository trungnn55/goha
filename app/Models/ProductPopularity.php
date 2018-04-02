<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPopularity extends Model
{
    protected $table = 'cscart_product_popularity';

    protected $primaryKey = null;

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'viewed',
        'added',
        'deleted',
        'bought',
        'total',
    ];
}
