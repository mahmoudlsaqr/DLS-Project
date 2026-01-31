<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Branch extends Model
{
    use HasFactory;
    protected $table = 'branches';
    protected $guarded = [];
    public $timestamps = true;
    protected $fillable = [
        'name_ar',
        'name_en',
        'code',
        'tin',
        'com_reg',
        'region_id',
        'district_id',
        'city_id',
        'address',
        'phone',
        'mobile',
        'status',
        'longitude',
        'latitude',
        'created_by',
        'updated_by',
    ];

    public function Region() : BelongsTo
    {
        return $this ->belongsTo(Region::class, "region_id", "id");
    }

    public function City() : BelongsTo
    {
        return $this ->belongsTo(City::class, "city_id", "id");
    }

    public function District() : BelongsTo
    {
        return $this ->belongsTo(District::class, "district_id", "id");
    }

    public function User(): belongsToMany
    {
        return $this->belongsToMany(User::class, 'user_branches', 'branch_id', 'user_id');
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
