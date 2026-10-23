<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatDibersihkan implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public int $konsultasiId;

    public function __construct(int $konsultasiId)
    {
        $this->konsultasiId = $konsultasiId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('konsultasi.' . $this->konsultasiId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ChatDibersihkan';
    }
}