<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorsServicesDetails extends Model
{
    protected $table = 'doctors_services_details';
    public $timestamps = true;
    protected $fillable = [
        'doctor_id',
        'service_id',
        'category_id',
        'service_code',
        'service_price',
        'service_discount',
        'price',
        'status',
    ];

    public function doctors() : BelongsTo
    {
        return $this ->belongsTo(Doctors::class, "doctor_id", "id");
    }
    public function services() : BelongsTo
    {
        return $this ->belongsTo(Services::class, "service_id", "id");
    }
    public function servicesCategories() : BelongsTo
    {
        return $this ->belongsTo(ServicesCategories::class, "category_id", "id");
    }
}
