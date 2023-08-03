<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseChat extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'user_id',
        'message',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function courses()
    {
        return $this->belongsTo(Course::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

}
