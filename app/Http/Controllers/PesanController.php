<?php

namespace App\Http\Controllers;

use App\Events\AktivitasKonsultasi;
use App\Events\PesanDikirim;
use App\Models\ActivityLog;
use App\Models\Konsultasi;
use App\Models\Pesan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PesanController extends Controller
{
    public function store(Request $request, Konsultasi $konsultasi)
    {
        $user = Auth::user();

        abort_unless(
            $konsultasi->audiens_id === $user->id || $konsultasi->konsultan_id === $user->id,
            403
        );

        if ($konsultasi->status === 'selesai') {
            return response()->json([
                'success' => false,
                'message' => 'Konsultasi ini sudah selesai, tidak bisa mengirim pesan.',
            ], 403);
        }

        $validated = $request->validate([
            'isi_pesan' => 'nullable|string|max:2000',
            'balas_ke_id' => 'nullable|exists:pesans,id',
            'file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,ppt,pptx,xls,xlsx,zip,mp4,webm,ogg,mov,avi,mp3,wav,aac,flac,m4a',
        ]);

        if (empty($validated['isi_pesan']) && !$request->hasFile('file')) {
            return response()->json(['success' => false, 'message' => 'Pesan atau file wajib diisi.'], 422);
        }

        if (!empty($validated['balas_ke_id'])) {
            $pesanAsal = Pesan::find($validated['balas_ke_id']);
            abort_if(!$pesanAsal || $pesanAsal->konsultasi_id !== $konsultasi->id, 422);
        }

        $dataPesan = [
            'konsultasi_id' => $konsultasi->id,
            'pengirim_id' => $user->id,
            'isi_pesan' => $validated['isi_pesan'] ?? null,
            'balas_ke_id' => $validated['balas_ke_id'] ?? null,
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('chat_files', 'public');

            $dataPesan['file_path'] = $path;
            $dataPesan['file_nama'] = $file->getClientOriginalName();
            $dataPesan['file_tipe'] = $file->getMimeType();
        }

        $pesan = Pesan::create($dataPesan);
        $pesan->load('balasKe.pengirim');

        $updateData = [];

        if ($konsultasi->status === 'menunggu') {
            $updateData['status'] = 'berlangsung';
        }

        if ($user->id === $konsultasi->audiens_id && $konsultasi->disembunyikan_oleh_konsultan) {
            $updateData['disembunyikan_oleh_konsultan'] = false;
        }

        if ($user->id === $konsultasi->konsultan_id && $konsultasi->disembunyikan_oleh_audiens) {
            $updateData['disembunyikan_oleh_audiens'] = false;
        }

        if (!empty($updateData)) {
            $konsultasi->update($updateData);
        }

        ActivityLog::catat($user->id, 'Kirim Pesan', $user->name . ' mengirim pesan di konsultasi #' . $konsultasi->id);

        broadcast(new PesanDikirim($pesan))->toOthers();

        $penerimaId = $user->id === $konsultasi->audiens_id ? $konsultasi->konsultan_id : $konsultasi->audiens_id;
        broadcast(new AktivitasKonsultasi($penerimaId));

        return response()->json([
            'success' => true,
            'id' => $pesan->id,
            'file_url' => $pesan->file_path ? asset('storage/' . $pesan->file_path) : null,
            'file_nama' => $pesan->file_nama,
            'file_tipe' => $pesan->file_tipe,
            'balas_ke' => $pesan->balasKe ? [
                'id' => $pesan->balasKe->id,
                'isi_pesan' => $pesan->balasKe->isi_pesan,
                'pengirim_nama' => $pesan->balasKe->pengirim->name,
            ] : null,
        ]);
    }

    public function destroy(Konsultasi $konsultasi, Pesan $pesan)
    {
        $user = Auth::user();

        abort_unless($pesan->konsultasi_id === $konsultasi->id, 404);
        abort_unless($pesan->pengirim_id === $user->id, 403);

        $pesan->update(['disembunyikan_oleh_pengirim' => true]);

        return response()->json(['success' => true]);
    }

    public function edit(Konsultasi $konsultasi, Pesan $pesan, Request $request)
    {
        $user = Auth::user();

        abort_unless($pesan->konsultasi_id === $konsultasi->id, 404);
        abort_unless($pesan->pengirim_id === $user->id, 403);

        $validated = $request->validate([
            'isi_pesan' => 'nullable|string|max:2000',
        ]);

        if (empty($validated['isi_pesan'])) {
            return response()->json(['success' => false, 'message' => 'Isi pesan tidak boleh kosong.'], 422);
        }

        $pesan->update(['isi_pesan' => $validated['isi_pesan']]);
        $pesan->load('balasKe.pengirim');

        return response()->json([
            'success' => true,
            'isi_pesan_baru' => $pesan->isi_pesan,
            'pengirim_nama' => $pesan->balasKe?->pengirim->name ?? '',
        ]);
    }

    public function clear(Konsultasi $konsultasi)
    {
        $user = Auth::user();

        abort_unless(
            $konsultasi->audiens_id === $user->id || $konsultasi->konsultan_id === $user->id,
            403
        );

        if ($user->id === $konsultasi->audiens_id) {
            $konsultasi->update(['dibersihkan_audiens_pada' => now()]);
        } else {
            $konsultasi->update(['dibersihkan_konsultan_pada' => now()]);
        }

        return response()->json(['success' => true]);
    }
}