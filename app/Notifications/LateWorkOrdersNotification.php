<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class LateWorkOrdersNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via()
    {
        return ['database'];
    }

    public function toDatabase()
    {
        return new DatabaseMessage([
            'work_order_id' => $this->order['work_order_id'],
            'user_id' => $this->order['user_id'],
            'phase_id' => $this->order['phase_id'],
            'title' => $this->order['title'],
            'created_at' => getTimeDate($this->order['created_at']),
        ]);
    }
}
