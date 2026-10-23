<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topik extends Model
{
    protected $fillable = ['kategori_id', 'nama_topik'];

    public function kategori()
    {
        return $this->belongsTo(KategoriKonsultan::class, 'kategori_id');
    }

    public function konsultasis()
    {
        return $this->hasMany(Konsultasi::class);
    }
}