<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrinkOrder extends Model
{
    //
    protected $table = 'drink_order';
    protected $primaryKey = 'order_id';  // Use 'order_id' instead of the default 'id'
     /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'checkin_id',
        'date',
        'type',
        'product_name',
        'variant',
        'quantity',
        'amount',
        'served_flg'
    ];

     // A order belongs to one checkin
     public function checkin()
     {
         return $this->belongsTo(CheckIn::class, 'checkin_id');
     }
}
