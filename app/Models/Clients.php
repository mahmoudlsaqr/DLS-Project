<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Clients extends Model
{
    use HasFactory;
    protected $table = 'clients';
    protected $guarded = [];
    public $timestamps = true;
    protected $fillable = [
        'name_ar',
        'name_en',
        'tax_number',
        'commercial_register',
        'region_id',
        'district_id',
        'city_id',
        'branch_id',
        'discount',
        'address',
        'phone',
        'mobile',
        'longitude',
        'latitude',
        'commissioner',
        'commissioner_number',
        'status',
        'created_by',
        'updated_by',
    ];

    public function region() : BelongsTo
    {
        return $this ->belongsTo(Region::class, "region_id", "id");
    }
    public function city() : BelongsTo
    {
        return $this ->belongsTo(City::class, "city_id", "id");
    }
    public function district() : BelongsTo
    {
        return $this ->belongsTo(District::class, "district_id", "id");
    }
    public function branches() : BelongsTo
    {
        return $this ->belongsTo(Branch::class, "branch_id", "id");
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
