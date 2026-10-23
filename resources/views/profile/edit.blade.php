<x-app-layout>
    @php
        $user   = auth()->user();
        $role   = $user->role;
        $isAudiens = $role === 'audiens';
        $profil = $role === 'konsultan' ? ($user->konsultanProfil ?? null) : null;

        // Foto konsultan dibaca dari profil konsultan (sama seperti di halaman Pilih Konsultan),
        // cadangannya foto di akun user
        $foto = $profil?->foto_profil
            ? asset('storage/' . $profil->foto_profil)
            : ($user->foto_profil ? asset('storage/' . $user->foto_profil) : null);

        $bidang = $profil?->kategori?->nama_kategori;
        $aktif  = $profil ? (bool) $profil->aktif : (bool) ($user->is_active ?? true);

        $tanggalLahir = $user->tanggal_lahir
            ? \Carbon\Carbon::parse($user->tanggal_lahir)->locale('id')->translatedFormat('d F Y')
            : null;

        $roleStyle = [
            'admin'     => 'bg-[#FFE1D6] text-[#D9531E]',
            'konsultan' => 'bg-[#D6E6FA] text-[#2B6CC4]',
            'audiens'   => 'bg-[#EADCF8] text-[#7B3FBF]',
        ];

        $kotakInfo = 'border: 2px solid #4B5563; border-radius: 10px; padding: 12px 16px; font-size: 14px; color: #111827; background: #ffffff;';
    @endphp

    <div class="py-8">
        <div class="px-4 sm:px-6 lg:px-8 space-y-6" style="max-width: 56rem; margin-left: auto; margin-right: auto;">

            {{-- Banner --}}
            <div class="bg-[#00C2FF] text-white rounded-2xl shadow-md px-6 py-4 flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold">Profil Saya</h1>
                    <p class="text-lg">Kelola informasi akun dan keamanan kamu</p>
                </div>
                <span class="font-bold text-lg">{{ now()->locale('id')->translatedFormat('d F Y') }}</span>
            </div>

            {{-- Kartu profil --}}
            <div class="bg-white rounded-2xl shadow-md" style="overflow: hidden;">

                {{-- Strip gradasi --}}
                <div style="height: 90px; background: linear-gradient(90deg, #8FB4E0, #00C2FF);"></div>

                <div style="padding: 0 32px 28px 32px;">

                    {{-- Foto + nama --}}
                    <div style="display: flex; align-items: flex-end; gap: 24px; flex-wrap: wrap; margin-top: -60px;">

                        @if ($foto)
                            <img src="{{ $foto }}" alt="Foto {{ $user->name }}"
                                 style="width: 120px; height: 120px; min-width: 120px; border-radius: 9999px; object-fit: cover; border: 5px solid #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.2); background: #ffffff;">
                        @else
                            <div style="width: 120px; height: 120px; min-width: 120px; border-radius: 9999px; border: 5px solid #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.2); background: #8FB4E0; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 44px; font-weight: 700;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif

                        <div style="padding-bottom: 6px; text-align: left;">
                            <h2 class="text-2xl font-bold text-gray-900" style="margin: 0;">{{ $user->name }}</h2>

                            @unless ($isAudiens)
                                <p class="text-sm text-gray-500" style="margin: 4px 0 0 0;">{{ $user->email }}</p>
                            @endunless
                        </div>
                    </div>

                    {{-- Badge --}}
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 18px;">
                        <span class="inline-block font-bold text-xs px-4 py-1 rounded-full {{ $roleStyle[$role] ?? 'bg-gray-200 text-gray-600' }}">
                            {{ ucfirst($role) }}
                        </span>

                        @if ($aktif)
                            <span class="inline-block bg-green-200 text-green-600 font-bold text-xs px-4 py-1 rounded-full">Aktif</span>
                        @else
                            <span class="inline-block bg-red-100 text-red-600 font-bold text-xs px-4 py-1 rounded-full">Nonaktif</span>
                        @endif

                        @if ($bidang)
                            <span class="inline-block bg-[#D5F3DF] text-[#2DB35C] font-bold text-xs px-4 py-1 rounded-full">
                                Bidang {{ $bidang }}
                            </span>
                        @endif
                    </div>

                    {{-- Khusus audiens: tanggal lahir, email, tombol edit dan hapus --}}
                    @if ($isAudiens)
                        <hr style="border: 0; border-top: 2px solid #111827; margin: 24px 0;">

                        <div style="margin-bottom: 16px;">
                            <p class="text-sm font-bold text-gray-900" style="margin: 0 0 8px 0;">Tanggal Lahir</p>
                            <div style="{{ $kotakInfo }}">{{ $tanggalLahir ?? '-' }}</div>
                        </div>

                        <div style="margin-bottom: 24px;">
                            <p class="text-sm font-bold text-gray-900" style="margin: 0 0 8px 0;">Email</p>
                            <div style="{{ $kotakInfo }}">{{ $user->email }}</div>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 16px;">
                            <a href="#informasi-profil"
                               style="background: #0A84FF; color: #ffffff; font-weight: 700; font-size: 14px; padding: 10px 32px; border-radius: 12px; text-decoration: none;">
                                Edit
                            </a>

                            <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                                    style="background: #FF3B47; color: #ffffff; font-weight: 700; font-size: 14px; padding: 10px 32px; border-radius: 12px; border: 0; cursor: pointer;">
                                Hapus
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Informasi profil --}}
            <div id="informasi-profil" class="p-4 sm:p-8 bg-white rounded-2xl shadow-md" style="border-left: 4px solid #00C2FF; scroll-margin-top: 20px;">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Ganti password --}}
            <div class="p-4 sm:p-8 bg-white rounded-2xl shadow-md" style="border-left: 4px solid #0A84FF;">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Hapus akun --}}
            <div class="p-4 sm:p-8 bg-white rounded-2xl shadow-md" style="border-left: 4px solid #F87171;">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>