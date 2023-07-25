<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $guarded =[];
    protected $hidden = ['created_at', 'updated_at', 'course_id'];
    protected $appends = ['thumbnail_module'];

    public function getThumbnailModuleAttribute()
    {
        return url('storage/images/thumbnail_module/' . $this->thumbnail);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

}
