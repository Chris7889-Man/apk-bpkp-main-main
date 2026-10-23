<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Memantau Percakapan</h2>
    </x-slot>

    @php
        $tanggal = \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y');

        // Gaya badge status (sesuaikan isi kolom status di database kamu)
        $statusStyle = [
            'berlangsung' => ['label' => 'Berlangsung', 'class' => 'bg-[#D5F3DF] text-[#2DB35C]'],
            'aktif'       => ['label' => 'Berlangsung', 'class' => 'bg-[#D5F3DF] text-[#2DB35C]'],
            'menunggu'    => ['label' => 'Menunggu',    'class' => 'bg-[#FDEB9E] text-[#E0B000]'],
            'pending'     => ['label' => 'Menunggu',    'class' => 'bg-[#FDEB9E] text-[#E0B000]'],
            'selesai'     => ['label' => 'Selesai',     'class' => 'bg-[#A9C6E8] text-[#2F6BB5]'],
        ];
    @endphp

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Banner --}}
            <section class="flex items-start justify-between rounded-2xl bg-[#00C2FF] px-6 py-4 text-white shadow-md">
                <div>
                    <h1 class="text-2xl font-bold leading-tight">Pantau Percakapan</h1>
                    <p class="text-lg">Pantau percakapan saat konsultasi</p>
                </div>
                <span class="text-base font-bold">{{ $tanggal }}</span>
            </section>

            {{-- Kartu daftar --}}
            <section class="mt-4 rounded-xl bg-white px-4 py-5 shadow-[0_2px_8px_rgba(0,0,0,0.18)]">

                <h2 class="mb-3 flex items-center gap-2 px-2 text-lg font-bold text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a8 8 0 01-11.5 7.2L4 20l1-4.2A8 8 0 1121 12z"/>
                    </svg>
                    Daftar Konsultasi
                </h2>

                <table class="w-full table-fixed text-sm">
                    <thead>
                        <tr class="bg-[#8DB3E2] text-xs font-bold text-white">
                            <th class="w-16 rounded-l-md px-4 py-2.5 text-left">No</th>
                            <th class="w-28 px-2 py-2.5 text-left">Audien</th>
                            <th class="w-28 px-2 py-2.5 text-left">Konsultan</th>
                            <th class="w-36 px-2 py-2.5 text-left">Bidang/Kategori</th>
                            <th class="w-32 px-2 py-2.5 text-left">Tanggal</th>
                            <th class="w-44 px-2 py-2.5 text-center">Status</th>
                            <th class="rounded-r-md px-2 py-2.5 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($konsultasis as $k)
                            @php
                                $no     = method_exists($konsultasis, 'firstItem')
                                            ? $konsultasis->firstItem() + $loop->index
                                            : $loop->iteration;
                                $bidang = $k->konsultan->konsultanProfil->kategori->nama_kategori ?? null;
                                $status = $statusStyle[strtolower($k->status)] ?? ['label' => ucfirst($k->status), 'class' => 'bg-gray-200 text-gray-600'];
                                $waktu  = \Carbon\Carbon::parse($k->created_at)->locale('id');
                            @endphp
                            <tr>
                                <td class="px-4 py-3 text-xs text-gray-900">{{ $no }}</td>
                                <td class="px-2 py-3 text-xs text-gray-800">{{ $k->audiens->name ?? '-' }}</td>
                                <td class="px-2 py-3 text-xs text-gray-800">{{ $k->konsultan->name ?? '-' }}</td>
                                <td class="px-2 py-3 text-xs text-gray-800">{{ $bidang ? 'Bidang '.$bidang : '-' }}</td>
                                <td class="px-2 py-3">
                                    <div class="text-xs lowercase text-gray-800">{{ $waktu->translatedFormat('d M Y') }}</div>
                                    <div class="text-[8px] leading-tight text-gray-600">{{ $waktu->format('H.i') }}</div>
                                </td>
                                <td class="px-2 py-3 text-center">
                                    <span class="inline-block w-28 rounded-full px-3 py-1.5 text-xs {{ $status['class'] }}">
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                                <td class="px-2 py-3">
                                    <a href="{{ route('admin.pemantauan.show', $k->id) }}"
                                       class="inline-block rounded-sm bg-[#0A84F5] px-6 py-1.5 text-xs text-white hover:bg-blue-700">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-gray-400">Belum ada percakapan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination (hanya jika controller pakai paginate()) --}}
                @if (method_exists($konsultasis, 'links'))
                    <div class="mt-4 px-2">
                        {{ $konsultasis->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>

      <script>
        (function () {
            var button = document.getElementById('userMenuButton');
            var dropdown = document.getElementById('userMenuDropdown');
            var wrapper = document.getElementById('userMenuWrapper');

            button.addEventListener('click', function (e) {
                e.stopPropagation();
                var isOpen = dropdown.style.display === 'block';
                dropdown.style.display = isOpen ? 'none' : 'block';
            });

            document.addEventListener('click', function (e) {
                if (!wrapper.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
        })();
    </script>
 
</x-app-layout>
