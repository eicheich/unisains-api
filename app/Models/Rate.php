<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at', 'user_id', 'course_id'];

//    appends
    protected $appends = ['date', 'rated_by_me'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDateAttribute()
    {
        $date = Carbon::parse($this->created_at);
        $formattedDate = $date->isoFormat('D MMMM Y');

        return $formattedDate;
    }

    public function getRatedByMeAttribute()
    {
        $user = auth()->user();
        if ($this->user_id == $user->id) {
            return true;
        } else {
            return false;
        }
    }
}
