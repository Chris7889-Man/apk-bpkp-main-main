<x-app-layout>

    {{-- Hero (gambar gedung + floating card) --}}
    <div class="relative">
        <div class="relative w-full h-80 sm:h-96 overflow-hidden">
            <img src="{{ asset('images/gedung-bpkp.jpg') }}" alt="Gedung BPKP" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/30 to-transparent flex items-center">
                <div class="max-w-6xl mx-auto px-6 w-full">
                    <h1 class="text-white text-2xl sm:text-3xl font-bold mb-2">
                        Selamat datang, {{ Auth::user()->name }} !
                    </h1>
                    <p class="text-white/90 text-base sm:text-lg">
                        Pilih konsultan dan mulai konsultasi
                    </p>
                </div>
            </div>
        </div>

        {{-- Kartu putih mengambang berisi pencarian, filter, dan daftar konsultan --}}
        <div class="max-w-6xl mx-auto px-6 -mt-16 relative z-10 pb-10">
            <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">

                <form action="{{ route('audiens.pilih-konsultan') }}" method="GET" class="flex gap-3 mb-6">
                    @if (request('kategori_id'))
                        <input type="hidden" name="kategori_id" value="{{ request('kategori_id') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..."
                        class="flex-1 border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold text-sm px-6 rounded-lg">
                        Cari
                    </button>
                </form>

                <div class="flex flex-wrap gap-2 mb-6">
                    <a href="{{ route('audiens.pilih-konsultan', array_filter(['search' => request('search')])) }}"
                        class="px-4 py-2 rounded-md text-sm font-semibold {{ !request('kategori_id') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                        Semua
                    </a>
                    @foreach ($kategoris as $kat)
                        <a href="{{ route('audiens.pilih-konsultan', array_filter(['search' => request('search'), 'kategori_id' => $kat->id])) }}"
                            class="px-4 py-2 rounded-md text-sm font-semibold {{ request('kategori_id') == $kat->id ? 'bg-blue-600 text-white' : 'bg-blue-500 text-white' }}">
                            {{ $kat->nama_kategori }}
                        </a>
                    @endforeach
                </div>

                {{-- Banner deskripsi kategori aktif --}}
                @php
                    $kategoriAktif = $kategoris->firstWhere('id', (int) request('kategori_id'));
                @endphp
                @if ($kategoriAktif && $kategoriAktif->keterangan)
                    <div class="mb-6 bg-blue-50 border border-blue-100 text-blue-800 text-sm rounded-lg px-4 py-3">
                        <span class="font-semibold">{{ $kategoriAktif->nama_kategori }}:</span>
                        {{ $kategoriAktif->keterangan }}
                    </div>
                @endif

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                    @forelse ($konsultans as $kp)
                        @php
                            // Hanya topik yang sesuai bidang konsultan ini
                            $topikBidang = $topiks->where('kategori_id', $kp->kategori_id);
                        @endphp
                        <div class="flex flex-col">
                            <form action="{{ route('audiens.konsultasi.store', $kp->user->id) }}" method="POST" class="flex flex-col">
                                @csrf

                                <div class="bg-white rounded-t-lg shadow border border-gray-200 p-4 flex flex-col items-center text-center">
                                    @if ($kp->foto_profil)
                                        <img src="{{ asset('storage/' . $kp->foto_profil) }}"
                                            style="width:100%;aspect-ratio:1/1;object-fit:cover;"
                                            class="rounded-md mb-4">
                                    @else
                                        <div style="width:100%;aspect-ratio:1/1;"
                                            class="rounded-md bg-gray-100 flex items-center justify-center text-gray-400 font-semibold text-3xl mb-4">
                                            {{ strtoupper(substr($kp->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <p class="font-bold text-gray-800">{{ $kp->user->name }}</p>
                                    <p class="text-sm text-blue-600 font-medium mb-3">Bidang {{ $kp->kategori->nama_kategori }}</p>

                                    <div class="w-full text-left">
                                        <label class="block text-[11px] font-medium text-gray-500 mb-1">
                                            Topik pertanyaan (opsional)
                                        </label>
                                        <select name="topik_id"
                                            onchange="this.closest('form').querySelector('.topik-lainnya-input').classList.toggle('hidden', this.value !== 'lainnya')"
                                            class="w-full text-xs border border-gray-300 rounded-md px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-400">
                                            <option value="">-- Pilih topik --</option>
                                            @foreach ($topikBidang as $t)
                                                <option value="{{ $t->id }}">{{ $t->nama_topik }}</option>
                                            @endforeach
                                            <option value="lainnya">Lainnya...</option>
                                        </select>
                                        <input type="text" name="topik_lainnya" placeholder="Tulis topik Anda..."
                                            class="topik-lainnya-input hidden w-full text-xs border border-gray-300 rounded-md px-2 py-1.5 mt-1.5 focus:outline-none focus:ring-1 focus:ring-blue-400">
                                    </div>
                                </div>

                                <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-semibold py-2 rounded-b-lg -mt-px">
                                    KONSUL
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-gray-400 col-span-full text-center py-10">Tidak ada konsultan ditemukan.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

</x-app-layout>