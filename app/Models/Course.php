<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at','is_paid','course_code','category_id','image_course','certificate_course','is_public'];
    protected $appends = ['thumbnail','is_purchased', 'is_wishlist','avgRate','in_cart','totalRate'];
    public function getPriceAttribute($value)
    {
        if ($this->is_paid == 0) {
            return 'free';
        }
        return $value;
    }
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

    public function getIsPurchasedAttribute()
    {
        $user = auth()->user();
        if ($user) {
            $myCourse = MyCourse::where('user_id', $user->id)->where('course_id', $this->id)->first();
            if ($myCourse) {
                return true;
            }
        }
        return false;
    }

//    public function getIsDoneAttribute()
//    {
//        $user = auth()->user();
//        if ($user) {
//            $myCourse = $user->myCourses()->where('course_id', $this->id)->first();
//            if ($myCourse) {
//                if ($myCourse->status == 'done') {
//                    return true;
//                }
//            }
//        }
//        return false;
//    }
    public function getCertificateAttribute()
    {
        return url('storage/images/certificate/' . $this->certificate_course);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function modules()
    {
        return $this->hasMany(Module::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_courses');
    }
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
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function myCourses()
    {
        return $this->hasMany(MyCourse::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

//    tampilkan yg is_public nya 1
    public function scopeIsPublic($query)
    {
        return $query->where('is_public', 1);
    }

    public function getIsWishlistAttribute()
    {
        $user = auth()->user();
        if ($user) {
            $wishlist = Wishlist::where('user_id', $user->id)->where('course_id', $this->id)->first();
            if ($wishlist) {
                return true;
            }
        }
        return false;
    }

    public function summary_modules()
    {
        return $this->hasMany(SummaryModule::class);
    }

    public function getAvgRateAttribute()
    {
        return $this->avgRate($this->id);
    }
    public static function avgRate($courseId)
    {
        $rates = Rate::where('course_id', $courseId)->get();
        $totalRate = 0;

        foreach ($rates as $rate) {
            $totalRate += $rate->rate;
        }

        if (count($rates) > 0) {
            // Memperoleh rata-rata dan membulatkannya ke 2 desimal.
            $averageRate = $totalRate / count($rates);
            $roundedRate = round($averageRate, 2);
            return $roundedRate;
        } else {
            return 0;
        }
    }


    public function getInCartAttribute()
    {
        $user = auth()->user();
        if ($user) {
            $cart = Cart::where('user_id', $user->id)->where('course_id', $this->id)->first();
            if ($cart) {
                return true;
            }
        }
        return false;
    }
    public function getTotalRateAttribute()
    {
        $rate = Rate::where('course_id', $this->id)->get();
        return count($rate);
    }

    public function scopeApproved($query)
    {
        return $query->whereHas('rates', function ($subquery) {
            $subquery->where('status', 'approved');
        });
    }



}
