<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Konsultasi Saya</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                <div class="lg:col-span-1 overflow-y-auto" style="max-height: 82vh;">
                    <a href="{{ route('audiens.pilih-konsultan') }}"
                        class="flex items-center justify-center gap-2 w-full mb-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Konsultasi Baru
                    </a>

                    @include('partials._daftar-konsultasi', [
                        'konsultasis' => $konsultasis,
                        'sisi' => 'konsultan',
                        'rutePilihKonsultan' => 'audiens.pilih-konsultan',
                        'rutaChat' => 'audiens.konsultasi-saya',
                        'aktifId' => $konsultasiAktif->id ?? null,
                    ])
                </div>

                <div class="lg:col-span-2">
                    @if ($konsultasiAktif)
                        @include('partials._chat-konsultasi', ['konsultasi' => $konsultasiAktif])
                    @else
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm flex items-center justify-center text-center p-12" style="min-height: 78vh;">
                            <div>
                                <p class="text-gray-500 mb-1">Pilih konsultasi di samping</p>
                                <p class="text-gray-400 text-sm">untuk mulai atau melanjutkan percakapan.</p>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>