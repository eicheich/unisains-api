<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
        'role',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'avatar',
        'email_verified_at',

    ];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        return $this->avatar ? asset('storage/images/avatar/' . $this->avatar) : asset('storage/images/avatar/default.png');
    }



    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'user_courses');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function myCourses()
    {
        return $this->hasMany(MyCourse::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);

    }
}
