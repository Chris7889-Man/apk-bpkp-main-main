<?php

namespace App\Http\Controllers\Audiens;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Konsultasi;
use App\Models\Topik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonsultasiController extends Controller
{
    public function store(Request $request, User $konsultan)
    {
        abort_if($konsultan->role !== 'konsultan', 404);

        $request->validate([
            'topik_id'      => 'nullable|string',
            'topik_lainnya' => 'nullable|string|max:255',
        ]);

        $topikId = null;
        $topikLainnya = null;

        if ($request->topik_id === 'lainnya') {
            $topikLainnya = trim((string) $request->topik_lainnya) ?: null;
        } elseif ($request->filled('topik_id') && Topik::whereKey($request->topik_id)->exists()) {
            $topikId = (int) $request->topik_id;
        }

        // Setiap klik KONSUL = konsultasi (chat) baru
        $konsultasi = Konsultasi::create([
            'audiens_id'    => Auth::id(),
            'konsultan_id'  => $konsultan->id,
            'status'        => 'menunggu',
            'topik_id'      => $topikId,
            'topik_lainnya' => $topikLainnya,
        ]);

        ActivityLog::catat(
            Auth::id(),
            'Mulai Konsultasi',
            Auth::user()->name . ' memulai konsultasi dengan ' . $konsultan->name
        );

        return redirect()->route('audiens.konsultasi-saya', $konsultasi->id);
    }
}