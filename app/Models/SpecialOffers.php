<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialOffers extends Model
{
    use HasFactory;
    protected $table = 'special_offers';
    public $timestamps = true;
    protected $fillable = [
        'offer_name',
        'doctor_id',
        'start_date',
        'end_date',
        'discount',
    ];

    public function doctors() : BelongsTo
    {
        return $this ->belongsTo(Doctors::class, 'doctor_id', 'id');
    }
}
