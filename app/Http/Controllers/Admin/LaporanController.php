<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriKonsultan;
use App\Models\Konsultasi;
use App\Models\User;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Statistik total (tidak terpengaruh filter)
        $totalAudiens     = User::where('role', 'audiens')->count();
        $totalKonsultan   = User::where('role', 'konsultan')->count();
        $totalMenunggu    = Konsultasi::where('status', 'menunggu')->count();
        $totalBerlangsung = Konsultasi::where('status', 'berlangsung')->count();
        $totalSelesai     = Konsultasi::where('status', 'selesai')->count();

        // Status di DB bisa pakai istilah lama (aktif/pending), jadi dipetakan ke semua kemungkinan nilainya
        $petaStatus = [
            'berlangsung' => ['berlangsung', 'aktif'],
            'menunggu'    => ['menunggu', 'pending'],
            'selesai'     => ['selesai'],
        ];

        // Data tabel + filter
        $konsultasis = Konsultasi::query()
            ->with(['audiens', 'konsultan.konsultanProfil.kategori'])
            ->when($request->filled('bidang'), function ($q) use ($request) {
                $q->whereHas('konsultan.konsultanProfil', function ($p) use ($request) {
                    $p->where('kategori_id', $request->bidang);
                });
            })
            ->when($request->filled('status'), function ($q) use ($request, $petaStatus) {
                $q->whereIn('status', $petaStatus[$request->status] ?? [$request->status]);
            })
            ->when($request->filled('bulan'), function ($q) use ($request) {
                $q->whereMonth('created_at', $request->bulan);
            })
            ->when($request->filled('tahun'), function ($q) use ($request) {
                $q->whereYear('created_at', $request->tahun);
            })
            ->latest()
            ->get();

        // Isi dropdown Bidang
        $kategoris = KategoriKonsultan::orderBy('nama_kategori')->get();

        return view('admin.laporan.index', compact(
            'totalAudiens',
            'totalKonsultan',
            'totalMenunggu',
            'totalBerlangsung',
            'totalSelesai',
            'konsultasis',
            'kategoris'
        ));
    }
}