<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Konsultan</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">{{ session('success') }}</div>
            @endif

            {{-- Banner biru judul halaman --}}
            <div class="bg-blue-500 rounded-xl px-6 py-5 mb-6 flex items-center justify-between text-white">
                <div>
                    <h1 class="text-lg font-bold">Kelola Konsultan</h1>
                    <p class="text-blue-100 text-sm">Kelola Data dan Akun Konsultan</p>
                </div>
                <span class="text-sm font-medium">{{ now()->translatedFormat('d F Y') }}</span>
            </div>

            {{-- Tombol tambah + pencarian --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <a href="{{ route('admin.konsultan-profil.create') }}"
                    class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                    <span class="text-lg leading-none">+</span> Tambah Konsultan
                </a>

                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama, email, Kategori/Bidang..."
                        class="border border-gray-200 rounded-lg px-4 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded-lg transition">
                        Cari
                    </button>
                </form>
            </div>

            {{-- Tabel konsultan --}}
            <div class="bg-white shadow rounded-xl overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-blue-100 text-blue-800 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 w-12">No</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Kategori/Bidang</th>
                            <th class="px-4 py-3">Jabatan</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($profils as $profil)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $profil->user->name }}</td>
                                <td class="px-4 py-3">
                                    <a href="mailto:{{ $profil->user->email }}" class="text-blue-600 hover:underline">
                                        {{ $profil->user->email }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-gray-600">Bidang {{ $profil->kategori->nama_kategori }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $profil->jabatan ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.konsultan-profil.edit', $profil->id) }}"
                                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-1.5 rounded-full transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada profil konsultan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>