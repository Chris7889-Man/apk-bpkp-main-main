<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Admin</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Banner selamat datang --}}
            <div class="bg-gradient-to-r from-sky-500 to-cyan-400 rounded-2xl shadow p-6 flex items-center justify-between text-white">
                <div>
                    <h1 class="text-2xl font-bold">Selamat Datang Admin</h1>
                    <p class="text-sky-50 mt-1">Pantau Aktifitas Penggunaan Aplikasi Konsultasi</p>
                </div>
                <div class="text-lg font-semibold whitespace-nowrap">
                    {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>

            {{-- Kartu statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-2 text-gray-500 text-sm mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                        </svg>
                        Total Audiens
                    </div>
                    <p class="text-4xl font-bold text-gray-800">{{ $totalAudiens }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-2 text-gray-500 text-sm mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Total Konsultan
                    </div>
                    <p class="text-4xl font-bold text-gray-800">{{ $totalKonsultan }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex items-center gap-2 text-gray-500 text-sm mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8-1.13 0-2.21-.18-3.2-.51L3 21l1.55-3.85C3.57 15.9 3 14.02 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Total Konsultasi
                    </div>
                    <p class="text-4xl font-bold text-gray-800">{{ $totalKonsultasi }}</p>
                </div>
            </div>

            {{-- Konsultasi terbaru + Status konsultasi --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Tabel konsultasi terbaru --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8-1.13 0-2.21-.18-3.2-.51L3 21l1.55-3.85C3.57 15.9 3 14.02 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Konsultasi Terbaru
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-blue-600 text-white text-left">
                                    <th class="px-3 py-2 rounded-l-md">No.</th>
                                    <th class="px-3 py-2">Audien</th>
                                    <th class="px-3 py-2">Konsultan</th>
                                    <th class="px-3 py-2">Kategori</th>
                                    <th class="px-3 py-2">Tanggal</th>
                                    <th class="px-3 py-2">Status</th>
                                    <th class="px-3 py-2 rounded-r-md">Aktif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($konsultasiTerbaru as $item)
                                    <tr class="border-b border-gray-100 last:border-0">
                                        <td class="px-3 py-3 text-gray-500">{{ $loop->iteration }}</td>
                                        <td class="px-3 py-3 font-medium text-gray-800">{{ $item->audiens->name ?? '-' }}</td>
                                        <td class="px-3 py-3">
                                            <p class="font-medium text-gray-800">{{ $item->konsultan->name ?? '-' }}</p>
                                        </td>
                                        <td class="px-3 py-3 text-gray-600">-</td>
                                        <td class="px-3 py-3">
                                            <p class="text-gray-800">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                                            <p class="text-xs text-gray-400">{{ $item->created_at->format('H:i') }}</p>
                                        </td>
                                        <td class="px-3 py-3">
                                            @php
                                                $badge = match ($item->status) {
                                                    'berlangsung' => 'bg-green-100 text-green-700',
                                                    'menunggu' => 'bg-yellow-100 text-yellow-700',
                                                    'selesai' => 'bg-blue-100 text-blue-700',
                                                    default => 'bg-gray-100 text-gray-600',
                                                };
                                            @endphp
                                            <span class="inline-block text-xs px-3 py-1 rounded-full font-medium {{ $badge }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <a href="{{ route('admin.pemantauan.show', $item->id) }}"
                                                class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-1.5 rounded-md transition">
                                                Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-3 py-6 text-center text-gray-400">
                                            Belum ada konsultasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Panel status konsultasi --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Status Konsultasi
                    </h3>

                    <div class="space-y-3">
                        <div class="border border-gray-100 rounded-lg p-4">
                            <p class="flex items-center gap-2 text-sm font-semibold text-green-600 mb-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                                Berlangsung
                            </p>
                            <p class="text-2xl font-bold text-gray-800">{{ $jumlahBerlangsung }}</p>
                        </div>

                        <div class="border border-gray-100 rounded-lg p-4">
                            <p class="flex items-center gap-2 text-sm font-semibold text-yellow-600 mb-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
                                Menunggu
                            </p>
                            <p class="text-2xl font-bold text-gray-800">{{ $jumlahMenunggu }}</p>
                        </div>

                        <div class="border border-gray-100 rounded-lg p-4">
                            <p class="flex items-center gap-2 text-sm font-semibold text-blue-600 mb-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                Selesai
                            </p>
                            <p class="text-2xl font-bold text-gray-800">{{ $jumlahSelesai }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>