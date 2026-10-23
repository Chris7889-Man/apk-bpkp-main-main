<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            // Akun admin tidak ditampilkan, hanya audiens dan konsultan
            ->whereIn('role', ['audiens', 'konsultan'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = trim($request->q);
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'foto_profil' => 'nullable|image|max:2048',
            'is_active'   => 'required|boolean',
        ]);

        $validated['is_active'] = (bool) $validated['is_active'];

        // Admin tidak boleh menonaktifkan akunnya sendiri
        if ($user->id === Auth::id()) {
            $validated['is_active'] = true;
        }

        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama biar storage nggak numpuk
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $validated['foto_profil'] = $request->file('foto_profil')->store('foto_pengguna', 'public');
        } else {
            unset($validated['foto_profil']);
        }

        $user->forceFill($validated)->save();

        ActivityLog::catat(Auth::id(), 'Kelola Akun', Auth::user()->name . ' memperbarui akun ' . $user->name);

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_if($user->id === Auth::id(), 403, 'Tidak bisa menghapus akun sendiri.');

        $nama = $user->name;

        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $user->delete();

        ActivityLog::catat(Auth::id(), 'Kelola Akun', Auth::user()->name . ' menghapus akun ' . $nama);

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dihapus.');
    }
}