<?php

namespace App\Models;

use App\Mail\ResetPasswordMail;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use HasFactory, Notifiable, CanResetPassword;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'password',
        'google_id',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'user_courses')
                    ->withPivot('enrollment_date', 'status')
                    ->withTimestamps();
    }

    public function enrollments()
    {
        return $this->hasMany(UserCourse::class);
    }

    public function statistics()
    {
        return $this->hasMany(Statistic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class)->withPivot('watched')->withTimestamps();
    }

    public function completedCourses()
    {
        return $this->belongsToMany(Course::class, 'course_completions')
                    ->withTimestamps();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $resetLink = route('password.reset', $token);

        Mail::to($this->email)->send(new ResetPasswordMail($this, $resetLink));
    }
}
