<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
//    append
    // Accessor to calculate the remaining time for each pending transaction
    public function getRemainingTimeAttribute()
    {
        if ($this->status === 'pending') {
            $currentTime = Carbon::now();
            $createdAt = Carbon::parse($this->created_at);
            $dueTime = $createdAt->addDay();

            if ($dueTime > $currentTime) {
                return $dueTime->diffForHumans($currentTime, [
                    'syntax' => Carbon::DIFF_ABSOLUTE,
                    'parts' => 2,
                ]);
            } else {
                // If the transaction has expired, update the status to 'failed'
                $this->update([
                    'status' => 'failed',
                    'updated_at' => Carbon::now(),
                ]);
                return 'Expired';
            }
        }

        return null; // If the status is not 'pending', return null for 'remaining_time'
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
