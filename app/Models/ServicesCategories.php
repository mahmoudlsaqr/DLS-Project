<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicesCategories extends Model
{
    use HasFactory;
    protected $table = 'services_categories';
    public $timestamps = true;
    protected $fillable = [
        'name_ar',
        'name_en',
        'category_code',
        'status',
    ];
}
