<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PesanDibaca implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $konsultasiId;
    public $pembacaId;

    public function __construct($konsultasiId, $pembacaId)
    {
        $this->konsultasiId = $konsultasiId;
        $this->pembacaId = $pembacaId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('konsultasi.' . $this->konsultasiId);
    }

    public function broadcastAs()
    {
        return 'PesanDibaca';
    }

    public function broadcastWith()
    {
        return [
            'konsultasi_id' => $this->konsultasiId,
            'pembaca_id' => $this->pembacaId,
        ];
    }
}