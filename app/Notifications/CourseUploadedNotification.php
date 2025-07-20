<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CourseUploadedNotification extends Notification
{
    use Queueable;

    public $course;

    /**
     * Create a new notification instance.
     */
    public function __construct($course)
    {
        $this->course = $course;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Course Uploaded')
            ->view('emails.course-uploaded', [
                'course' => $this->course
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'title'   => 'New Course Uploaded',
            'message' => 'A new course "' . $this->course->title . '" has been uploaded by ' . 
                        $this->course->user->first_name . ' ' . $this->course->user->last_name,
            'course_id' => $this->course->id,
            'instructor_id' => $this->course->user_id,
            'upload_date' => $this->course->created_at
        ];
    }
}
