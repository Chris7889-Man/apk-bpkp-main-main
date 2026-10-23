<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Obrolan</h2>
    </x-slot>

    @php
        // Ambil URL foto audiens (null kalau belum punya foto)
        $fotoUrl = fn ($u) => $u->foto_profil ? asset('storage/' . $u->foto_profil) : null;
    @endphp

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Split view: daftar obrolan (kiri) + chat aktif (kanan) --}}
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex" style="height: 70vh;">

                {{-- Kolom kiri: daftar obrolan --}}
                <div class="w-full sm:w-72 shrink-0 border-r border-gray-100 flex flex-col {{ $selected ? 'hidden sm:flex' : 'flex' }}">
                    <div class="p-3 border-b border-gray-100">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" id="cari-obrolan" placeholder="Cari ..."
                                class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto" id="daftar-obrolan">
                        @forelse ($konsultasis as $k)
                            @php $fotoAudiens = $fotoUrl($k->audiens); @endphp
                            <a href="{{ route('konsultan.obrolan', $k->id) }}"
                                class="konsultasi-item flex items-center gap-3 px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition {{ $selected && $selected->id === $k->id ? 'bg-blue-50' : '' }}"
                                data-nama="{{ strtolower($k->audiens->name) }}">
                                @if ($fotoAudiens)
                                    <img src="{{ $fotoAudiens }}" alt="{{ $k->audiens->name }}"
                                         class="w-11 h-11 shrink-0 rounded-full object-cover bg-gray-200">
                                @else
                                    <div class="w-11 h-11 shrink-0 rounded-full bg-gray-200 flex items-center justify-center font-semibold text-gray-500">
                                        {{ strtoupper(substr($k->audiens->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-gray-800 truncate">{{ $k->audiens->name }}</p>
                                    @if ($k->pesans->last())
                                        <p class="text-xs text-gray-500 truncate">{{ $k->pesans->last()->isi_pesan ?? '📎 File' }}</p>
                                    @else
                                        <p class="text-xs text-gray-400 italic">Belum ada pesan</p>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <p class="text-gray-400 text-sm text-center p-6">Belum ada obrolan.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Kolom kanan: isi chat --}}
                <div class="flex-1 flex flex-col {{ $selected ? 'flex' : 'hidden sm:flex' }}">
                    @if ($selected)
                        @php $fotoSelected = $fotoUrl($selected->audiens); @endphp
                        <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-3">
                            <a href="{{ route('konsultan.obrolan') }}" class="sm:hidden text-gray-400 hover:text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                            @if ($fotoSelected)
                                <img src="{{ $fotoSelected }}" alt="{{ $selected->audiens->name }}"
                                     class="w-10 h-10 rounded-full object-cover bg-gray-200">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-semibold text-gray-500">
                                    {{ strtoupper(substr($selected->audiens->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-semibold text-gray-800 leading-tight">{{ $selected->audiens->name }}</p>
                                    @if ($selected->topik || $selected->topik_lainnya)
                                        <span class="text-[11px] font-medium bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full whitespace-nowrap">
                                            {{ $selected->topik->nama_topik ?? $selected->topik_lainnya }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-green-600">Online</p>
                            </div>
                            <a href="{{ route('konsultasi.show', $selected->id) }}"
                                class="ml-auto text-gray-400 hover:text-blue-600 text-xs underline">
                                Buka chat penuh
                            </a>
                        </div>

                        <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-2 bg-gray-50" id="chat-box">
                            {{-- Kartu topik --}}
                            @if ($selected->topik || $selected->topik_lainnya)
                                <div class="flex justify-center mb-2">
                                    <div class="flex items-center gap-1.5 bg-white border border-gray-200 shadow-sm rounded-full px-4 py-1.5">
                                        <span class="text-sm">📌</span>
                                        <span class="text-xs font-medium text-gray-600">
                                            Topik: {{ $selected->topik->nama_topik ?? $selected->topik_lainnya }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @foreach ($selected->pesans as $pesan)
                                <div class="w-full flex {{ $pesan->pengirim_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-xs px-4 py-2 rounded-2xl shadow-sm {{ $pesan->pengirim_id === auth()->id() ? 'bg-blue-600 text-white rounded-br-sm' : 'bg-white text-gray-800 border border-gray-200 rounded-bl-sm' }}">
                                        @if ($pesan->isi_pesan)
                                            <p class="text-sm">{{ $pesan->isi_pesan }}</p>
                                        @endif
                                        <p class="text-[10px] opacity-70 mt-1 text-right">{{ $pesan->created_at->format('H:i') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <form id="form-pesan" class="p-3 border-t border-gray-100 flex gap-2">
                            <input type="text" id="input-pesan" autocomplete="off"
                                class="flex-1 bg-gray-100 border-none rounded-full px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Kirim Pesan ....">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 rounded-full transition">
                                Kirim
                            </button>
                        </form>
                    @else
                        <div class="flex-1 flex items-center justify-center text-gray-400 text-sm">
                            Pilih salah satu obrolan di sebelah kiri.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputCari = document.getElementById('cari-obrolan');
            const daftar = document.getElementById('daftar-obrolan');
            if (inputCari && daftar) {
                inputCari.addEventListener('input', function () {
                    const kata = this.value.trim().toLowerCase();
                    daftar.querySelectorAll('.konsultasi-item').forEach(function (item) {
                        item.style.display = item.dataset.nama.includes(kata) ? '' : 'none';
                    });
                });
            }

            @if ($selected)
            const konsultasiId = {{ $selected->id }};
            const userId = {{ auth()->id() }};
            const namaSaya = '{{ auth()->user()->name }}';
            const chatBox = document.getElementById('chat-box');
            const form = document.getElementById('form-pesan');
            const input = document.getElementById('input-pesan');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            function tambahBubble(isiPesan, waktu, pengirimId) {
                const milikSendiri = pengirimId === userId;
                const wrapper = document.createElement('div');
                wrapper.className = 'w-full flex ' + (milikSendiri ? 'justify-end' : 'justify-start');
                wrapper.innerHTML = `
                    <div class="max-w-xs px-4 py-2 rounded-2xl shadow-sm ${milikSendiri ? 'bg-blue-600 text-white rounded-br-sm' : 'bg-white text-gray-800 border border-gray-200 rounded-bl-sm'}">
                        <p class="text-sm"></p>
                        <p class="text-[10px] opacity-70 mt-1 text-right"></p>
                    </div>`;
                wrapper.querySelector('p.text-sm').textContent = isiPesan;
                wrapper.querySelector('p.text-\\[10px\\]').textContent = waktu;
                chatBox.appendChild(wrapper);
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                const isiPesan = input.value.trim();
                if (!isiPesan) return;

                input.value = '';

                const formData = new FormData();
                formData.append('isi_pesan', isiPesan);

                const res = await fetch(`/konsultasi/${konsultasiId}/pesan`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Socket-ID': window.Echo.socketId(),
                    },
                    body: formData,
                });

                const data = await res.json();
                if (data.success) {
                    tambahBubble(isiPesan, 'Baru saja', userId);
                }
            });

            window.Echo.private(`konsultasi.${konsultasiId}`)
                .listen('.PesanDikirim', (e) => {
                    if (e.pengirim_id === userId) return;
                    tambahBubble(e.isi_pesan, e.waktu, e.pengirim_id);
                });

            chatBox.scrollTop = chatBox.scrollHeight;
            @endif
        });
    </script>
    @endpush
</x-app-layout>