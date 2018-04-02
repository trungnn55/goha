<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSessionProduct extends Model
{
    protected $table = 'cscart_user_session_products';

    protected $primaryKey = null;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'timestamp',
        'type',
        'user_type',
        'item_id',
        'item_type',
        'product_id',
        'amount',
        'price',
        'extra',
        'session_id',
        'ip_address',
        'order_id',
    ];
}
