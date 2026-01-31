<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class WorkingPhasesReminder extends Notification
{
    use Queueable;

    protected $details;

    public function __construct(array $details)
    {
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('تنبيه تأخير مرحلة امر العمل')
            ->line($this->details['title'] . $this->details['workOrderNumber'])
            ->action('عرض امر العمل', url('/work-orders/' . $this->details['id']))
            ->line('يرجى اتخاذ الإجراءات اللازمة.');
    }

    public function toArray($notifiable)
    {
        return [
            'id' => $this->details['id'],
            'title' => $this->details['title'],
            'body' => $this->details['body'],
            'workOrderNumber' => $this->details['workOrderNumber'],
            'user_id' => $notifiable->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
