<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageLink extends Model
{
    protected $table = 'cscart_images_links';

    public function detailed()
    {
        return $this->hasOne(Image::class, 'image_id', 'detailed_id');
    }
}
