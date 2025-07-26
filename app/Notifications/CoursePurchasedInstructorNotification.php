<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CoursePurchasedInstructorNotification extends Notification
{
    use Queueable;

    public $course;
    public $student;

    public function __construct($course, $student)
    {
        $this->course = $course;
        $this->student = $student;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🎉 New Student Enrolled in Your Course!')
            ->view('emails.user-enrolled', [
                'course' => $this->course,
                'user' => $this->student // Pass the student as 'user' to match the blade template variable
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Course Purchase',
            'message' => $this->student->first_name . ' ' . $this->student->last_name . ' purchased your course "' . $this->course->title . '"',
            'course_id' => $this->course->id,
            'student_id' => $this->student->id,
            'purchase_amount' => $this->course->price,
            'instructor_earnings' => $this->course->price * 0.8,
            'purchase_date' => now()
        ];
    }
} 