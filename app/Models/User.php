<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
        'profile',
    ];

    protected $casts = [
        'profile' => 'array', // Jika profil disimpan dalam JSON
    ];

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
}
