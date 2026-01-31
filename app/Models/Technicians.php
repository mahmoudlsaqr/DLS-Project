<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Technicians extends Model
{
    use HasFactory;
    protected $table = 'technicians';
    public $timestamps = true;
    protected $fillable = [
        'name_ar',
        'name_en',
        'section_id',
        'nationality_id',
        'email',
        'country_code',
        'mobile',
        'monthly_target',
        'branches',
        'phases',
        'note',
        'status',
        'user_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'branches' => 'array',
        'phases' => 'array',
    ];

    public function sections() : BelongsTo
    {
        return $this ->belongsTo(Sections::class, 'section_id', 'id');
    }

    public function nationalities() : BelongsTo
    {
        return $this ->belongsTo(Nationalities::class, 'nationality_id', 'id');
    }

    public function user() : BelongsTo
    {
        return $this ->BelongsTo(User::class, 'user_id', 'id');
    }

    public function createdBy() : BelongsTo
    {
        return $this ->BelongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy() : BelongsTo
    {
        return $this ->BelongsTo(User::class, 'updated_by', 'id');
    }

    public function branches() : BelongsToMany
    {
        return $this ->belongsToMany(Branch::class, 'many_branches_technicians', 'technician_id', 'branch_id');
    }

    public function phases() : BelongsToMany
    {
        return $this ->belongsToMany(WorkingPhases::class, 'many_phases_technicians', 'technician_id', 'phase_id');
    }
}
