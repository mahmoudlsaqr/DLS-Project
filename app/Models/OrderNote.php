<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderNote extends Model
{
    use HasFactory;
    protected $table = 'order_notes';
    public $timestamps = true;
    protected $fillable = [
        "order_id",
        "notes",
        "file",
        "created_by",
    ];

    public function Orders() : BelongsTo
    {
        return $this->belongsTo(Order::class, "order_id", "id");
    }
    public function User() : BelongsTo
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }
}
