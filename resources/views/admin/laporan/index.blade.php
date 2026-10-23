<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Sistem</h2>
    </x-slot>

    @php
        $tanggal = \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y');

        $statusStyle = [
            'berlangsung' => ['label' => 'Berlangsung', 'class' => 'bg-[#D5F3DF] text-[#2DB35C]'],
            'aktif'       => ['label' => 'Berlangsung', 'class' => 'bg-[#D5F3DF] text-[#2DB35C]'],
            'menunggu'    => ['label' => 'Menunggu',    'class' => 'bg-[#FDEB9E] text-[#E0B000]'],
            'pending'     => ['label' => 'Menunggu',    'class' => 'bg-[#FDEB9E] text-[#E0B000]'],
            'selesai'     => ['label' => 'Selesai',     'class' => 'bg-[#9CCBFA] text-[#2B7BD6]'],
        ];

        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $tahunSekarang = now()->year;
        $daftarTahun   = range($tahunSekarang, $tahunSekarang - 5);
        $selectClass   = 'w-32 rounded-full border-0 bg-white py-1 pl-3 pr-7 text-[11px] text-gray-700 shadow-sm focus:ring-2 focus:ring-blue-400';
    @endphp

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Judul khusus saat dicetak --}}
            <div class="hidden print:block mb-4">
                <h1 class="text-xl font-bold mb-1">Laporan Sistem Konsultasi Online</h1>
                <p class="text-sm text-gray-500">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
            </div>

            {{-- Banner --}}
            <section class="flex items-start justify-between rounded-2xl bg-[#00C2FF] px-6 py-4 text-white shadow-md print:hidden">
                <div>
                    <h1 class="text-2xl font-bold leading-tight">Cetak Laporan</h1>
                    <p class="text-lg">Cetak laporan konsultasi yang telah berlangsung</p>
                </div>
                <span class="text-base font-bold">{{ $tanggal }}</span>
            </section>

            {{-- Kartu --}}
            <section id="area-cetak" class="mt-4 rounded-xl bg-white px-4 py-5 shadow-[0_2px_8px_rgba(0,0,0,0.18)]">

                {{-- Toolbar: cetak + filter --}}
                <div class="mb-3 flex items-center gap-3 print:hidden">
                    <button type="button" onclick="window.print()"
                            class="inline-flex items-center gap-2 rounded-full bg-[#1D6FE0] px-5 py-2 text-sm font-bold text-white hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V3h12v6M6 18H4a1 1 0 01-1-1v-6a2 2 0 012-2h14a2 2 0 012 2v6a1 1 0 01-1 1h-2M6 14h12v7H6v-7z"/>
                        </svg>
                        Cetak
                    </button>

                    <form method="GET" action="{{ route('admin.laporan.index') }}"
                          class="flex flex-1 items-center gap-3 rounded-md bg-[#A9C4E8] px-3 py-1.5">

                        <label class="flex items-center gap-1.5 text-[10px] font-bold text-white">
                            Bidang
                            <select name="bidang" class="{{ $selectClass }}">
                                <option value=""></option>
                                @foreach (($kategoris ?? []) as $kat)
                                    <option value="{{ $kat->id }}" @selected(request('bidang') == $kat->id)>{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="flex items-center gap-1.5 text-[10px] font-bold text-white">
                            Status
                            <select name="status" class="{{ $selectClass }}">
                                <option value=""></option>
                                <option value="menunggu"    @selected(request('status') === 'menunggu')>Menunggu</option>
                                <option value="berlangsung" @selected(request('status') === 'berlangsung')>Berlangsung</option>
                                <option value="selesai"     @selected(request('status') === 'selesai')>Selesai</option>
                            </select>
                        </label>

                        <label class="flex items-center gap-1.5 text-[10px] font-bold text-white">
                            Bulan
                            <select name="bulan" class="{{ $selectClass }}">
                                <option value=""></option>
                                @foreach ($daftarBulan as $angka => $nama)
                                    <option value="{{ $angka }}" @selected(request('bulan') == $angka)>{{ $nama }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="flex items-center gap-1.5 text-[10px] font-bold text-white">
                            Tahun
                            <select name="tahun" class="{{ $selectClass }}">
                                <option value=""></option>
                                @foreach ($daftarTahun as $th)
                                    <option value="{{ $th }}" @selected(request('tahun') == $th)>{{ $th }}</option>
                                @endforeach
                            </select>
                        </label>

                        <button type="submit"
                                class="ml-auto rounded-md bg-[#1D6FE0] px-4 py-1.5 text-[11px] font-bold text-white hover:bg-blue-700">
                            Tampilkan
                        </button>
                    </form>
                </div>

                {{-- Tabel --}}
                <table class="w-full table-fixed text-sm">
                    <thead>
                        <tr class="bg-[#8DB3E2] text-sm font-bold text-white">
                            <th class="w-16 rounded-l-md px-4 py-2.5 text-center">No.</th>
                            <th class="px-2 py-2.5 text-center">Audiens</th>
                            <th class="px-2 py-2.5 text-center">Konsultan</th>
                            <th class="px-2 py-2.5 text-center">Kategori/Bidang</th>
                            <th class="px-2 py-2.5 text-center">Tanggal</th>
                            <th class="w-44 rounded-r-md px-2 py-2.5 text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($konsultasis as $k)
                            @php
                                $bidang = $k->konsultan->konsultanProfil->kategori->nama_kategori ?? null;
                                $status = $statusStyle[strtolower($k->status)] ?? ['label' => ucfirst($k->status), 'class' => 'bg-gray-200 text-gray-600'];
                            @endphp
                            <tr class="border-b border-[#6E9BD6]">
                                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700">{{ $loop->iteration }}</td>
                                <td class="px-2 py-3 text-center text-sm text-gray-600">{{ $k->audiens->name ?? '-' }}</td>
                                <td class="px-2 py-3 text-center text-sm text-gray-600">{{ $k->konsultan->name ?? '-' }}</td>
                                <td class="px-2 py-3 text-center text-sm text-gray-600">{{ $bidang ? 'Bidang '.$bidang : '-' }}</td>
                                <td class="px-2 py-3 text-center text-sm text-gray-600">{{ $k->created_at->format('d/m/Y H.i') }}</td>
                                <td class="px-2 py-3 text-center">
                                    <span class="inline-block w-24 rounded-full px-3 py-1 text-[10px] {{ $status['class'] }}">
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-gray-400">Belum ada data konsultasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        </div>
    </div>

    <style>
        @media print {
            nav, header, .print\:hidden { display: none !important; }
            body { background: white; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            #area-cetak { box-shadow: none; }
        }
    </style>
</x-app-layout>