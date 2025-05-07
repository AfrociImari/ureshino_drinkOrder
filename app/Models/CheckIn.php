<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    //
    protected $table = 'check_in';
    protected $primaryKey = 'checkin_id';  // Use 'checkin_id' instead of the default 'id'
     /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'room_id',
        'date',
        'timing',
        'type',
        'qr_code',
        'order_stop'
    ];

    // A checkin belongs to one room
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    // One checkin has many orders
    public function order()
    {
        return $this->hasMany(Order::class, 'checkin_id');
    }
}
