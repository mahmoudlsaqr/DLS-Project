<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InvoicesMain extends Model
{
    use HasFactory;
    protected $table = 'invoices_main';
    public $timestamps = true;
    protected $fillable = [
        'qr_code',
        'invoice_date',
        'invoice_time',
        'branch_id',
        'client_id',
        'doctor_id',
        'total_count',
        'total_price',
        'total_discount_value',
        'additional_discount',
        'total_total_net',
        'total_tax_value',
        'total_total',
        'total_paid',
        'total_residual',
        'start_date_issue',
        'end_date_issue',
        'created_by',
    ];

    public function branches() : BelongsTo
    {
        return $this ->belongsTo(Branch::class, "branch_id", "id");
    }
    public function clients() : BelongsTo
    {
        return $this ->belongsTo(Clients::class, "client_id", "id");
    }
    public function doctors() : BelongsTo
    {
        return $this ->belongsTo(Doctors::class, "doctor_id", "id");
    }
    public function createdBy() : BelongsTo
    {
        return $this ->belongsTo(User::class, "created_by", "id");
    }
    public function workOrders() : BelongsToMany
    {
        return $this ->belongsToMany(WorkOrdersManagement::class, 'many_work_orders_invoices', 'invoice_id', 'work_order_id');
    }
}
