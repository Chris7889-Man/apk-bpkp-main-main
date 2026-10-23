<x-app-layout>
    <div class="py-10">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-md p-6">

                {{-- Judul + tombol tutup --}}
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Tambah Konsultan</h2>
                    <a href="{{ route('admin.konsultan-profil.index') }}" class="text-gray-700 hover:text-black text-xl leading-none" title="Tutup">&times;</a>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.konsultan-profil.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Nama</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full h-12 rounded-xl border border-gray-500 px-4 text-sm text-gray-900 focus:ring-0 focus:border-blue-500">
                    </div>

                    {{-- Foto Profil --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Foto Profil</label>

                        <div class="mb-2">
                            <img id="foto-preview" src="" alt="Preview foto"
                                 class="hidden w-20 h-20 rounded-xl object-cover border border-gray-300">
                        </div>

                        <input type="file" name="foto_profil" id="foto_profil" accept="image/*"
                               class="w-full rounded-xl border border-gray-500 px-3 py-3 text-sm text-gray-900
                                      file:mr-3 file:rounded file:border file:border-gray-700 file:bg-white file:px-4 file:py-1 file:text-sm file:text-gray-900">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full h-12 rounded-xl border border-gray-500 px-4 text-sm text-gray-900 focus:ring-0 focus:border-blue-500">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Password</label>
                        <input type="password" name="password" required minlength="8"
                               class="w-full h-12 rounded-xl border border-gray-500 px-4 text-sm text-gray-900 focus:ring-0 focus:border-blue-500">
                        <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter. Dipakai konsultan untuk login.</p>
                    </div>

                    {{-- Bidang --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Bidang</label>
                        <select name="kategori_id" required
                                class="w-full h-12 rounded-xl border border-gray-500 px-4 text-base font-semibold text-gray-900 focus:ring-0 focus:border-blue-500">
                            <option value="">Pilih Bidang</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat->id }}" @selected(old('kategori_id') == $kat->id)>{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Bio</label>
                        <textarea name="bio" rows="3"
                                  class="w-full rounded-xl border border-gray-500 px-4 py-3 text-sm text-gray-900 focus:ring-0 focus:border-blue-500">{{ old('bio') }}</textarea>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('admin.konsultan-profil.index') }}"
                           class="rounded-lg border border-gray-700 bg-white px-8 py-2 text-sm font-bold text-gray-900 hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-[#0A84FF] px-6 py-2 text-sm font-bold text-white hover:bg-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 3h11l3 3v13a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zM7 3v6h8V3M7 21v-7h10v7"/>
                            </svg>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Preview foto langsung saat pilih file
        document.getElementById('foto_profil').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const img = document.getElementById('foto-preview');

            if (!file) {
                img.classList.add('hidden');
                return;
            }

            img.src = URL.createObjectURL(file);
            img.classList.remove('hidden');
        });
    </script>
</x-app-layout>