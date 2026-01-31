<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAttachment extends Model
{
    use HasFactory;
    protected $table = 'order_attachments';
    public $timestamps = true;
    protected $fillable = [
        'order_id',
        'type',
        'description',
        'filesize',
        'file',
    ];

    public function Orders() : BelongsTo
    {
        return $this ->belongsTo(Order::class, "order_id", "id");
    }
}
