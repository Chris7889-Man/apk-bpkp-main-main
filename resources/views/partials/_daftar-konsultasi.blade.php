{{-- 
    Partial daftar konsultasi.
    Dipakai bareng oleh:
    - resources/views/audiens/konsultasi-saya.blade.php
    - resources/views/konsultan/obrolan.blade.php

    Variabel:
    - $konsultasis
    - $sisi
    - $rutePilihKonsultan (opsional)
    - $rutaChat (opsional, default 'konsultasi.show')
    - $aktifId (opsional, id konsultasi yang sedang dibuka)
--}}

<div class="space-y-3">

    @forelse ($konsultasis as $k)

        @php
            // Lawan bicara berdasarkan sisi pengguna
            $lawanBicara = $k->{$sisi};

            // Pesan terakhir
            $pesanTerakhir = $k->pesans->last();

            // Jumlah pesan dari lawan bicara yang belum dibaca
            $belumDibaca = $k->pesans->where('pengirim_id', '!=', auth()->id())->whereNull('dibaca_at')->count();

            // Apakah konsultasi ini yang sedang dibuka di panel kanan
            $sedangDibuka = isset($aktifId) && (int) $aktifId === $k->id;

            // Tentukan style status
            $statusStyle = 'bg-gray-100 text-gray-600';

            if ($k->status === 'berlangsung') {
                $statusStyle = 'bg-yellow-100 text-yellow-700';
            } elseif ($k->status === 'selesai') {
                $statusStyle = 'bg-green-100 text-green-700';
            }
        @endphp

        <div
            class="group bg-white rounded-xl shadow-sm hover:shadow-md border p-4 flex items-center justify-between gap-3 transition {{ $sedangDibuka ? 'border-blue-400 ring-1 ring-blue-200' : 'border-gray-100' }}">

            {{-- Link konsultasi --}}
            <a href="{{ route($rutaChat ?? 'konsultasi.show', $k->id) }}"
                class="flex items-center gap-3 flex-1 min-w-0">

                {{-- Avatar --}}
                <div
                    style="width:48px;height:48px;min-width:48px;min-height:48px;border-radius:9999px;"
                    class="bg-blue-100 text-blue-600 flex items-center justify-center font-semibold text-lg relative">

                    {{ strtoupper(substr($lawanBicara->name ?? '?', 0, 1)) }}

                    @if ($belumDibaca > 0)
                        <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center">
                            {{ $belumDibaca > 9 ? '9+' : $belumDibaca }}
                        </span>
                    @endif

                </div>

                {{-- Informasi konsultasi --}}
                <div class="min-w-0 flex-1">

                    {{-- Nama dan waktu --}}
                    <div class="flex items-center justify-between gap-2">

                        <p class="font-semibold text-gray-800 truncate">
                            {{ $lawanBicara->name ?? 'Pengguna' }}
                        </p>

                        @if ($k->updated_at)
                            <span class="text-xs text-gray-400 shrink-0">
                                {{ $k->updated_at->format('H:i') }}
                            </span>
                        @endif

                    </div>

                    {{-- Pesan terakhir --}}
                    @if ($pesanTerakhir)

                        <p class="text-sm text-gray-500 truncate mt-0.5">
                            {{ $pesanTerakhir->isi_pesan ?? '📎 File' }}
                        </p>

                    @else

                        <p class="text-sm text-gray-400 italic mt-0.5">
                            Belum ada pesan
                        </p>

                    @endif

                    {{-- Status konsultasi --}}
                    <span
                        class="inline-block text-xs px-2 py-0.5 rounded-full mt-1.5 {{ $statusStyle }}">

                        {{ ucfirst($k->status ?? 'menunggu') }}

                    </span>

                </div>

            </a>

            {{-- Tombol hapus --}}
            <form
                action="{{ route('konsultasi.destroy', $k->id) }}"
                method="POST"
                onsubmit="return confirm('Hapus konsultasi dengan {{ $lawanBicara->name ?? 'pengguna' }}? Semua chat di dalamnya juga akan terhapus.')">

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="shrink-0 w-9 h-9 flex items-center justify-center rounded-full text-gray-300 hover:text-red-600 hover:bg-red-50 opacity-0 group-hover:opacity-100 transition"
                    title="Hapus konsultasi">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4.5 h-4.5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />

                    </svg>

                </button>

            </form>

        </div>

    @empty

        {{-- Jika belum ada konsultasi --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">

            {{-- Icon --}}
            <div
                class="w-14 h-14 mx-auto rounded-full bg-blue-50 text-blue-400 flex items-center justify-center mb-4">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-7 h-7"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8-1.13 0-2.21-.18-3.2-.51L3 21l1.55-3.85C3.57 15.9 3 14.02 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />

                </svg>

            </div>

            <p class="text-gray-500 mb-1">
                Belum ada konsultasi
            </p>

            @if (isset($rutePilihKonsultan))

                <p class="text-gray-400 text-sm mb-4">
                    Mulai percakapan dengan konsultan untuk keperluanmu.
                </p>

                <a href="{{ route($rutePilihKonsultan) }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded-lg transition">

                    Mulai konsultasi baru

                </a>

            @else

                <p class="text-gray-400 text-sm">
                    Belum ada audiens yang mengajukan konsultasi.
                </p>

            @endif

        </div>

    @endforelse

</div>