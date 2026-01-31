<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctors extends Model
{
    use HasFactory;
    protected $table = 'doctors';
    public $timestamps = true;
    protected $fillable = [
        'name_ar',
        'name_en',
        'client_id',
        'nationality_id',
        'email',
        'mobile',
        'branch_id',
        'note',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'branches' => 'array',
    ];

    public function clients() : BelongsTo
    {
        return $this ->belongsTo(Clients::class, 'client_id', 'id');
    }
    public function nationalities() : BelongsTo
    {
        return $this ->belongsTo(Nationalities::class, 'nationality_id', 'id');
    }
    public function branches() : BelongsTo
    {
        return $this ->BelongsTo(Branch::class, 'branch_id', 'id');
    }
    public function createdBy() : BelongsTo
    {
        return $this ->BelongsTo(User::class, 'created_by', 'id');
    }
    public function updatedBy() : BelongsTo
    {
        return $this ->BelongsTo(User::class, 'updated_by', 'id');
    }

}
