<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'audiens_id',
        'konsultan_id',
        'status',
        'topik_id',
        'topik_lainnya',
        'disembunyikan_oleh_audiens',
        'disembunyikan_oleh_konsultan',
        'dibersihkan_audiens_pada',
        'dibersihkan_konsultan_pada',
    ];

    protected $casts = [
        'dibersihkan_audiens_pada' => 'datetime',
        'dibersihkan_konsultan_pada' => 'datetime',
    ];

    public function audiens()
    {
        return $this->belongsTo(User::class, 'audiens_id');
    }

    public function konsultan()
    {
        return $this->belongsTo(User::class, 'konsultan_id');
    }

    public function topik()
    {
        return $this->belongsTo(Topik::class);
    }

    public function pesans()
    {
        return $this->hasMany(Pesan::class)->orderBy('created_at');
    }

    public function pesanTerakhir()
    {
        return $this->hasOne(Pesan::class)->latestOfMany();
    }
}