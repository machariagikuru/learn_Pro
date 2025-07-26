<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InstructorAccessRequestNotification extends Notification
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $user
     * @return void
     */
    public function __construct($user)
    {
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
        // You can add channels like 'mail', 'database', etc.
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
            ->subject('Instructor Access Request')
            ->view('emails.instructor_access_request', [
                'user' => $this->user
            ]);
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title'   => 'Instructor Access Request',
            'message' => $this->user->first_name . ' ' . ($this->user->last_name ?? '') . ' has requested instructor access with email ' . $this->user->email,
            'user_id' => $this->user->id,
            'email'   => $this->user->email,
            'request_date' => now()
        ];
    }
}
