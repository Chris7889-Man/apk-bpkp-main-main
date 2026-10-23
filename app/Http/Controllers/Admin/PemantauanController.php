<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Konsultasi;
use Illuminate\Support\Facades\Auth;

class PemantauanController extends Controller
{
    public function index()
    {
        $konsultasis = Konsultasi::with('audiens', 'konsultan')->latest()->get();
        return view('admin.pemantauan.index', compact('konsultasis'));
    }

    public function show(Konsultasi $konsultasi)
    {
        $konsultasi->load('pesans.pengirim', 'pesans.balasKe.pengirim', 'audiens', 'konsultan');

        ActivityLog::catat(Auth::id(), 'Memantau Percakapan', Auth::user()->name . ' memantau percakapan #' . $konsultasi->id);

        return view('admin.pemantauan.show', compact('konsultasi'));
    }
}