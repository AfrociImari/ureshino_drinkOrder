<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMenu extends Model
{
    //
    protected $table = 'course_menu';

     /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'course_type',
        'course_name'
    ];
}
