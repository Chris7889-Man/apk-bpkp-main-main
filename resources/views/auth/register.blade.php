<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-3xl bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col sm:flex-row-reverse">

            {{-- Panel kanan (tampil di kiri berkat flex-row-reverse): logo --}}
            <div class="sm:w-1/2 flex flex-col items-center justify-center gap-4 p-10 border-b sm:border-b-0 sm:border-l border-gray-200">
                <img src="{{ asset('images/logo-bpkp.png') }}" alt="Logo BPKP" class="w-32 h-auto">
                <p class="text-sm font-semibold text-gray-700 text-center">Aplikasi Konsultasi Online</p>
            </div>

            {{-- Panel kiri: form register --}}
            <div class="sm:w-1/2 p-8 sm:p-10">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Register</h1>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Role tetap dikirim sebagai audiens, tanpa perlu dipilih -->
                    <input type="hidden" name="role" value="audiens">

                    <!-- Nama -->
                    <div>
                        <x-input-label for="name" :value="__('Nama')" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <x-text-input id="name" class="block w-full pl-9" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <x-text-input id="email" class="block w-full pl-9" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <x-text-input id="password" class="block w-full pl-9"
                                            type="password"
                                            name="password"
                                            required autocomplete="new-password" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <x-text-input id="password_confirmation" class="block w-full pl-9"
                                            type="password"
                                            name="password_confirmation" required autocomplete="new-password" />
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Tombol Daftar & link login -->
                    <div class="flex items-center justify-between mt-6">
                        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                            {{ __('Sudah Punya akun') }}
                        </a>

                        <x-primary-button class="bg-blue-600 hover:bg-blue-700">
                            {{ __('Daftar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</x-guest-layout>