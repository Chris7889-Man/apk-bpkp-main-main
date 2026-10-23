<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PesanDihapus implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public int $konsultasiId;
    public int $pesanId;

    public function __construct(int $konsultasiId, int $pesanId)
    {
        $this->konsultasiId = $konsultasiId;
        $this->pesanId = $pesanId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('konsultasi.' . $this->konsultasiId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'PesanDihapus';
    }

    public function broadcastWith(): array
    {
        return [
            'pesan_id' => $this->pesanId,
        ];
    }
}