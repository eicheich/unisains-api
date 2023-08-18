<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at', 'user_id', 'course_id'];

//    appends
    protected $appends = ['avgRate'];

    public function courses()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
            return $totalRate / count($rates);
        } else {
            return 0;
        }
    }



}
