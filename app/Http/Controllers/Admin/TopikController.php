<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriKonsultan;
use App\Models\Topik;
use Illuminate\Http\Request;

class TopikController extends Controller
{
    public function index()
    {
        $topiks = Topik::with('kategori')
            ->withCount('konsultasis')
            ->orderBy('kategori_id')
            ->orderBy('nama_topik')
            ->get();

        return view('admin.topik.index', compact('topiks'));
    }

    public function create()
    {
        $kategoris = KategoriKonsultan::orderBy('nama_kategori')->get();
        return view('admin.topik.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_konsultans,id',
            'nama_topik' => 'required|string|max:255',
        ]);

        Topik::create($validated);

        return redirect()->route('admin.topik.index')->with('success', 'Topik berhasil ditambahkan.');
    }

    public function edit(Topik $topik)
    {
        $kategoris = KategoriKonsultan::orderBy('nama_kategori')->get();
        return view('admin.topik.edit', compact('topik', 'kategoris'));
    }

    public function update(Request $request, Topik $topik)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_konsultans,id',
            'nama_topik' => 'required|string|max:255',
        ]);

        $topik->update($validated);

        return redirect()->route('admin.topik.index')->with('success', 'Topik berhasil diperbarui.');
    }

    public function destroy(Topik $topik)
    {
        $topik->delete();
        return redirect()->route('admin.topik.index')->with('success', 'Topik berhasil dihapus.');
    }
}