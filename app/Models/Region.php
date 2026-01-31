<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    protected $table = 'regions';
    public $timestamps = true;
    protected $fillable = [
        'code',
        'name_ar',
        'name_en',
        'population',
        'status',
    ];
}
