<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewCourseContentNotification extends Notification
{
    use Queueable;

    public $content;
    public $contentType; // 'task', 'lesson', or 'quiz'
    public $course;

    public function __construct($content, $contentType, $course)
    {
        $this->content = $content;
        $this->contentType = $contentType;
        $this->course = $course;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("New " . ucfirst($this->contentType) . " Added to " . $this->course->title)
            ->view('emails.new-course-content', [
                'content' => $this->content,
                'contentType' => $this->contentType,
                'course' => $this->course
            ]);
    }

    public function toArray($notifiable)
    {
        $contentType = ucfirst($this->contentType);
        return [
            'title' => "New {$contentType} Added",
            'message' => "A new {$this->contentType} '{$this->content->title}' has been added to {$this->course->title}",
            'course_id' => $this->course->id,
            'content_id' => $this->content->id,
            'content_type' => $this->contentType
        ];
    }
} 