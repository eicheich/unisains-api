<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyCourse extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
//    protected $appends = ['course', ];

    public function getCourseAttribute()
    {
        return Course::find($this->course_id);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }




}
