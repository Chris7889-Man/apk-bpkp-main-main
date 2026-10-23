<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900">
            Informasi Profil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Perbarui data dan alamat email akun kamu.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        @if ($user->role === 'audiens')
            {{-- Foto profil --}}
            <div>
                <x-input-label for="foto_profil" value="Foto Profil" />

                <div style="display: flex; align-items: center; gap: 16px; margin-top: 8px;">
                    @if ($user->foto_profil)
                        <img id="foto-preview" src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto profil"
                             style="width: 80px; height: 80px; min-width: 80px; border-radius: 9999px; object-fit: cover; border: 2px solid #D1D5DB;">
                    @else
                        <img id="foto-preview" src="" alt="Preview foto"
                             style="display: none; width: 80px; height: 80px; min-width: 80px; border-radius: 9999px; object-fit: cover; border: 2px solid #D1D5DB;">

                        <div id="foto-placeholder"
                             style="width: 80px; height: 80px; min-width: 80px; border-radius: 9999px; background: #8FB4E0; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 30px; font-weight: 700;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <input type="file" id="foto_profil" name="foto_profil" accept="image/*"
                           class="block w-full text-sm text-gray-700 border border-gray-300 rounded-xl"
                           style="padding: 10px 12px;">
                </div>

                <p class="mt-2 text-xs text-gray-500">
                    Format JPG atau PNG, maksimal 2 MB. Foto ini terlihat oleh konsultan dan admin.
                </p>

                <x-input-error class="mt-2" :messages="$errors->get('foto_profil')" />

                @if ($user->foto_profil)
                    <label style="display: flex; align-items: center; gap: 8px; margin-top: 8px; font-size: 13px; color: #4B5563;">
                        <input type="checkbox" name="hapus_foto" value="1" class="rounded border-gray-300">
                        Hapus foto profil saat ini
                    </label>
                @endif
            </div>
        @endif

        <div>
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                          style="border-radius: 12px; height: 46px;"
                          :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        @if ($user->role === 'audiens')
            <div>
                <x-input-label for="tanggal_lahir" value="Tanggal Lahir" />
                <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="date" class="mt-1 block w-full"
                              style="border-radius: 12px; height: 46px;"
                              max="{{ now()->subDay()->format('Y-m-d') }}"
                              :value="old('tanggal_lahir', $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : '')" />
                <x-input-error class="mt-2" :messages="$errors->get('tanggal_lahir')" />
            </div>
        @endif

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                          style="border-radius: 12px; height: 46px;"
                          :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Alamat email kamu belum terverifikasi.

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none">
                            Klik di sini untuk kirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            Link verifikasi baru sudah dikirim ke email kamu.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                    style="background: #0A84FF; color: #ffffff; font-weight: 700; font-size: 14px; padding: 10px 28px; border-radius: 12px; border: 0; cursor: pointer;">
                Simpan
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                   class="text-sm font-semibold text-green-600">Tersimpan.</p>
            @endif
        </div>
    </form>

    <script>
        // Preview foto langsung saat pilih file baru
        (function () {
            const input = document.getElementById('foto_profil');
            if (!input) return;

            input.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;

                const img = document.getElementById('foto-preview');
                const placeholder = document.getElementById('foto-placeholder');

                img.src = URL.createObjectURL(file);
                img.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            });
        })();
    </script>
</section>