<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CasesNotes extends Model
{
    use HasFactory;
    protected $table = 'case_notes';
    public $timestamps = true;
    protected $fillable = [
        "case_id",
        "notes",
        "file",
        "created_by",
    ];

    public function Cases() : BelongsTo
    {
        return $this->belongsTo(Cases::class, "case_id", "id");
    }
    public function User() : BelongsTo
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }
}
