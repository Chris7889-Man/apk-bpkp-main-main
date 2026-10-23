<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Konsultan</h2>
    </x-slot>

    @php
        // Ambil URL foto audiens (null kalau belum punya foto)
        $fotoUrl = fn ($u) => $u->foto_profil ? asset('storage/' . $u->foto_profil) : null;
    @endphp

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">{{ session('success') }}</div>
            @endif

            {{-- Pencarian --}}
            <div class="relative mb-6">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" id="cari-konsultasi" placeholder="Cari ..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Daftar permintaan konsultasi --}}
                <div class="lg:col-span-2">
                    <h3 class="font-semibold text-gray-700 mb-3">Permintaan Konsultasi Terbaru</h3>

                    <div class="space-y-3" id="daftar-konsultasi">
                        @forelse ($konsultasis as $k)
                            @php $fotoAudiens = $fotoUrl($k->audiens); @endphp
                            <div class="konsultasi-item bg-blue-600 hover:bg-blue-700 transition rounded-xl px-4 py-3 flex items-center gap-3"
                                data-nama="{{ strtolower($k->audiens->name) }}">
                                <a href="{{ route('konsultasi.show', $k->id) }}" class="flex items-center gap-3 flex-1 min-w-0">
                                    @if ($fotoAudiens)
                                        <img src="{{ $fotoAudiens }}" alt="{{ $k->audiens->name }}"
                                             class="w-9 h-9 rounded-full object-cover shrink-0 bg-white/90">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-white/90 flex items-center justify-center text-blue-600 font-semibold shrink-0">
                                            {{ strtoupper(substr($k->audiens->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-white font-semibold truncate">{{ $k->audiens->name }}</p>
                                        <p class="text-blue-100 text-xs truncate">{{ $k->status }}</p>
                                    </div>
                                </a>
                                <span class="text-white/90 text-sm shrink-0">{{ $k->created_at->format('H.i') }}</span>
                            </div>
                        @empty
                            <p class="text-gray-400">Belum ada permintaan konsultasi.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Detail permintaan / aksi terima-tolak --}}
                <div>
                    <h3 class="font-semibold text-gray-700 mb-3 invisible lg:visible">&nbsp;</h3>
                    @if ($konsultasis->isNotEmpty())
                        @php
                            $terbaru = $konsultasis->first();
                            $fotoTerbaru = $fotoUrl($terbaru->audiens);
                        @endphp
                        <div class="bg-white rounded-xl shadow p-4">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="relative shrink-0">
                                    @if ($fotoTerbaru)
                                        <img src="{{ $fotoTerbaru }}" alt="{{ $terbaru->audiens->name }}"
                                             class="w-11 h-11 rounded-full object-cover bg-gray-200">
                                    @else
                                        <div class="w-11 h-11 rounded-full bg-gray-200 flex items-center justify-center font-semibold text-gray-500">
                                            {{ strtoupper(substr($terbaru->audiens->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">{{ $terbaru->audiens->name }}</p>
                                    <p class="text-xs text-green-600">Online</p>
                                </div>
                                <span class="ml-auto text-xs text-gray-400 shrink-0">{{ $terbaru->created_at->format('H.i') }}</span>
                            </div>

                            @if ($terbaru->pesans->last())
                                <p class="text-sm text-gray-600 mb-4 truncate">{{ $terbaru->pesans->last()->isi_pesan }}</p>
                            @else
                                <p class="text-sm text-gray-400 mb-4 italic">Belum ada pesan.</p>
                            @endif

                            <div class="flex gap-2">
                                <form action="{{ route('konsultasi.destroy', $terbaru->id) }}" method="POST" class="flex-1"
                                    onsubmit="return confirm('Tolak permintaan konsultasi dari {{ $terbaru->audiens->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full bg-red-500 hover:bg-red-600 text-white text-sm font-semibold py-2 rounded-lg transition">
                                        Tolak
                                    </button>
                                </form>
                                <a href="{{ route('konsultasi.show', $terbaru->id) }}"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 rounded-lg text-center transition">
                                    Terima
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userId = {{ auth()->id() }};

            if (window.Echo) {
                window.Echo.private(`user.${userId}`)
                    .listen('.AktivitasKonsultasi', () => {
                        window.location.reload();
                    });
            }

            const inputCari = document.getElementById('cari-konsultasi');
            const daftar = document.getElementById('daftar-konsultasi');
            if (inputCari && daftar) {
                inputCari.addEventListener('input', function () {
                    const kata = this.value.trim().toLowerCase();
                    daftar.querySelectorAll('.konsultasi-item').forEach(function (item) {
                        item.style.display = item.dataset.nama.includes(kata) ? '' : 'none';
                    });
                });
            }
        });
    </script>
    @endpush
</x-app-layout>