<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InstructorAccessRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // User information

    /**
     * Create a new message instance.
     *
     * @param mixed $user
     */
    public function __construct($user = null)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Instructor Access Request')
                    ->view('emails.instructor_access_request');
    }
}
