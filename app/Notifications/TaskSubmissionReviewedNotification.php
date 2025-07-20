<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskSubmissionReviewedNotification extends Notification
{
    use Queueable;

    public $submission;
    public $grade;
    public $feedback;

    public function __construct($submission, $grade, $feedback)
    {
        $this->submission = $submission;
        $this->grade = $grade;
        $this->feedback = $feedback;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Task Submission Has Been Reviewed')
            ->view('emails.task-submission-reviewed', [
                'submission' => $this->submission,
                'grade' => $this->grade,
                'feedback' => $this->feedback
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Task Submission Reviewed',
            'message' => 'Your submission for "' . $this->submission->task->title . '" has been reviewed. Grade: ' . $this->grade . '%',
            'task_id' => $this->submission->task_id,
            'grade' => $this->grade,
            'feedback' => $this->feedback
        ];
    }
} 