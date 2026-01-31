<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingPhases extends Model
{
    use HasFactory;
    protected $table = 'working_phases';
    public $timestamps = true;
    protected $fillable = [
        'name_ar',
        'name_en',
        'completion_percent',
        'number_of_times_of_delay',
        'status',
    ];
}
