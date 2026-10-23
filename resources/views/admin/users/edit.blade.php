<x-app-layout>
    <div class="py-10">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-md p-6">

                {{-- Judul + tombol tutup --}}
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Edit Akun Pengguna</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-gray-700 hover:text-black text-xl leading-none" title="Tutup">&times;</a>
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

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Foto Profil --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Foto Profil</label>

                        <div class="mb-2">
                            <img id="foto-preview"
                                 src="{{ $user->foto_profil ? asset('storage/' . $user->foto_profil) : '' }}"
                                 alt="Foto profil"
                                 class="w-20 h-20 rounded-full object-cover border border-gray-300 {{ $user->foto_profil ? '' : 'hidden' }}">

                            @unless ($user->foto_profil)
                                <div id="foto-placeholder"
                                     class="w-20 h-20 rounded-full bg-gray-100 border border-gray-300 flex items-center justify-center text-2xl font-bold text-gray-400">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endunless
                        </div>

                        <input type="file" name="foto_profil" id="foto_profil" accept="image/*"
                               class="w-full rounded-xl border border-gray-500 px-3 py-3 text-sm text-gray-900
                                      file:mr-3 file:rounded file:border file:border-gray-700 file:bg-white file:px-4 file:py-1 file:text-sm file:text-gray-900">
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full h-12 rounded-xl border border-gray-500 px-4 text-sm text-gray-900 focus:ring-0 focus:border-blue-500">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full h-12 rounded-xl border border-gray-500 px-4 text-sm text-gray-900 focus:ring-0 focus:border-blue-500">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Status</label>
                        @php $statusAktif = (string) old('is_active', (int) ($user->is_active ?? 1)); @endphp
                        <select name="is_active" required
                                class="w-full h-12 rounded-xl border border-gray-500 px-4 text-sm font-semibold text-gray-900 focus:ring-0 focus:border-blue-500">
                            <option value="1" @selected($statusAktif === '1')>Aktif</option>
                            <option value="0" @selected($statusAktif === '0')>Nonaktif</option>
                        </select>
                        @if ($user->id === auth()->id())
                            <p class="text-xs text-gray-400 mt-1">Akun yang sedang kamu pakai tidak bisa dinonaktifkan.</p>
                        @endif
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('admin.users.index') }}"
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