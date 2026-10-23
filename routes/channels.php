<?php

use App\Models\Konsultasi;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('konsultasi.{konsultasiId}', function ($user, $konsultasiId) {
    $konsultasi = Konsultasi::find($konsultasiId);

    if (!$konsultasi) {
        return false;
    }

    return $user->id === $konsultasi->audiens_id || $user->id === $konsultasi->konsultan_id;
});

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});