<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptVoucher extends Model
{
    use HasFactory;
    protected $table = 'receipt_vouchers';
    public $timestamps = true;
    protected $fillable = [
        "case_id",
        "clinic_name",
        "amount",
        "description",
        "file",
        "status",
        "created_by",
        "updated_by",
    ];

    public function Cases() : BelongsTo
    {
        return $this->belongsTo(Cases::class, "case_id", "id");
    }
}
