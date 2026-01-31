<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DoctorsRequest extends Model
{
    use HasFactory;
    protected $table = 'doctors_requests';
    public $timestamps = true;
    protected $fillable = [
        'main_code',
        'doctor_name',
        'patient_name',
        'file_number',
        'type_case',
        'number_teeth',
        'color_code',
        'duration_delivery',
        'crowns',
        'bridges',
        'rpd',
        'rcd',
        'request_status',
        'notes',
        'confirmed_by',
        'confirmed_at',
    ];

    public function attachments(): HasMany
    {
        return $this->hasMany(DoctorsRequestsArchive::class, 'request_id', 'id');
    }
    public function colorCode(): BelongsTo
    {
        return $this->belongsTo(ColorCodes::class, 'color_code', 'id');
    }
    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by', 'id');
    }
}
