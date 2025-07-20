<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserEnrolledNotification extends Notification
{
    use Queueable;

    public $course;
    public $user;

    public function __construct($course, $user)
    {
        $this->course = $course;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('A New Student Enrolled in Your Course')
            ->view('emails.user-enrolled', [
                'course' => $this->course,
                'user' => $this->user
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Enrollment',
            'message' => $this->user->first_name . ' ' . $this->user->last_name . ' enrolled in your course "' . $this->course->title . '"',
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'enrolled_at' => now(),
        ];
    }
} 