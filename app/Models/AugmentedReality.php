<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AugmentedReality extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at','course_id','image_ar'];
    protected $appends = ['ar' ];

    public function getArAttribute()
    {
        return url('storage/images/ar/' . $this->image_ar);
    }
}
