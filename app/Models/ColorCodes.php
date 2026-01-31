<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorCodes extends Model
{
    protected $table = 'color_codes';
    public $timestamps = true;
    protected $fillable = [
        'color_code',
        'status',
    ];
}
