<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKonsultan(): bool
    {
        return $this->role === 'konsultan';
    }

    public function isAudiens(): bool
    {
        return $this->role === 'audiens';
    }

    public function konsultanProfil()
    {
        return $this->hasOne(KonsultanProfil::class);
    }

    public function konsultasiSebagaiAudiens()
    {
        return $this->hasMany(Konsultasi::class, 'audiens_id');
    }

    public function konsultasiSebagaiKonsultan()
    {
        return $this->hasMany(Konsultasi::class, 'konsultan_id');
    }
}