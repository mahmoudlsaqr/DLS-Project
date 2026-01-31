<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cases extends Model
{
    use HasFactory;
    protected $table = 'cases';
    public $timestamps = true;
    protected $fillable = [
        'case_number',
        'patient_name',
        'physician',
        'supervising',
        'age',
        'gender',
        'status',
        'note',
        'created_by',
        'updated_by',
    ];

    public function Physician() : BelongsTo
    {
        return $this->belongsTo(User::class, "physician", "id");
    }
    public function Supervising() : BelongsTo
    {
        return $this->belongsTo(User::class, "supervising", "id");
    }
}
