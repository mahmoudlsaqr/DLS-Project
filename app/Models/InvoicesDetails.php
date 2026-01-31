<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoicesDetails extends Model
{
    use HasFactory;
    protected $table = 'invoices_details';
    public $timestamps = true;
    protected $fillable = [
        'invoice_id',
        'file_number',
        'patient_name',
        'work_order_id',
        'service_id',
        'color_id',
        'service_count',
        'service_price',
        'service_discount',
        'service_discount_value',
        'service_total_net',
        'service_tax',
        'service_tax_value',
        'service_total',
    ];

    public function invoicesMain() : BelongsTo
    {
        return $this ->belongsTo(InvoicesMain::class, "invoice_id", "id");
    }
    public function services() : BelongsTo
    {
        return $this ->belongsTo(Services::class, "service_id", "id");
    }
    public function workOrder() : BelongsTo
    {
        return $this ->BelongsTo(WorkOrdersManagement::class, 'work_order_id', 'id');
    }
    public function colors() : BelongsTo
    {
        return $this ->belongsTo(ColorCodes::class, "color_id", "id");
    }
}
