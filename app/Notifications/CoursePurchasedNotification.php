<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CoursePurchasedNotification extends Notification
{
    use Queueable;

    public $course;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Course  $course
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct($course, $user)
    {
        $this->course = $course;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🎉 Welcome to ' . $this->course->title . ' - Your Course Access is Ready!')
            ->markdown('emails.course-purchased', [
                'course' => $this->course,
                'user' => $this->user
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'course_id' => $this->course->id,
            'title' => $this->course->title,
            'message' => 'You have successfully purchased the course: ' . $this->course->title,
        ];
    }
} 