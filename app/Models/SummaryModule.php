<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummaryModule extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = ['video'];

    public function getVideoAttribute()
    {
        return asset('storage/video/rangkuman/'.$this->summary_video);
    }


    public function courses()
    {
        return $this->belongsTo(Course::class);
    }
}
