<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Konsultasi;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAudiens    = User::where('role', 'audiens')->count();
        $totalKonsultan  = User::where('role', 'konsultan')->count();
        $totalKonsultasi = Konsultasi::count();

        $konsultasiTerbaru = Konsultasi::with(['audiens', 'konsultan'])
            ->latest()
            ->take(6)
            ->get();

        $jumlahBerlangsung = Konsultasi::where('status', 'berlangsung')->count();
        $jumlahMenunggu    = Konsultasi::where('status', 'menunggu')->count();
        $jumlahSelesai     = Konsultasi::where('status', 'selesai')->count();

        return view('admin.dashboard', compact(
            'totalAudiens',
            'totalKonsultan',
            'totalKonsultasi',
            'konsultasiTerbaru',
            'jumlahBerlangsung',
            'jumlahMenunggu',
            'jumlahSelesai'
        ));
    }
}