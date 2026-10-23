<?php

namespace App\Http\Controllers;

use App\Events\PesanDibaca;
use App\Models\Konsultasi;
use App\Models\Pesan;
use Illuminate\Support\Facades\Auth;

class KonsultasiController extends Controller
{
    public function show(Konsultasi $konsultasi)
    {
        $user = Auth::user();

        abort_unless(
            $konsultasi->audiens_id === $user->id || $konsultasi->konsultan_id === $user->id,
            403
        );

        $batasWaktu = $user->id === $konsultasi->audiens_id
            ? $konsultasi->dibersihkan_audiens_pada
            : $konsultasi->dibersihkan_konsultan_pada;

        $konsultasi->load(['pesans' => function ($query) use ($user, $batasWaktu) {
            $query->where(function ($q) use ($user) {
                $q->where('disembunyikan_oleh_pengirim', false)
                  ->orWhere('pengirim_id', '!=', $user->id);
            });

            if ($batasWaktu) {
                $query->where('created_at', '>', $batasWaktu);
            }

            $query->orderBy('created_at');
        }, 'pesans.pengirim', 'pesans.balasKe.pengirim', 'audiens', 'konsultan']);

        $this->tandaiDibaca($konsultasi, $user->id);

        return view('konsultasi.show', compact('konsultasi'));
    }

    public function destroy(Konsultasi $konsultasi)
    {
        $user = Auth::user();

        abort_unless(
            $konsultasi->audiens_id === $user->id || $konsultasi->konsultan_id === $user->id,
            403
        );

        if ($konsultasi->audiens_id === $user->id) {
            $konsultasi->update(['disembunyikan_oleh_audiens' => true]);
        } else {
            $konsultasi->update(['disembunyikan_oleh_konsultan' => true]);
        }

        if ($konsultasi->disembunyikan_oleh_audiens && $konsultasi->disembunyikan_oleh_konsultan) {
            $konsultasi->delete();
        }

        return back()->with('success', 'Konsultasi berhasil dihapus.');
    }

    public function selesaikan(Konsultasi $konsultasi)
    {
        $user = Auth::user();

        abort_unless(
            $konsultasi->audiens_id === $user->id || $konsultasi->konsultan_id === $user->id,
            403
        );

        $konsultasi->update(['status' => 'selesai']);

        return back()->with('success', 'Konsultasi ditandai selesai.');
    }

    public function tandaiDibaca(Konsultasi $konsultasi, $userId)
    {
        $belumDibaca = $konsultasi->pesans
            ->where('pengirim_id', '!=', $userId)
            ->whereNull('dibaca_at');

        if ($belumDibaca->isNotEmpty()) {
            Pesan::whereIn('id', $belumDibaca->pluck('id'))->update(['dibaca_at' => now()]);
            broadcast(new PesanDibaca($konsultasi->id, $userId))->toOthers();
        }
    }
}