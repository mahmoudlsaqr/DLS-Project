<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $table = 'cities';
    public $timestamps = true;
    protected $fillable = [
        'region_id',
        'name_ar',
        'name_en',
        'status',
    ];

    public function Region() : BelongsTo
    {
        return $this ->belongsTo(Region::class, "region_id", "id");
    }
}
