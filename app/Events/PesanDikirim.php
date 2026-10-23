<?php

namespace App\Events;

use App\Models\Pesan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PesanDikirim implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Pesan $pesan;

    public function __construct(Pesan $pesan)
    {
        $this->pesan = $pesan->load('pengirim', 'balasKe.pengirim');
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('konsultasi.' . $this->pesan->konsultasi_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'PesanDikirim';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->pesan->id,
            'isi_pesan' => $this->pesan->isi_pesan,
            'pengirim_id' => $this->pesan->pengirim_id,
            'pengirim_nama' => $this->pesan->pengirim->name,
            'waktu' => $this->pesan->created_at->format('H:i'),
            'file_url' => $this->pesan->file_path ? asset('storage/' . $this->pesan->file_path) : null,
            'file_nama' => $this->pesan->file_nama,
            'file_tipe' => $this->pesan->file_tipe,
            'balas_ke' => $this->pesan->balasKe ? [
                'id' => $this->pesan->balasKe->id,
                'isi_pesan' => $this->pesan->balasKe->isi_pesan,
                'pengirim_nama' => $this->pesan->balasKe->pengirim->name,
            ] : null,
        ];
    }
}