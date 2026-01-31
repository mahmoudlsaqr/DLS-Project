<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrdersLog extends Model
{
    use HasFactory;
    protected $table = 'work_orders_logs';
    public $timestamps = true;
    protected $fillable = [
        'work_order_id',
        'user_id',
        'log_date',
        'log_time',
        'phase_id',
    ];
    public function workOrders() : BelongsTo
    {
        return $this ->belongsTo(WorkOrdersManagement::class, "work_order_id", "id");
    }
    public function users() : BelongsTo
    {
        return $this ->belongsTo(User::class, "user_id", "id");
    }
    public function phases() : BelongsTo
    {
        return $this ->belongsTo(WorkingPhases::class, "phase_id", "id");
    }
}
