<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, HasRoles;
    protected $table = 'users';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'username',
        'email',
        'country_code',
        'mobile',
        'role',
        'supervising',
        'status',
        'avatar',
        'last_active',
        'ip_address',
        'receiving_orders',
        'skipping',
        'password',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function branches() : BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'many_users_branches', 'user_id', 'branch_id');
    }
}
