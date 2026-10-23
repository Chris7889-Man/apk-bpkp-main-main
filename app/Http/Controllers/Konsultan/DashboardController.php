<?php

namespace App\Http\Controllers\Konsultan;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $konsultasis = Auth::user()->konsultasiSebagaiKonsultan()
            ->where('disembunyikan_oleh_konsultan', false)
            ->with('audiens')
            ->latest()
            ->get();

        return view('konsultan.dashboard', compact('konsultasis'));
    }

    public function obrolan(?Konsultasi $konsultasi = null)
    {
        $konsultasis = Auth::user()->konsultasiSebagaiKonsultan()
            ->where('disembunyikan_oleh_konsultan', false)
            ->with(['audiens', 'pesans' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->latest()
            ->get();

        // Pastikan konsultasi yang dibuka memang milik konsultan ini
        if ($konsultasi && $konsultasi->konsultan_id !== Auth::id()) {
            $konsultasi = null;
        }

        // Kalau belum pilih obrolan, otomatis tampilkan yang paling baru
        $selected = $konsultasi ?? $konsultasis->first();

        if ($selected) {
            $selected->load(['pesans.pengirim', 'audiens', 'topik']);
        }

        return view('konsultan.obrolan', compact('konsultasis', 'selected'));
    }
}