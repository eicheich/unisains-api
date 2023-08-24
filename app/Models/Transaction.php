<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = ['updated_at','created_at'];
    protected $appends = ['expired_date','date','name','is_purchased','is_rated'];
//    append
    // Accessor to calculate the remaining time for each pending transaction
//    public function getRemainingTimeAttribute()
//    {
//        if ($this->status === 'pending') {
//            $currentTime = Carbon::now();
//            $createdAt = Carbon::parse($this->created_at);
//            $dueTime = $createdAt->addDay();
//
//            if ($dueTime > $currentTime) {
//                return $dueTime->diffForHumans($currentTime, [
//                    'syntax' => Carbon::DIFF_ABSOLUTE,
//                    'parts' => 2,
//                ]);
//            } else {
//                // If the transaction has expired, update the status to 'failed'
//                $this->update([
//                    'status' => 'failed',
//                    'updated_at' => Carbon::now(),
//                ]);
//                return 'Expired';
//            }
//        }
//
//        return null; // If the status is not 'pending', return null for 'remaining_time'
//    }
    public function getExpiredDateAttribute()
    {
        $exp =  Carbon::parse($this->created_at)->addDay();
        return $exp->isoFormat('D MMMM Y HH:mm:ss');
    }

    public function getStatusAttribute()
    {
//        cek status, jika status pending ganti belum di bayar
        if ($this->attributes['status'] == 'pending'){
            return 'Belum Dibayar';
        } elseif ($this->attributes['status'] == 'success'){
            return 'Selesai';
        } elseif ($this->attributes['status'] == 'failed'){
            return 'Gagal';
        }
    }
    public function getDateAttribute()
    {
        $date = Carbon::parse($this->created_at);
        $formattedDate = $date->isoFormat('D MMMM Y');

        return $formattedDate;
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getNameAttribute()
    {
        return $this->user->first_name.' '.$this->user->last_name;
    }

    public function getIsPurchasedAttribute()
    {
        if ($this->status == 'Selesai'){
            return true;
        } else {
            return false;
        }
    }

    public function getIsRatedAttribute()
    {
        $rating = Rate::where('user_id', $this->user_id)->where('course_id', $this->course_id)->first();
        if ($rating){
            return true;
        } else {
            return false;
        }
    }



}
