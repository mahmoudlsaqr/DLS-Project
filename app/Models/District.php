<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{
    protected $table = 'districts';
    public $timestamps = true;
    protected $fillable = [
        'region_id',
        'city_id',
        'name_ar',
        'name_en',
        'status',
    ];

    public function Region() : BelongsTo
    {
        return $this ->belongsTo(Region::class, "region_id", "id");
    }
    public function City() : BelongsTo
    {
        return $this ->belongsTo(City::class, "city_id", "id");
    }
}
