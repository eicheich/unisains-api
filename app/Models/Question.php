<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'quiz_id',
        'question',
        'answer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'answer'
    ];

    public function quizzes()
    {
        return $this->belongsTo(Quiz::class);
    }
}
