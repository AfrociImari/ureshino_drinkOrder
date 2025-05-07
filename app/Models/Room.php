<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    protected $table = 'room';
    protected $primaryKey = 'room_id';  // Use 'room_id' instead of the default 'id'
     /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'room_name'
    ];

    // One room has many checkin items
    public function checkin()
    {
        return $this->hasMany(CheckIn::class, 'room_id');
    }
}
