<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = [
        'patient_name',
        'designer',
        'age',
        'gender',
        'required_in_case',
        'treatment_plan',
        'patient_expectations',
        'status',
        'accepted_by',
        'accepted_at',
        'rejected_by',
        'rejected_at',
        'created_by',
        'updated_by',
    ];

    public function Designer() : BelongsTo
    {
        return $this->belongsTo(User::class, "designer", "id");
    }
    public function AcceptedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, "accepted_by", "id");
    }
    public function RejectedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, "rejected_by", "id");
    }
    public function CreatedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }
    public function UpdatedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, "updated_by", "id");
    }
}
