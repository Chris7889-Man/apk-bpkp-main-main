<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonsultanProfil extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori_id',
        'bio',
        'foto_profil',
        'aktif',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriKonsultan::class, 'kategori_id');
    }
}