<x-app-layout>
    <div class="py-10">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-md p-6">

                {{-- Judul + tombol tutup --}}
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Edit Konsultan</h2>
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

                <form action="{{ route('admin.konsultan-profil.update', $konsultanProfil->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Nama</label>
                        <input type="text" value="{{ $konsultanProfil->user->name }}" disabled
                               class="w-full h-12 rounded-xl border border-gray-500 bg-gray-50 px-4 text-sm text-gray-700">
                    </div>

                    {{-- Foto Profil --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Foto Profil</label>

                        <div class="mb-2">
                            <img id="foto-preview"
                                 src="{{ $konsultanProfil->foto_profil ? asset('storage/' . $konsultanProfil->foto_profil) : '' }}"
                                 alt="Foto profil"
                                 class="w-20 h-20 rounded-xl object-cover border border-gray-300 {{ $konsultanProfil->foto_profil ? '' : 'hidden' }}">

                            @unless ($konsultanProfil->foto_profil)
                                <div id="foto-placeholder" class="w-20 h-20 rounded-xl bg-gray-100 border border-gray-300 flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0"/>
                                    </svg>
                                </div>
                            @endunless
                        </div>

                        <input type="file" name="foto_profil" id="foto_profil" accept="image/*"
                               class="w-full rounded-xl border border-gray-500 px-3 py-3 text-sm text-gray-900
                                      file:mr-3 file:rounded file:border file:border-gray-700 file:bg-white file:px-4 file:py-1 file:text-sm file:text-gray-900">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Email</label>
                        <input type="email" value="{{ $konsultanProfil->user->email }}" disabled
                               class="w-full h-12 rounded-xl border border-gray-500 bg-gray-50 px-4 text-sm text-gray-700">
                    </div>

                    {{-- Bidang --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Bidang</label>
                        <select name="kategori_id" required
                                class="w-full h-12 rounded-xl border border-gray-500 px-4 text-base font-semibold text-gray-900 focus:ring-0 focus:border-blue-500">
                            <option value="" disabled>Pilih Bidang</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat->id }}" @selected(old('kategori_id', $konsultanProfil->kategori_id) == $kat->id)>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Bio</label>
                        <textarea name="bio" rows="3"
                                  class="w-full rounded-xl border border-gray-500 px-4 py-3 text-sm text-gray-900 focus:ring-0 focus:border-blue-500">{{ old('bio', $konsultanProfil->bio) }}</textarea>
                    </div>

                    {{-- Aktif --}}
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="aktif" value="0">
                        <input type="checkbox" name="aktif" id="aktif" value="1"
                               {{ old('aktif', $konsultanProfil->aktif) ? 'checked' : '' }}
                               class="rounded border-gray-400">
                        <label for="aktif" class="text-sm text-gray-700">Aktif</label>
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
        // Preview foto langsung saat pilih file baru
        document.getElementById('foto_profil').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const img = document.getElementById('foto-preview');
            const placeholder = document.getElementById('foto-placeholder');

            img.src = URL.createObjectURL(file);
            img.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        });
    </script>
</x-app-layout>