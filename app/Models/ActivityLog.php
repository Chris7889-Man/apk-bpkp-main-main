<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'aktivitas',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function catat(?int $userId, string $aktivitas, ?string $keterangan = null): void
    {
        static::create([
            'user_id' => $userId,
            'aktivitas' => $aktivitas,
            'keterangan' => $keterangan,
        ]);
    }
}