<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Kategori Konsultan</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Banner --}}
            <div class="bg-gradient-to-r from-sky-500 to-cyan-400 rounded-2xl shadow p-6 text-white">
                <h1 class="text-2xl font-bold">Edit Kategori Konsultan</h1>
                <p class="text-sky-50 mt-1">Ubah nama atau keterangan kategori/bidang</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-700 p-4 rounded-lg text-sm">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kategori</label>
                        <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition"
                            placeholder="Contoh: Bidang Investigasi" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                        <textarea name="keterangan" rows="4"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition"
                            placeholder="Deskripsi singkat kategori ini (opsional)">{{ old('keterangan', $kategori->keterangan) }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.kategori.index') }}"
                            class="text-gray-600 font-medium px-6 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 text-sm transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>