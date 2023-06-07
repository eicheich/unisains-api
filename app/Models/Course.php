<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    // fillabel
    protected $guarded = [];

    // relation
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // relation
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    // relation
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_courses');
    }

    // relation
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }


}
