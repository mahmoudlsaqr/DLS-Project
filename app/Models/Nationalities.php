<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nationalities extends Model
{
    use HasFactory;
    protected $table = 'nationalities';
    public $timestamps = true;
    protected $fillable = [
        'Numeric_3',
        'Alpha_2',
        'Alpha_3',
        'Nat_Ar',
        'Nat_En',
        'Country_Ar',
        'Country_EN',
        'status',
    ];
}
