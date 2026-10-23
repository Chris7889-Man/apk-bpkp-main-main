<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriKonsultan;
use App\Models\KonsultanProfil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KonsultanProfilController extends Controller
{
    public function index(Request $request)
    {
        $profils = KonsultanProfil::with('user', 'kategori')
            ->when($request->filled('search'), function ($query) use ($request) {
                // Buang awalan "bidang" supaya ketik "Bidang APD 2" tetap ketemu
                $kata = trim(preg_replace('/^bidang\s+/i', '', trim($request->search)));

                $query->where(function ($sub) use ($kata) {
                    $sub->whereHas('user', function ($u) use ($kata) {
                            $u->where('name', 'like', "%{$kata}%")
                              ->orWhere('email', 'like', "%{$kata}%");
                        })
                        ->orWhereHas('kategori', function ($k) use ($kata) {
                            $k->where('nama_kategori', 'like', "%{$kata}%");
                        });
                });
            })
            ->latest()
            ->get();

        return view('admin.konsultan-profil.index', compact('profils'));
    }

    public function create()
    {
        $kategoris = KategoriKonsultan::all();

        return view('admin.konsultan-profil.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:users,email',
            'password'    => 'required|string|min:8',
            'kategori_id' => 'required|exists:kategori_konsultans,id',
            'bio'         => 'nullable|string',
            'foto_profil' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, $validated) {
            // 1. Buat akun user dengan role konsultan
            $user = new User();
            $user->forceFill([
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'password'          => Hash::make($validated['password']),
                'role'              => 'konsultan',
                'email_verified_at' => now(),
            ])->save();

            // 2. Simpan foto (kalau ada)
            $fotoPath = $request->hasFile('foto_profil')
                ? $request->file('foto_profil')->store('foto_konsultan', 'public')
                : null;

            // 3. Buat profil konsultan
            KonsultanProfil::create([
                'user_id'     => $user->id,
                'kategori_id' => $validated['kategori_id'],
                'bio'         => $validated['bio'] ?? null,
                'foto_profil' => $fotoPath,
                'aktif'       => true,
            ]);
        });

        return redirect()->route('admin.konsultan-profil.index')
            ->with('success', 'Konsultan berhasil ditambahkan.');
    }

    public function edit(KonsultanProfil $konsultanProfil)
    {
        $kategoris = KategoriKonsultan::all();
        return view('admin.konsultan-profil.edit', compact('konsultanProfil', 'kategoris'));
    }

    public function update(Request $request, KonsultanProfil $konsultanProfil)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_konsultans,id',
            'bio'         => 'nullable|string',
            'foto_profil' => 'nullable|image|max:2048',
            'aktif'       => 'boolean',
        ]);

        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama biar storage nggak numpuk
            if ($konsultanProfil->foto_profil) {
                Storage::disk('public')->delete($konsultanProfil->foto_profil);
            }
            $validated['foto_profil'] = $request->file('foto_profil')->store('foto_konsultan', 'public');
        }

        $konsultanProfil->update($validated);

        return redirect()->route('admin.konsultan-profil.index')
            ->with('success', 'Profil konsultan berhasil diperbarui.');
    }

    public function destroy(KonsultanProfil $konsultanProfil)
    {
        if ($konsultanProfil->foto_profil) {
            Storage::disk('public')->delete($konsultanProfil->foto_profil);
        }

        $konsultanProfil->delete();

        return redirect()->route('admin.konsultan-profil.index')
            ->with('success', 'Profil konsultan berhasil dihapus.');
    }
}