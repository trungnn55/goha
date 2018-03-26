<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageLink extends Model
{
    protected $table = 'cscart_images_links';

    protected $fillable = [
        'product_id',
        'product_code',
        'product_type',
    ];
}
