<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;

    // fillabel
    protected $guarded = [];
//    hidden
    protected $hidden = ['created_at', 'updated_at', 'deleted_at','is_paid','course_code','category_id','image_course','certificate_course'];
//    append image and  certificate
    protected $appends = ['thumbnail' ];

    public function getPriceAttribute($value)
    {
        if ($this->is_paid == 0) {
            return 'free';
        }

        return $value;
    }

//    jika discount ada maka tambahkan di belakangnya %
    public function getDiscountAttribute($value)
    {
        if ($value) {
            return $value . '%';
        }
    }




    public function getThumbnailAttribute()
    {
        return url('storage/images/thumbnail_course/' . $this->image_course);
    }

    public function getCertificateAttribute()
    {
        return url('storage/images/certificate/' . $this->certificate_course);
    }

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

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function ars()
    {
        return $this->hasMany(AugmentedReality::class);
    }


}
