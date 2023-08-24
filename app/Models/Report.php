<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report',
    ];

    protected $hidden = [
        'created_at',
    ];

//    appends
    protected $appends = [
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

//    appends date
    public function getDateAttribute()
    {
        $date = Carbon::parse($this->created_at);
        $formattedDate = $date->isoFormat('D MMMM Y');

        return $formattedDate;
    }


}
