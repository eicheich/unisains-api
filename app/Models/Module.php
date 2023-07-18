<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $guarded =[];
    protected $hidden = ['created_at', 'updated_at', 'course_id'];
    protected $appends = ['image_module'];

    public function getImageModuleAttribute()
    {
        return url('storage/images/module/' . $this->image);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

}
