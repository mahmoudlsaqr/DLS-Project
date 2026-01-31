<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappNotificationLog extends Model
{
    use HasFactory;
    protected $table = 'whatsapp_notifications_logs';
    public $timestamps = true;
    protected $fillable = [
        'mobile',
        'message',
        'success',
        'response',
        'type',
        'user_id',
        'work_order_id',
        'phase_id',
    ];

    public function users() : BelongsTo
    {
        return $this ->BelongsTo(User::class, 'user_id', 'id');
    }

    public function workOrders() : BelongsTo
    {
        return $this ->BelongsTo(WorkOrdersManagement::class, 'work_order_id', 'id');
    }

    public function phases() : BelongsTo
    {
        return $this ->BelongsTo(WorkingPhases::class, 'phase_id', 'id');
    }
}
