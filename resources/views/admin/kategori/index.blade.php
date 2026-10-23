<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kategori Konsultan</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            {{-- Banner --}}
            <div class="bg-gradient-to-r from-sky-500 to-cyan-400 rounded-2xl shadow p-6 flex items-center justify-between text-white">
                <div>
                    <h1 class="text-2xl font-bold">Kategori Konsultan</h1>
                    <p class="text-sky-50 mt-1">Kelola Kategori/bidang konsultan</p>
                </div>
                <div class="text-lg font-semibold whitespace-nowrap">
                    {{ now()->translatedFormat('d F Y') }}
                </div>
            </div>

            <a href="{{ route('admin.kategori.create') }}"
                class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm font-semibold">
                + Tambah Kategori
            </a>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-blue-400/80 text-white">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama Kategori/Bidang</th>
                            <th class="px-4 py-3">Jumlah Konsultan</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($kategoris as $kategori)
                            <tr>
                                <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $kategori->nama_kategori }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $kategori->konsultanProfils_count ?? $kategori->konsultanProfils->count() ?? 0 }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusAktif = ($kategori->status ?? 'aktif') === 'aktif';
                                    @endphp
                                    <span class="inline-block text-xs px-3 py-1 rounded-full font-medium {{ $statusAktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $statusAktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 space-x-2">
                                    <a href="{{ route('admin.kategori.edit', $kategori->id) }}"
                                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 rounded-md">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Yakin hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-block bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-3 py-1.5 rounded-md">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>