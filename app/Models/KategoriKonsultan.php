<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKonsultan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'keterangan',
    ];

    public function konsultanProfils()
    {
        return $this->hasMany(KonsultanProfil::class, 'kategori_id');
    }
}