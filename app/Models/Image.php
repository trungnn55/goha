<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'cscart_images';

    protected $appends = ['object_id', 'object_type', 'alt', 'http_image_path', 'https_image_path', 'absolute_path', 'relative_path'];

    public function getHttpImagePathAttribute()
    {
        return config('app.http_url') . '/images/detailed/' . floor_image($this->image_id/1000) . '/' . $this->image_path;
    }

    public function getHttpsImagePathAttribute()
    {
        return config('app.https_url') . '/images/detailed/' . floor_image($this->image_id/1000) . '/' . $this->image_path;
    }

    public function getAbsolutePathAttribute()
    {
        return config('app.absolute_path') . '/images/detailed/' . floor_image($this->image_id/1000) . '/' . $this->image_path;
    }

    public function getRelativePathAttribute()
    {
        return 'detailed/' . floor_image($this->image_id/1000) . '/' . $this->image_path;
    }

    public function getAltAttribute()
    {
        return '';
    }

    public function getObjectIdAttribute()
    {
        return ImageLink::where('detailed_id', $this->image_id)->first()->object_id;
    }

    public function getObjectTypeAttribute()
    {
        return ImageLink::where('detailed_id', $this->image_id)->first()->object_type;
    }
}
