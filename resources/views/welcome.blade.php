<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplikasi Konsultasi Online - BPKP Perwakilan Sulawesi Selatan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">

    {{-- Header --}}
    <header class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo-bpkp.png') }}" alt="Logo BPKP" class="h-10 w-auto">
                <span class="font-semibold text-gray-800">
                    BPKP Perwakilan Sulawesi Selatan
                </span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-gray-800 font-medium hover:text-blue-600">
                    Log in
                </a>
                <a href="{{ route('register') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg">
                    Daftar
                </a>
            </div>
        </div>
    </header>

    {{-- Hero --}}
    <section class="relative w-full">
        <img src="{{ asset('images/gedung-bpkp.jpg') }}" alt="Gedung BPKP" class="w-full h-auto block">

        <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/30 to-transparent flex items-center">
            <div class="max-w-6xl mx-auto px-6 w-full">
                <div class="max-w-lg">
                    <h1 class="text-white text-2xl sm:text-3xl md:text-4xl font-bold mb-3">
                        Selamat Datang di Aplikasi Konsultasi
                    </h1>
                    <p class="text-white/90 text-base sm:text-lg mb-6">
                        Konsultasikan kebutuhan Anda dengan mudah
                    </p>
                    <a href="{{ auth()->check() ? route('dashboard') : route('register') }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg shadow-lg">
                        Mulai Konsultasi
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300 text-center py-4 text-sm">
        © {{ date('Y') }} BPKP Perwakilan Provinsi Sulawesi Selatan
    </footer>

</body>
</html>