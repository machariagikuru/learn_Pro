<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskSubmittedNotification extends Notification
{
    use Queueable;

    public $submission;
    public $user;

    public function __construct($submission)
    {
        $this->submission = $submission;
        $this->user = $submission->user;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Task Submission Received')
            ->view('emails.task-submitted', [
                'submission' => $this->submission,
                'user' => $this->user
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Task Submission',
            'message' => $this->user->name . ' has submitted a task: ' . $this->submission->task->title,
            'task_id' => $this->submission->task_id,
            'submission_id' => $this->submission->id,
            'user_id' => $this->user->id
        ];
    }
} 