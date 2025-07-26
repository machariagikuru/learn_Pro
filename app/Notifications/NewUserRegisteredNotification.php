<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserRegisteredNotification extends Notification
{
    use Queueable;

    public $newUser;

    public function __construct($newUser)
    {
        $this->newUser = $newUser;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New User Registered')
            ->view('emails.new-user-registered', [
                'newUser' => $this->newUser
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title'   => 'New User Registered',
            'message' => $this->newUser->first_name . ' ' . $this->newUser->last_name . ' has registered with email ' . $this->newUser->email,
            'user_id' => $this->newUser->id,
            'email'   => $this->newUser->email,
            'registration_date' => $this->newUser->created_at
        ];
    }
}
