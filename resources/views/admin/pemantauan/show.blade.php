<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Memantau: {{ $konsultasi->audiens->name }} ↔ {{ $konsultasi->konsultan->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <p class="text-xs text-gray-400 mb-2 text-center">Mode pemantauan Admin (read-only)</p>

            <div class="bg-white shadow rounded-lg p-6 h-96 overflow-y-auto flex flex-col gap-3">
                @forelse ($konsultasi->pesans as $pesan)
                    <div class="w-full flex items-end {{ $pesan->pengirim_id === $konsultasi->audiens_id ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-xs px-4 py-2 rounded-lg {{ $pesan->pengirim_id === $konsultasi->audiens_id ? 'bg-gray-100 text-gray-800' : 'bg-blue-600 text-white' }}">
                            <p class="text-[10px] opacity-70 font-semibold">{{ $pesan->pengirim->name }}</p>

                            @if ($pesan->balasKe)
                                <div class="mb-1 pl-2 border-l-2 border-gray-400 text-xs opacity-80">
                                    <p class="font-semibold">{{ $pesan->balasKe->pengirim->name }}</p>
                                    <p class="truncate">{{ $pesan->balasKe->isi_pesan }}</p>
                                </div>
                            @endif

                            @if ($pesan->file_path)
                                @if ($pesan->isGambar())
                                    <a href="{{ asset('storage/' . $pesan->file_path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $pesan->file_path) }}" class="rounded-md max-w-full mb-1" alt="{{ $pesan->file_nama }}">
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $pesan->file_path) }}" target="_blank" class="text-xs underline">📎 {{ $pesan->file_nama }}</a>
                                @endif
                            @endif

                            @if ($pesan->isi_pesan)
                                <p class="text-sm">{{ $pesan->isi_pesan }}</p>
                            @endif
                            <p class="text-[10px] opacity-70 mt-1">{{ $pesan->created_at->format('H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-center m-auto">Belum ada pesan di percakapan ini.</p>
                @endforelse
            </div>

            <a href="{{ route('admin.pemantauan.index') }}" class="inline-block mt-4 text-blue-600 hover:underline text-sm">
                ← Kembali ke daftar percakapan
            </a>
        </div>
    </div>
</x-app-layout>