<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CourseStatusUpdated extends Notification
{
    use Queueable;

    public $course;

    public function __construct($course)
    {
        $this->course = $course;
    }

    // تحديد قنوات الإشعار
    public function via($notifiable)
    {
        return ['mail', 'database']; // يمكن إضافة قنوات أخرى مثل broadcast إذا رغبت
    }

    // تنبيه البريد الإلكتروني
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Course Status Updated')
            ->view('emails.course-status-updated', [
                'course' => $this->course
            ]);
    }

    // تنبيه قاعدة البيانات (للتخزين في جدول notifications)
    public function toArray($notifiable)
    {
        return [
            'course_id' => $this->course->id,
            'title'     => $this->course->title,
            'status'    => $this->course->status,
        ];
    }
}
