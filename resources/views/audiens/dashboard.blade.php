<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Audiens - BPKP Perwakilan Sulawesi Selatan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">

    {{-- Header --}}
    <header class="bg-white shadow-sm relative z-20">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-bpkp.png') }}" alt="Logo BPKP" class="h-10 w-auto">
                <span class="font-semibold text-gray-800">
                    BPKP Perwakilan Sulawesi Selatan
                </span>
            </div>

            <div class="relative" id="userMenuWrapper">
                <button id="userMenuButton" type="button"
                    class="flex items-center gap-1 text-gray-600 hover:text-gray-800">
                    <span class="font-medium">{{ Auth::user()->name }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="userMenuDropdown"
                    class="absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-30"
                    style="display: none;">
                    <a href="{{ route('profile.edit') }}"
                        class="block px-4 py-2 text-sm hover:bg-gray-50"
                        style="color: #374151; text-decoration: none;">
                        Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50"
                            style="color: #374151; background: transparent; border: none;">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="max-w-6xl mx-auto px-6 pt-4">
            <div class="bg-green-100 text-green-700 p-3 rounded text-sm">{{ session('success') }}</div>
        </div>
    @endif

    {{-- Hero (dipanjangkan sedikit) --}}
    <section class="relative w-full h-72 sm:h-80 overflow-hidden">
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
    </section>

    {{-- Kartu mengambang, nempel di batas bawah hero --}}
    <div class="max-w-3xl mx-auto px-6 -mt-20 relative z-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            <a href="{{ route('audiens.pilih-konsultan') }}"
                class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center text-center hover:shadow-xl transition">
                <div class="w-14 h-14 rounded-full border-2 border-blue-500 flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 12a4 4 0 100-8 4 4 0 000 8zM4 20c0-3.314 3.582-6 8-6s8 2.686 8 6" />
                    </svg>
                </div>
                <p class="font-bold text-blue-600 tracking-wide mb-1">MULAI KONSULTASI</p>
                <p class="text-sm text-gray-500">Sampaikan Keperluan Anda</p>
            </a>

            <a href="{{ route('audiens.konsultasi-saya') }}"
                class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center text-center hover:shadow-xl transition border-2 border-blue-500">
                <div class="w-14 h-14 rounded-full border-2 border-blue-500 flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8-1.13 0-2.21-.18-3.2-.51L3 21l1.55-3.85C3.57 15.9 3 14.02 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <p class="font-bold text-blue-600 tracking-wide mb-1">LIHAT PESAN</p>
                <p class="text-sm text-gray-500">Lihat Semua Pesan Anda Disini</p>
            </a>

        </div>
    </div>

    <p class="text-center text-xs text-gray-400 mt-14 pb-10">&copy; {{ date('Y') }} BPKP Perwakilan Provinsi Sulawesi Selatan</p>

    <script>
        (function () {
            var button = document.getElementById('userMenuButton');
            var dropdown = document.getElementById('userMenuDropdown');
            var wrapper = document.getElementById('userMenuWrapper');

            button.addEventListener('click', function (e) {
                e.stopPropagation();
                var isOpen = dropdown.style.display === 'block';
                dropdown.style.display = isOpen ? 'none' : 'block';
            });

            document.addEventListener('click', function (e) {
                if (!wrapper.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
        })();
    </script>

</body>
</html>
