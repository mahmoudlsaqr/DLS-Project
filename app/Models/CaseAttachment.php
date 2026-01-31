<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseAttachment extends Model
{
    use HasFactory;
    protected $table = 'case_attachments';
    public $timestamps = true;
    protected $fillable = [
        'case_id',
        'type',
        'description',
        'filesize',
        'file',
    ];

    public function Cases() : BelongsTo
    {
        return $this ->belongsTo(Cases::class, "case_id", "id");
    }
}
