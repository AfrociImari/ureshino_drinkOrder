<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrinkCategory extends Model
{
    //
     //
     protected $table = 'drink_category';
     /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_name','parent_category'
    ];

    // One category has many drink menu items
    public function drinks()
    {
        return $this->hasMany(DrinkMenu::class, 'category_id');
    }
}
