<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasFactory;

    protected $fillable = [
        'konsultasi_id',
        'pengirim_id',
        'isi_pesan',
        'disembunyikan_oleh_pengirim',
        'balas_ke_id',
        'file_path',
        'file_nama',
        'file_tipe',
        'dibaca_at',
    ];

    protected $casts = [
        'dibaca_at' => 'datetime',
    ];

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class);
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    public function balasKe()
    {
        return $this->belongsTo(Pesan::class, 'balas_ke_id');
    }

    public function isGambar(): bool
    {
        if ($this->file_tipe && str_starts_with($this->file_tipe, 'image/')) {
            return true;
        }

        if ($this->file_nama) {
            $ext = strtolower(pathinfo($this->file_nama, PATHINFO_EXTENSION));
            return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        }

        return false;
    }

    public function isVideo(): bool
    {
        if ($this->file_tipe && str_starts_with($this->file_tipe, 'video/')) {
            return true;
        }

        if ($this->file_nama) {
            $ext = strtolower(pathinfo($this->file_nama, PATHINFO_EXTENSION));
            return in_array($ext, ['mp4', 'webm', 'ogg', 'mov', 'avi']);
        }

        return false;
    }
}