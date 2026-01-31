<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Http\Requests\Settings\DataSettings\DoctorsRequest;

class DoctorsRequestsArchive extends Model
{
    use HasFactory;
    protected $table = 'doctors_requests_archives';
    public $timestamps = true;
    protected $fillable = [
        'request_id',
        'filename',
        'size',
        'created_by',
        'updated_by',
    ];

    public function requests(): BelongsTo
    {
        return $this->belongsTo(DoctorsRequest::class, 'request_id', 'id');
    }
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
