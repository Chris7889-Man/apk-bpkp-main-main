<?php

namespace App\Http\Controllers\Audiens;

use App\Http\Controllers\Controller;
use App\Http\Controllers\KonsultasiController;
use App\Models\KategoriKonsultan;
use App\Models\Konsultasi;
use App\Models\KonsultanProfil;
use App\Models\Topik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('audiens.dashboard');
    }

    public function pilihKonsultan(Request $request)
    {
        $konsultans = KonsultanProfil::with('user', 'kategori')
            ->where('aktif', true)
            ->when($request->filled('kategori_id'), function ($query) use ($request) {
                $query->where('kategori_id', $request->kategori_id);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->get();

        $kategoris = KategoriKonsultan::orderBy('nama_kategori')->get();
        $topiks = Topik::orderBy('nama_topik')->get();

        return view('audiens.pilih-konsultan', compact('konsultans', 'kategoris', 'topiks'));
    }

    public function konsultasiSaya(?Konsultasi $konsultasi = null)
    {
        $user = Auth::user();

        $konsultasis = $user->konsultasiSebagaiAudiens()
            ->where('disembunyikan_oleh_audiens', false)
            ->with(['konsultan', 'pesans'])
            ->latest()
            ->get();

        $konsultasiAktif = null;

        if ($konsultasi) {
            abort_unless($konsultasi->audiens_id === $user->id, 403);

            $konsultasi->load(['pesans' => function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('disembunyikan_oleh_pengirim', false)
                      ->orWhere('pengirim_id', '!=', $user->id);
                })->orderBy('created_at');
            }, 'pesans.pengirim', 'pesans.balasKe.pengirim', 'audiens', 'konsultan', 'topik']);

            app(KonsultasiController::class)->tandaiDibaca($konsultasi, $user->id);

            $konsultasiAktif = $konsultasi;
        }

        return view('audiens.konsultasi-saya', compact('konsultasis', 'konsultasiAktif'));
    }
}