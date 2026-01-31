<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoicesPayments extends Model
{
    use HasFactory;
    protected $table = 'invoices_payments';
    public $timestamps = true;
    protected $fillable = [
        'invoice_id',
        'payment_type',
        'amount',
        'reference',
        'note',
        'user_id',
    ];

    public function invoicesMain() : BelongsTo
    {
        return $this ->belongsTo(InvoicesMain::class, "invoice_id", "id");
    }
    public function users() : BelongsTo
    {
        return $this ->belongsTo(User::class, "user_id", "id");
    }
}
