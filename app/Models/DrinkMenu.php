<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrinkMenu extends Model
{
    //
    protected $table = 'drink_menu';
    protected $primaryKey = 'menu_id';  // Use 'menu_id' instead of the default 'id'

     /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_name', 'category_id', 'variant', 'unitprice', 'image'
    ];

     // A drink belongs to one category
     public function category()
     {
         return $this->belongsTo(DrinkCategory::class, 'category_id');
     }
}
