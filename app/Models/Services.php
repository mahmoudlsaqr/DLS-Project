<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Services extends Model
{
    use HasFactory;
    protected $table = 'services';
    public $timestamps = true;
    protected $fillable = [
        'name_ar',
        'name_en',
        'service_code',
        'category_id',
        'service_cost',
        'service_price',
        'tax_percent',
        'tax_value',
        'service_price_with_tax',
        'branches',
        'note',
        'status',
    ];

    protected $casts = [
        'branches' => 'array',
    ];

    public function categories() : BelongsTo
    {
        return $this ->belongsTo(ServicesCategories::class, 'category_id', 'id');
    }

    public function branches() : BelongsToMany
    {
        return $this ->belongsToMany(Branch::class, 'many_branches_services', 'service_id', 'branch_id');
    }
}
