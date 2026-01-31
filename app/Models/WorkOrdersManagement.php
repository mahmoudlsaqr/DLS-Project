<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrdersManagement extends Model
{
    protected $table = 'work_orders_management';
    public $timestamps = true;
    protected $fillable = [
        'work_order_number',
        'work_order_date',
        'branch_id',
        'doctor_id',
        'client_id',
        'color_id',
        'service_id',
        'service_count',
        'service_price',
        'services_price',
        'service_discount',
        'service_discount_value',
        'services_discount_value',
        'service_total_net',
        'service_tax',
        'service_tax_value',
        'service_total',
        'statement',
        'patient_name',
        'file_number',
        'created_by',
        'updated_by',
        'phase_id',
        'status',
        'note',
    ];

    public function branches() : BelongsTo
    {
        return $this ->belongsTo(Branch::class, "branch_id", "id");
    }
    public function doctors() : BelongsTo
    {
        return $this ->belongsTo(Doctors::class, "doctor_id", "id");
    }
    public function clients() : BelongsTo
    {
        return $this ->belongsTo(Clients::class, "client_id", "id");
    }
    public function colors() : BelongsTo
    {
        return $this ->belongsTo(ColorCodes::class, "color_id", "id");
    }
    public function services() : BelongsTo
    {
        return $this ->belongsTo(Services::class, "service_id", "id");
    }
    public function phases() : BelongsTo
    {
        return $this ->BelongsTo(WorkingPhases::class, 'phase_id', 'id');
    }
    public function createdBy() : BelongsTo
    {
        return $this ->BelongsTo(User::class, 'created_by', 'id');
    }
    public function updatedBy() : BelongsTo
    {
        return $this ->BelongsTo(User::class, 'updated_by', 'id');
    }

}
