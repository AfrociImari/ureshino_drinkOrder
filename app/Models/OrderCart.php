<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCart extends Model
{
    //
    protected $table = 'order_cart';

     /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'checkin_id',
        'product_name',
        'variant',
        'unitprice',
        'quantity',
        'total_amount',
    ];
}
