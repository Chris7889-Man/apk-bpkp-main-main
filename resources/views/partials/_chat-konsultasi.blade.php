<div class="bg-white shadow-lg rounded-2xl overflow-hidden flex flex-col h-full" style="min-height: 78vh;">

    <div class="flex justify-between items-center px-5 py-3 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">
                {{ strtoupper(substr(auth()->user()->id === $konsultasi->audiens_id ? $konsultasi->konsultan->name : $konsultasi->audiens->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="font-semibold text-lg text-gray-800 leading-tight">
                        {{ auth()->user()->id === $konsultasi->audiens_id ? $konsultasi->konsultan->name : $konsultasi->audiens->name }}
                    </h2>
                    @if ($konsultasi->topik || $konsultasi->topik_lainnya)
                        <span
                            class="text-[11px] font-medium bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full whitespace-nowrap">
                            {{ $konsultasi->topik->nama_topik ?? $konsultasi->topik_lainnya }}
                        </span>
                    @endif
                </div>
                @if ($konsultasi->status === 'selesai')
                    <span class="text-xs font-medium text-green-600">Selesai</span>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if ($konsultasi->status !== 'selesai')
                <form action="{{ route('konsultasi.selesai', $konsultasi->id) }}" method="POST"
                    onsubmit="return confirm('Tandai konsultasi ini sebagai selesai?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="text-xs font-medium text-green-600 hover:bg-green-50 px-3 py-1.5 rounded-lg transition">
                        Tandai Selesai
                    </button>
                </form>
            @endif
            <button id="btn-hapus-semua"
                class="flex items-center gap-1.5 text-red-500 text-sm font-medium px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus Semua
            </button>
        </div>
    </div>

    {{--  --}}

    <div class="flex-1 overflow-y-auto p-5 flex flex-col gap-3 bg-gray-50" id="chat-box">

        {{-- Kartu topik --}}
        @if ($konsultasi->topik || $konsultasi->topik_lainnya)
            <div class="flex justify-center mb-2">
                <div
                    class="flex items-center gap-1.5 bg-white border border-gray-200 shadow-sm rounded-full px-4 py-1.5">
                    <span class="text-sm">📌</span>
                    <span class="text-xs font-medium text-gray-600">
                        Topik: {{ $konsultasi->topik->nama_topik ?? $konsultasi->topik_lainnya }}
                    </span>
                </div>
            </div>
        @endif

        @foreach ($konsultasi->pesans as $pesan)
            <div id="pesan-{{ $pesan->id }}"
                class="w-full flex items-end gap-2 group {{ $pesan->pengirim_id === auth()->id() ? 'justify-end' : 'justify-start' }}"
                data-id="{{ $pesan->id }}" data-isi="{{ $pesan->isi_pesan }}"
                data-nama="{{ $pesan->pengirim->name }}"
                data-milik-sendiri="{{ $pesan->pengirim_id === auth()->id() ? '1' : '0' }}">

                <button
                    class="btn-reply shrink-0 w-7 h-7 flex items-center justify-center rounded-full text-gray-400 opacity-0 group-hover:opacity-100 hover:text-blue-600 hover:bg-white transition text-base">
                    ↩
                </button>

                <div class="pesan-bubble relative max-w-xs sm:max-w-sm px-4 py-2.5 shadow-sm select-none {{ $pesan->pengirim_id === auth()->id() ? 'bg-blue-600 text-white rounded-2xl rounded-br-sm' : 'bg-white text-gray-800 border border-gray-200 rounded-2xl rounded-bl-sm' }}"
                    style="-webkit-touch-callout: none; -webkit-user-select: none; touch-action: pan-y;">

                    @if ($pesan->balasKe)
                        <div
                            class="mb-1.5 pl-2 py-1 border-l-2 rounded {{ $pesan->pengirim_id === auth()->id() ? 'border-blue-300 bg-blue-500/40' : 'border-gray-300 bg-gray-50' }} text-xs opacity-90">
                            <p class="font-semibold">{{ $pesan->balasKe->pengirim->name }}</p>
                            <p class="truncate">{{ $pesan->balasKe->isi_pesan }}</p>
                        </div>
                    @endif

                    @if ($pesan->file_path)
                        @if ($pesan->isGambar())
                            <a href="{{ asset('storage/' . $pesan->file_path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $pesan->file_path) }}"
                                    class="rounded-lg max-w-full mb-1.5" alt="{{ $pesan->file_nama }}"
                                    onerror="window.retryGambar(this)">
                            </a>
                        @elseif (str_starts_with($pesan->file_tipe, 'video/'))
                            <video src="{{ asset('storage/' . $pesan->file_path) }}"
                                class="rounded-lg max-w-full mb-1.5" controls preload="metadata"></video>
                        @elseif (str_starts_with($pesan->file_tipe, 'audio/'))
                            <audio src="{{ asset('storage/' . $pesan->file_path) }}" class="w-full mb-1.5" controls
                                preload="metadata"></audio>
                        @else
                            <a href="{{ asset('storage/' . $pesan->file_path) }}" target="_blank" download
                                class="flex items-center gap-2 mb-1.5 px-3 py-2 rounded-lg {{ $pesan->pengirim_id === auth()->id() ? 'bg-blue-700/60' : 'bg-gray-100' }}">
                                <span></span>
                                <span class="text-xs truncate">{{ $pesan->file_nama }}</span>
                            </a>
                        @endif
                    @endif

@if ($pesan->voice_path)
    <div class="custom-audio-player flex items-center gap-3 bg-teal-800 text-white rounded-lg p-2 mt-1 relative w-64" data-audio-url="{{ asset('storage/' . $pesan->voice_path) }}">
        <div class="w-10 h-10 rounded-full bg-yellow-400 flex items-center justify-center text-teal-900 shrink-0">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9v7c0 1.1.9 2 2 2h4v-8H5v-1c0-3.87 3.13-7 7-7s7 3.13 7 7v1h-4v8h4c1.1 0 2-.9 2-2v-7c0-4.97-4.03-9-9-9z"/></svg>
        </div>
        <button type="button" class="audio-play hover:text-yellow-300">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
        </button>
        <div class="flex-1">
            <input type="range" class="audio-slider w-full h-1 bg-teal-600 rounded-lg appearance-none cursor-pointer" value="0" min="0" max="100">
            <div class="flex justify-between text-[10px] opacity-80">
                <span class="audio-current">0:00</span>
                <span class="audio-duration">0:00</span>
            </div>
        </div>
        <button type="button" class="audio-menu-toggle px-1 hover:text-yellow-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <audio class="custom-audio hidden" preload="metadata"></audio>
        
        <!-- Dropdown Menu -->
        <div class="message-context-menu hidden absolute top-full right-0 mt-1 w-48 rounded-lg bg-gray-900 text-white shadow-xl py-1 text-xs z-50">
            <button type="button" data-context-action="info" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">ⓘ Message info</button>
            <button type="button" data-context-action="reply" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">↩ Reply</button>
            <button type="button" data-context-action="copy" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">▣ Copy</button>
            <button type="button" data-context-action="forward" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">↠ Forward</button>
            <button type="button" data-context-action="pin" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">⚑ Pin</button>
            <button type="button" data-context-action="star" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">☆ Star</button>
            <button type="button" data-context-action="save" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">⇩ Save as</button>
            <button type="button" data-context-action="delete" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700 text-red-400">▢ Delete</button>
        </div>
    </div>
@endif

                    <div class="message-context-menu hidden absolute z-50 right-2 bottom-8 w-48 rounded-lg bg-gray-900 text-white shadow-xl py-1 text-xs">
                        <button type="button" data-context-action="info" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">ⓘ Info</button>
                        <button type="button" data-context-action="reply" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">↩ Reply</button>
                        <button type="button" data-context-action="copy" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">▣ Copy</button>
                        <button type="button" data-context-action="forward" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">↠ Forward</button>
                        <button type="button" data-context-action="pin" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">⚑ Pin</button>
                        <button type="button" data-context-action="star" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">☆ Star</button>
                        <button type="button" data-context-action="save" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">⇩ Save</button>
                        <button type="button" data-context-action="delete" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700 text-red-400">▢ Delete</button>
                    </div>

                    @if ($pesan->isi_pesan)
                        <div class="flex justify-between items-start gap-3">
                            <p class="text-sm leading-relaxed pesan-isi flex-1">{{ $pesan->isi_pesan }}</p>
                            @if ($pesan->pengirim_id === auth()->id())
                                <div class="relative message-actions">
                                    <button type="button"
                                        class="edit-menu text-xs opacity-70 cursor-pointer px-1 select-none font-bold"
                                        title="Pilihan pesan">⋮</button>
                                    <div
                                        class="message-menu hidden absolute right-0 bottom-full mb-1 bg-white text-gray-700 rounded-lg shadow-lg border border-gray-200 z-20 w-24">
                                        <button type="button"
                                            class="message-edit block w-full text-left px-3 py-2 text-xs hover:bg-gray-100">Edit</button>
                                        <button type="button"
                                            class="message-delete block w-full text-left px-3 py-2 text-xs text-red-600 hover:bg-gray-100">Hapus</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                    <p class="text-[10px] opacity-70 mt-1 text-right">
                        {{ $pesan->created_at->format('H:i') }}
                        @if ($pesan->pengirim_id === auth()->id())
                            <span class="status-baca ml-1">{{ $pesan->dibaca_at ? '✓✓' : '✓' }}</span>
                        @endif
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="px-4 pt-2 bg-white border-t border-gray-100">
        <div id="file-preview"
            class="hidden mb-2 bg-green-50 border-l-4 border-green-500 px-3 py-2 rounded-lg flex flex-col gap-1">
            <div class="flex justify-between items-center">
                <p class="text-xs font-semibold text-gray-700">Lampiran File:</p>
                <button type="button" id="btn-batal-file" class="text-gray-400 hover:text-red-500 text-sm">✕</button>
            </div>
            <div id="file-preview-list" class="flex flex-col gap-1"></div>
        </div>

        <div id="reply-preview"
            class="hidden mb-2 bg-blue-50 border-l-4 border-blue-500 px-3 py-2 rounded-lg flex justify-between items-start">
            <div class="min-w-0">
                <p class="text-xs font-semibold text-blue-600" id="reply-nama"></p>
                <p class="text-xs text-gray-500 truncate" id="reply-isi"></p>
            </div>
            <button type="button" id="btn-batal-reply"
                class="text-gray-400 hover:text-red-500 text-sm ml-2 shrink-0">✕</button>
        </div>

        @if ($konsultasi->status === 'selesai')
            <div class="flex items-center justify-center gap-2 py-3 mb-4 bg-gray-100 rounded-xl text-sm text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Konsultasi ini sudah selesai. Anda tidak dapat mengirim pesan lagi.
            </div>
        @endif

        <div id="edit-preview"
            class="hidden mb-2 bg-yellow-50 border-l-4 border-yellow-400 px-3 py-2 rounded-lg flex justify-between items-center">
            <p class="text-xs text-yellow-700">Mengedit pesan</p>
            <button type="button" id="btn-batal-edit" class="text-gray-400 hover:text-red-500 text-sm">✕</button>
        </div>

        <form id="form-pesan" class="flex gap-2 items-center pb-4">
            <label for="input-file"
                class="cursor-pointer w-10 h-10 shrink-0 flex items-center justify-center {{ $konsultasi->status === 'selesai' ? 'opacity-40 pointer-events-none' : '' }}"
                title="Attach">
                <svg class="stroke-black fill-none" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                    viewBox="0 -0.5 25 25">
                    <path stroke-linejoin="round" stroke-linecap="round" stroke-width="1.5"
                        d="M15.17 11.053L11.18 15.315C10.8416 15.6932 10.3599 15.9119 9.85236 15.9178C9.34487 15.9237 8.85821 15.7162 8.51104 15.346C7.74412 14.5454 7.757 13.2788 8.54004 12.494L13.899 6.763C14.4902 6.10491 15.3315 5.72677 16.2161 5.72163C17.1006 5.71649 17.9463 6.08491 18.545 6.736C19.8222 8.14736 19.8131 10.2995 18.524 11.7L12.842 17.771C12.0334 18.5827 10.9265 19.0261 9.78113 18.9971 8.63575 18.9682 7.55268 18.4695 6.78604 17.618C5.0337 15.6414 5.07705 12.6549 6.88604 10.73L12.253 5">
                    </path>
                </svg>
            </label>
            {{-- <button type="button" id="voice-mic-button" class="w-10 h-10 shrink-0 flex items-center justify-center text-black transition {{ $konsultasi->status === 'selesai' ? 'opacity-40 pointer-events-none' : '' }}" title="Rekam suara" onclick="toggleVoiceRecording(event)">
                <i id="voice-mic" class="bi bi-mic"></i>
            </button> --}}
            <button type="button" id="voice-mic-button"
                class="w-10 h-10 shrink-0 flex items-center justify-center text-gray-700 hover:text-blue-600 transition {{ $konsultasi->status === 'selesai' ? 'opacity-40 pointer-events-none' : '' }}"
                title="Rekam suara" onclick="toggleVoiceRecording(event)">
                <svg id="voice-mic" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                    viewBox="0 0 60 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
                </svg>
            </button>
            <input type="file" id="input-file" class="hidden" multiple
                accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip"
                {{ $konsultasi->status === 'selesai' ? 'disabled' : '' }}>
            <input type="text" id="input-pesan" autocomplete="off"
                class="flex-1 bg-gray-100 border-none rounded-full px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                placeholder="Tulis pesan..." {{ $konsultasi->status === 'selesai' ? 'disabled' : '' }}>
            <button type="submit"
                class="w-10 h-10 shrink-0 flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white rounded-full transition disabled:opacity-50 disabled:cursor-not-allowed"
                {{ $konsultasi->status === 'selesai' ? 'disabled' : '' }}>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5m0 0l-6 6m6-6l6 6" />
                </svg>
            </button>
        </form>
    </div>

</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const konsultasiId = {{ $konsultasi->id }};
            const statusSelesai = {{ $konsultasi->status === 'selesai' ? 'true' : 'false' }};

            window.retryGambar = function(img) {
                const percobaan = parseInt(img.dataset.percobaan || '0', 10);
                if (percobaan >= 5) return;

                img.dataset.percobaan = percobaan + 1;
                setTimeout(() => {
                    const urlAsli = img.src.split('?')[0];
                    img.src = urlAsli + '?retry=' + Date.now();
                }, 1000);
            };
            const userId = {{ auth()->id() }};
            const namaSaya = '{{ auth()->user()->name }}';
            const chatBox = document.getElementById('chat-box');
            const form = document.getElementById('form-pesan');
            const input = document.getElementById('input-pesan');
            const inputFile = document.getElementById('input-file');
            const filePreview = document.getElementById('file-preview');
            const filePreviewNama = document.getElementById('file-preview-nama');
            const btnBatalFile = document.getElementById('btn-batal-file');
            const btnHapusSemua = document.getElementById('btn-hapus-semua');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            const replyPreview = document.getElementById('reply-preview');
            const replyNama = document.getElementById('reply-nama');
            const replyIsi = document.getElementById('reply-isi');
            const btnBatalReply = document.getElementById('btn-batal-reply');
            const editPreview = document.getElementById('edit-preview');
            const btnBatalEdit = document.getElementById('btn-batal-edit');

            let replyTarget = null;
            let editTarget = null;

            function setReply(id, isi, nama) {
                replyTarget = {
                    id,
                    isi,
                    nama
                };
                replyNama.textContent = 'Membalas ' + nama;
                replyIsi.textContent = isi || '[File]';
                replyPreview.classList.remove('hidden');
                input.focus();
            }

            function batalReply() {
                replyTarget = null;
                replyPreview.classList.add('hidden');
            }

            btnBatalReply.addEventListener('click', batalReply);

            inputFile.addEventListener('change', function() {
                const list = document.getElementById('file-preview-list');
                list.innerHTML = '';
                if (inputFile.files.length > 0) {
                    Array.from(inputFile.files).forEach(file => {
                        const div = document.createElement('div');
                        div.className = 'text-xs text-green-700 truncate';
                        div.textContent = '• ' + file.name;
                        list.appendChild(div);
                    });
                    filePreview.classList.remove('hidden');
                } else {
                    filePreview.classList.add('hidden');
                }
            });

            btnBatalFile.addEventListener('click', function() {
                inputFile.value = '';
                filePreview.classList.add('hidden');
            });

            function isGambarFile(tipe, nama) {
                if (tipe && tipe.startsWith('image/')) return true;
                if (nama) {
                    const ext = nama.split('.').pop().toLowerCase();
                    return ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext);
                }
                return false;
            }

            function isVideoFile(tipe, nama) {
                if (tipe && tipe.startsWith('video/')) return true;
                if (nama) {
                    const ext = nama.split('.').pop().toLowerCase();
                    return ['mp4', 'webm', 'ogg', 'mov', 'avi'].includes(ext);
                }
                return false;
            }

            function isAudioFile(tipe, nama) {
                if (tipe && tipe.startsWith('audio/')) return true;
                if (nama) {
                    const ext = nama.split('.').pop().toLowerCase();
                    return ['mp3', 'wav', 'aac', 'flac', 'm4a'].includes(ext);
                }
                return false;
            }

            document.querySelectorAll('.custom-audio-player').forEach(player => {
                const audio = player.querySelector('.custom-audio');
                audio.src = player.dataset.audioUrl;
                const playBtn = player.querySelector('.audio-play');
                const slider = player.querySelector('.audio-slider');
                const currentEl = player.querySelector('.audio-current');
                const durationEl = player.querySelector('.audio-duration');
                const menuToggle = player.querySelector('.audio-menu-toggle');
                const menu = player.querySelector('.message-context-menu');

                function formatTime(sec) {
                    const m = Math.floor(sec / 60);
                    const s = Math.floor(sec % 60);
                    return m + ':' + (s < 10 ? '0' : '') + s;
                }

                audio.addEventListener('loadedmetadata', () => {
                    durationEl.textContent = formatTime(audio.duration);
                });

                playBtn.addEventListener('click', () => {
                    if (audio.paused) {
                        audio.play();
                        playBtn.innerHTML = '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>';
                    } else {
                        audio.pause();
                        playBtn.innerHTML = '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>';
                    }
                });

                audio.addEventListener('timeupdate', () => {
                    if (audio.duration) {
                        slider.value = (audio.currentTime / audio.duration) * 100;
                        currentEl.textContent = formatTime(audio.currentTime);
                    }
                });

                slider.addEventListener('input', () => {
                    if (audio.duration) {
                        audio.currentTime = (slider.value / 100) * audio.duration;
                    }
                });

                audio.addEventListener('ended', () => {
                    playBtn.innerHTML = '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>';
                    slider.value = 0;
                    currentEl.textContent = '0:00';
                });

                menuToggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    document.querySelectorAll('.message-context-menu').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });
                    menu.classList.toggle('hidden');
                });
            });

            let voiceRecording = false;
            let mediaRecorder;
            let voiceChunks = [];
            let voiceBlob = null;

            window.toggleVoiceRecording = function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (voiceRecording) {
                    // Stop recording
                    if (mediaRecorder) {
                        mediaRecorder.stop();
                    }
                    voiceRecording = false;
                    // Change icon back to gray
                    const micIcon = document.getElementById('voice-mic');
                    if (micIcon) micIcon.classList.remove('text-red-500');
                } else {
                    // Start recording
                    navigator.mediaDevices.getUserMedia({
                            audio: true
                        })
                        .then(stream => {
                            mediaRecorder = new MediaRecorder(stream);
                            voiceChunks = [];
                            mediaRecorder.ondataavailable = e => voiceChunks.push(e.data);
                            mediaRecorder.onstop = function() {
                                stream.getTracks().forEach(track => track.stop());
                                voiceBlob = new Blob(voiceChunks, {
                                    type: 'audio/webm'
                                });
                                sendVoiceRecording();
                            };
                            mediaRecorder.start();
                            voiceRecording = true;
                            // Change icon color to red while recording
                            const micIcon = document.getElementById('voice-mic');
                            if (micIcon) micIcon.classList.add('text-red-500');
                        })
                        .catch(err => {
                            console.error('Microphone access denied', err);
                            alert('Tidak dapat mengakses mikrofon. Pastikan izin diberikan.');
                        });
                }
            };

            function sendVoiceRecording() {
                if (!voiceBlob) return;
                // Trigger form submission with voice data
                const form = document.getElementById('form-pesan');
                if (form) {
                    const event = new Event('submit', {
                        cancelable: true
                    });
                    form.dispatchEvent(event);
                }
            }

            function pasangCustomAudioPlayer(player) {
                const audio = player.querySelector('.custom-audio');
                const playBtn = player.querySelector('.audio-play');
                const slider = player.querySelector('.audio-slider');
                const currentEl = player.querySelector('.audio-current');
                const durationEl = player.querySelector('.audio-duration');
                const menuToggle = player.querySelector('.audio-menu-toggle');
                const menu = player.querySelector('.message-context-menu');
                audio.src = player.dataset.audioUrl;

                const formatTime = seconds => {
                    const minutes = Math.floor(seconds / 60);
                    const remainder = Math.floor(seconds % 60);
                    return `${minutes}:${remainder < 10 ? '0' : ''}${remainder}`;
                };
                const setPlayIcon = playing => {
                    playBtn.innerHTML = playing ? '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>' : '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>';
                };

                audio.addEventListener('loadedmetadata', () => {
                    durationEl.textContent = formatTime(audio.duration);
                });
                playBtn.addEventListener('click', () => audio.paused ? audio.play() : audio.pause());
                audio.addEventListener('play', () => setPlayIcon(true));
                audio.addEventListener('pause', () => setPlayIcon(false));
                audio.addEventListener('timeupdate', () => {
                    if (!audio.duration) return;
                    slider.value = audio.currentTime / audio.duration * 100;
                    currentEl.textContent = formatTime(audio.currentTime);
                });
                slider.addEventListener('input', () => {
                    if (audio.duration) audio.currentTime = slider.value / 100 * audio.duration;
                });
                audio.addEventListener('ended', () => {
                    slider.value = 0;
                    currentEl.textContent = '0:00';
                    setPlayIcon(false);
                });
                menuToggle.addEventListener('click', event => {
                    event.stopPropagation();
                    document.querySelectorAll('.message-context-menu').forEach(item => {
                        if (item !== menu) item.classList.add('hidden');
                    });
                    menu.classList.toggle('hidden');
                });
            }

            document.querySelectorAll('.custom-audio-player').forEach(pasangCustomAudioPlayer);

            function buatBubble(id, isiPesan, waktu, pengirimId, namaPengirim, balasKe, fileUrl, fileNama,
            fileTipe) {
                const milikSendiri = pengirimId === userId;

                const wrapper = document.createElement('div');
                wrapper.id = `pesan-${id}`;
                wrapper.className = 'w-full flex items-end gap-2 group ' +
                    (milikSendiri ? 'justify-end' : 'justify-start');
                wrapper.dataset.id = id;
                wrapper.dataset.isi = isiPesan || '';
                wrapper.dataset.nama = namaPengirim;
                wrapper.dataset.milikSendiri = milikSendiri ? '1' : '0';

                const btnReply = document.createElement('button');
                btnReply.className =
                    'btn-reply shrink-0 w-7 h-7 flex items-center justify-center rounded-full text-gray-400 opacity-0 group-hover:opacity-100 hover:text-blue-600 hover:bg-white transition text-base';
                btnReply.textContent = '↩';
                btnReply.addEventListener('click', () => setReply(id, isiPesan, namaPengirim));
                wrapper.appendChild(btnReply);

                const inner = document.createElement('div');
                inner.className = 'pesan-bubble max-w-xs sm:max-w-sm px-4 py-2.5 shadow-sm select-none ' +
                    (milikSendiri ? 'bg-blue-600 text-white rounded-2xl rounded-br-sm' :
                        'bg-white text-gray-800 border border-gray-200 rounded-2xl rounded-bl-sm');
                inner.style.webkitTouchCallout = 'none';
                inner.style.webkitUserSelect = 'none';
                inner.style.touchAction = 'pan-y';

                if (balasKe) {
                    const kutipan = document.createElement('div');
                    kutipan.className = 'mb-1.5 pl-2 py-1 border-l-2 rounded ' +
                        (milikSendiri ? 'border-blue-300 bg-blue-500/40' : 'border-gray-300 bg-gray-50') +
                        ' text-xs opacity-90';
                    const pNama = document.createElement('p');
                    pNama.className = 'font-semibold';
                    pNama.textContent = balasKe.pengirim_nama;
                    const pIsi = document.createElement('p');
                    pIsi.className = 'truncate';
                    pIsi.textContent = balasKe.isi_pesan || '[File]';
                    kutipan.appendChild(pNama);
                    kutipan.appendChild(pIsi);
                    inner.appendChild(kutipan);
                }

                if (fileUrl) {
                    if (isGambarFile(fileTipe, fileNama)) {
                        const a = document.createElement('a');
                        a.href = fileUrl;
                        a.target = '_blank';
                        const img = document.createElement('img');
                        img.src = fileUrl;
                        img.className = 'rounded-lg max-w-full mb-1.5';
                        img.alt = fileNama || '';
                        img.onerror = function() {
                            window.retryGambar(img);
                        };
                        a.appendChild(img);
                        inner.appendChild(a);
                    } else if (isVideoFile(fileTipe, fileNama)) {
                        const video = document.createElement('video');
                        video.src = fileUrl;
                        video.className = 'rounded-lg max-w-full mb-1.5';
                        video.controls = true;
                        video.preload = 'metadata';
                        inner.appendChild(video);
                    } else if (isAudioFile(fileTipe, fileNama)) {
                        const div = document.createElement('div');
                        div.innerHTML = `<div class="custom-audio-player flex items-center gap-3 bg-teal-800 text-white rounded-lg p-2 mt-1 relative w-64">
                            <div class="w-10 h-10 rounded-full bg-yellow-400 flex items-center justify-center text-teal-900 shrink-0"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9v7c0 1.1.9 2 2 2h4v-8H5v-1c0-3.87 3.13-7 7-7s7 3.13 7 7v1h-4v8h4c1.1 0 2-.9 2-2v-7c0-4.97-4.03-9-9-9z"/></svg></div>
                            <button type="button" class="audio-play hover:text-yellow-300"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></button>
                            <div class="flex-1"><input type="range" class="audio-slider w-full h-1 bg-teal-600 rounded-lg appearance-none cursor-pointer" value="0" min="0" max="100"><div class="flex justify-between text-[10px] opacity-80"><span class="audio-current">0:00</span><span class="audio-duration">0:00</span></div></div>
                            <button type="button" class="audio-menu-toggle px-1 hover:text-yellow-300"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg></button>
                            <audio class="custom-audio hidden"></audio>
                            <div class="message-context-menu hidden absolute top-full right-0 mt-1 w-48 rounded-lg bg-gray-900 text-white shadow-xl py-1 text-xs z-50">
                                <button type="button" data-context-action="info" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">ⓘ Info</button>
                                <button type="button" data-context-action="save" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700">⇩ Save</button>
                                <button type="button" data-context-action="delete" class="block w-full text-left px-3 py-1.5 hover:bg-gray-700 text-red-400">▢ Delete</button>
                            </div>
                        </div>`;
                        const player = div.firstElementChild;
                        player.dataset.audioUrl = fileUrl;
                        inner.appendChild(player);
                        pasangCustomAudioPlayer(player);
                    } else {
                        const a = document.createElement('a');
                        a.href = fileUrl;
                        a.target = '_blank';
                        a.download = fileNama || 'File';
                        a.className = 'flex items-center gap-2 mb-1.5 px-3 py-2 rounded-lg ' +
                            (milikSendiri ? 'bg-blue-700/60' : 'bg-gray-100');
                        const span1 = document.createElement('span');
                        span1.textContent = '';
                        const span2 = document.createElement('span');
                        span2.className = 'text-xs truncate';
                        span2.textContent = fileNama || 'File';
                        a.appendChild(span1);
                        a.appendChild(span2);
                        inner.appendChild(a);
                    }
                }

                if (isiPesan) {
                    const pContainer = document.createElement('div');
                    pContainer.className = 'flex justify-between items-start';

                    const p1 = document.createElement('p');
                    p1.className = 'text-sm leading-relaxed pesan-isi flex-1';
                    p1.textContent = isiPesan;
                    pContainer.appendChild(p1);

                    if (milikSendiri) {
                        const actions = document.createElement('div');
                        actions.className = 'relative message-actions shrink-0';

                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'edit-menu text-xs opacity-70 cursor-pointer px-1 select-none font-bold';
                        btn.title = 'Pilihan pesan';
                        btn.textContent = '⋮';

                        const menu = document.createElement('div');
                        menu.className =
                            'message-menu hidden absolute right-0 bottom-full mb-1 bg-white text-gray-700 rounded-lg shadow-lg border border-gray-200 z-20 w-24';

                        const btnEdit = document.createElement('button');
                        btnEdit.type = 'button';
                        btnEdit.className =
                            'message-edit block w-full text-left px-3 py-2 text-xs hover:bg-gray-100';
                        btnEdit.textContent = 'Edit';

                        const btnHapus = document.createElement('button');
                        btnHapus.type = 'button';
                        btnHapus.className =
                            'message-delete block w-full text-left px-3 py-2 text-xs text-red-600 hover:bg-gray-100';
                        btnHapus.textContent = 'Hapus';

                        menu.appendChild(btnEdit);
                        menu.appendChild(btnHapus);
                        actions.appendChild(btn);
                        actions.appendChild(menu);
                        pContainer.appendChild(actions);
                    }

                    inner.appendChild(pContainer);
                }

                const p2 = document.createElement('p');
                p2.className = 'text-[10px] opacity-70 mt-1 text-right';
                p2.textContent = waktu;
                if (milikSendiri) {
                    const statusBaca = document.createElement('span');
                    statusBaca.className = 'status-baca ml-1';
                    statusBaca.textContent = '✓';
                    p2.appendChild(statusBaca);
                }
                inner.appendChild(p2);

                wrapper.appendChild(inner);

                if (milikSendiri) {
                    pasangEventHapus(wrapper);
                } else {
                    pasangEventKlikKananReply(wrapper);
                }
                pasangEventSwipeReply(wrapper);

                chatBox.appendChild(wrapper);
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            async function hapusPesan(id) {
                if (!confirm('Hapus pesan ini?')) return;

                const socketId = window.Echo ? window.Echo.socketId() : '';

                await fetch(`/konsultasi/${konsultasiId}/pesan/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Socket-ID': socketId,
                    },
                });

                const el = document.getElementById(`pesan-${id}`);
                if (el) el.remove();
            }

            function pasangEventHapus(el) {
                let sudahDiproses = false;
                let timerTekan;

                function prosesHapus() {
                    if (sudahDiproses) return;
                    sudahDiproses = true;
                    hapusPesan(el.dataset.id);
                    setTimeout(() => {
                        sudahDiproses = false;
                    }, 1500);
                }

                el.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const menu = el.querySelector('.message-context-menu');
                    if (menu) {
                        document.querySelectorAll('.message-context-menu').forEach(item => item.classList.add('hidden'));
                        menu.classList.remove('hidden');
                    }
                });

                el.addEventListener('touchstart', function() {
                    timerTekan = setTimeout(() => {
                        const menu = el.querySelector('.message-context-menu');
                        if (menu) menu.classList.remove('hidden');
                    }, 600);
                }, {
                    passive: true
                });

                el.addEventListener('touchend', function() {
                    clearTimeout(timerTekan);
                });

                el.addEventListener('touchmove', function() {
                    clearTimeout(timerTekan);
                });
            }

            function pasangEventKlikKananReply(el) {
                el.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    setReply(el.dataset.id, el.dataset.isi, el.dataset.nama);
                });
            }

            function pasangEventSwipeReply(el) {
                const bubble = el.querySelector('.pesan-bubble');
                let startX = 0;
                let startY = 0;
                let geser = false;

                el.addEventListener('touchstart', function(e) {
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;
                    geser = false;
                }, {
                    passive: true
                });

                el.addEventListener('touchmove', function(e) {
                    const deltaX = e.touches[0].clientX - startX;
                    const deltaY = e.touches[0].clientY - startY;

                    if (Math.abs(deltaX) > Math.abs(deltaY) && deltaX > 10) {
                        geser = true;
                        const geseran = Math.min(deltaX, 60);
                        bubble.style.transform = `translateX(${geseran}px)`;
                    }
                }, {
                    passive: true
                });

                el.addEventListener('touchend', function() {
                    bubble.style.transform = '';
                    if (geser) {
                        setReply(el.dataset.id, el.dataset.isi, el.dataset.nama);
                    }
                    geser = false;
                });
            }

            document.querySelectorAll('[data-id]').forEach(function(el) {
                if (el.dataset.milikSendiri === '1') {
                    pasangEventHapus(el);
                } else {
                    pasangEventKlikKananReply(el);
                }
                pasangEventSwipeReply(el);

                const btnReply = el.querySelector('.btn-reply');
                if (btnReply) {
                    btnReply.addEventListener('click', () => setReply(el.dataset.id, el.dataset.isi, el
                        .dataset.nama));
                }
            });

            btnHapusSemua.addEventListener('click', async () => {
                if (!confirm(
                        'Hapus SEMUA chat di percakapan ini (hanya untuk kamu)? Tindakan ini tidak bisa dibatalkan.'
                        )) return;

                const socketId = window.Echo ? window.Echo.socketId() : '';

                await fetch(`/konsultasi/${konsultasiId}/pesan`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Socket-ID': socketId,
                    },
                });

                chatBox.innerHTML = '';
            });

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                if (statusSelesai) return;

                const isiPesan = input.value.trim();
                const files = Array.from(inputFile.files);
                
                // Jika tidak ada apa-apa, batalkan
                if (files.length === 0 && !isiPesan && !voiceBlob) return;

                const balasKeIdSaatIni = replyTarget ? replyTarget.id : null;
                const balasKeSaatIni = replyTarget ? {
                    isi_pesan: replyTarget.isi,
                    pengirim_nama: replyTarget.nama
                } : null;

                // Reset UI
                input.value = '';
                inputFile.value = '';
                filePreview.classList.add('hidden');
                document.getElementById('file-preview-list').innerHTML = '';
                batalReply();

                // Send voice (if any)
                if (voiceBlob) {
                    const formData = new FormData();
                    if (isiPesan) formData.append('isi_pesan', isiPesan);
                    if (balasKeIdSaatIni) formData.append('balas_ke_id', balasKeIdSaatIni);
                    formData.append('voice', voiceBlob, 'recording-' + Date.now() + '.webm');
                    voiceBlob = null;

                    const res = await fetch(`/konsultasi/${konsultasiId}/pesan`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Socket-ID': window.Echo ? window.Echo.socketId() : '',
                        },
                        body: formData,
                    });
                    const data = await res.json();
                    if (data.success) {
                        buatBubble(data.id, isiPesan, 'Baru saja', userId, namaSaya, data.balas_ke || balasKeSaatIni, data.voice_url, 'Rekaman suara', 'audio/webm');
                    }
                }

                // Send files one by one
                for (let i = 0; i < files.length; i++) {
                    const formData = new FormData();
                    // Hanya kirim pesan di request pertama jika tidak ada voice
                    if (i === 0 && !voiceBlob && isiPesan) formData.append('isi_pesan', isiPesan);
                    if (i === 0 && !voiceBlob && balasKeIdSaatIni) formData.append('balas_ke_id', balasKeIdSaatIni);
                    
                    formData.append('file', files[i]);

                    const res = await fetch(`/konsultasi/${konsultasiId}/pesan`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Socket-ID': window.Echo ? window.Echo.socketId() : '',
                        },
                        body: formData,
                    });

                    const data = await res.json();
                    if (data.success) {
                        buatBubble(data.id, (i === 0 && !voiceBlob ? isiPesan : ''), 'Baru saja', userId, namaSaya, (i === 0 && !voiceBlob ? (data.balas_ke || balasKeSaatIni) : null), data.file_url, data.file_nama, data.file_tipe);
                    }
                }
            });

            if (window.Echo) {
                window.Echo.private(`konsultasi.${konsultasiId}`)
                    .listen('.PesanDikirim', (e) => {
                        if (e.pengirim_id === userId) return;
                        buatBubble(e.id, e.isi_pesan, e.waktu, e.pengirim_id, e.pengirim_nama, e.balas_ke, e
                            .file_url || e.voice_url, e.file_nama || (e.voice_url ? 'Rekaman suara' : null),
                            e.file_tipe || (e.voice_url ? 'audio/webm' : null));
                    })
                    .listen('.PesanDibaca', (e) => {
                        if (e.pembaca_id === userId) return;
                        document.querySelectorAll('[data-milik-sendiri="1"] .status-baca').forEach(el => {
                            el.textContent = '✓✓';
                        });
                    });
            }

            function editPesan(pesanId, isiBaru) {
                return new Promise(async (resolve) => {
                    const socketId = window.Echo ? window.Echo.socketId() : '';
                    const res = await fetch(`/konsultasi/${konsultasiId}/pesan/${pesanId}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Socket-ID': socketId,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            isi_pesan: isiBaru
                        }),
                    });

                    const data = await res.json();
                    if (!res.ok || !data.success) {
                        alert('Gagal mengubah pesan: ' + (data.message || 'Kesalahan tidak diketahui'));
                        resolve(false);
                        return;
                    }

                    const wrapper = document.getElementById(`pesan-${pesanId}`);
                    if (wrapper) {
                        wrapper.dataset.isi = isiBaru;
                        const isiParagraf = wrapper.querySelector('.pesan-isi');
                        if (isiParagraf) isiParagraf.textContent = isiBaru;
                    }
                    resolve(true);
                });
            }

            function batalEdit() {
                editTarget = null;
                editPreview.classList.add('hidden');
                input.value = '';
                input.disabled = false;
                input.placeholder = 'Tulis pesan...';
            }

            document.addEventListener('click', async function(e) {
                const actionButton = e.target.closest('[data-context-action]');
                if (!actionButton) return;

                e.stopPropagation();
                const wrapper = actionButton.closest('[data-id]');
                const action = actionButton.dataset.contextAction;
                const menu = actionButton.closest('.message-context-menu');
                if (menu) menu.classList.add('hidden');
                if (!wrapper) return;

                const id = wrapper.dataset.id;
                const text = wrapper.dataset.isi || '';
                const audio = wrapper.querySelector('audio');
                const mediaUrl = audio?.src || wrapper.querySelector('a[download]')?.href;

                if (action === 'info') {
                    const response = await fetch(`/konsultasi/${konsultasiId}/pesan/${id}/info`);
                    const data = await response.json();
                    if (data.success) alert(`Pengirim: ${data.pesan.pengirim}\\nDikirim: ${data.pesan.waktu_kirim}\\nDibaca: ${data.pesan.dibaca_pada || 'Belum dibaca'}`);
                } else if (action === 'reply') {
                    setReply(id, text, wrapper.dataset.nama);
                } else if (action === 'copy') {
                    if (text) await navigator.clipboard.writeText(text);
                } else if (action === 'pin' || action === 'star') {
                    const response = await fetch(`/konsultasi/${konsultasiId}/pesan/${id}/${action}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } });
                    const data = await response.json();
                    if (data.success) alert(`${action === 'pin' ? 'Pin' : 'Star'} ${data[`is_${action === 'pin' ? 'pinned' : 'starred'}`] ? 'aktif' : 'dinonaktifkan'}.`);
                } else if (action === 'select') {
                    wrapper.classList.toggle('ring-2');
                    wrapper.classList.toggle('ring-blue-400');
                } else if (action === 'save' && mediaUrl) {
                    const link = document.createElement('a');
                    link.href = mediaUrl;
                    link.download = 'rekaman-suara';
                    link.click();
                } else if (action === 'share' && navigator.share) {
                    await navigator.share({ text: text || 'Pesan dari konsultasi' });
                } else if (action === 'open' && mediaUrl) {
                    window.open(mediaUrl, '_blank');
                } else if (action === 'delete') {
                    hapusPesan(id);
                }
            });

            // Event delegation untuk semua tombol menu pesan
            document.addEventListener('click', function(e) {
                const editMenu = e.target.closest('.edit-menu');
                if (editMenu) {
                    e.stopPropagation();
                    const wrapper = editMenu.closest('[data-id]');
                    if (!wrapper) return;

                    const menu = wrapper.querySelector('.message-menu');
                    if (menu) {
                        menu.classList.toggle('hidden');
                        e.preventDefault();
                    }
                    return;
                }

                if (e.target.classList.contains('message-edit')) {
                    e.stopPropagation();
                    const wrapper = e.target.closest('[data-id]');
                    if (!wrapper) return;

                    const menu = wrapper.querySelector('.message-menu');
                    if (menu) menu.classList.add('hidden');

                    const isiLama = wrapper.dataset.isi || '';
                    const isiBaru = prompt('Edit pesan:', isiLama);
                    if (isiBaru !== null && isiBaru.trim() !== '' && isiBaru.trim() !== isiLama) {
                        editPesan(wrapper.dataset.id, isiBaru.trim());
                    }
                    return;
                }

                if (e.target.classList.contains('message-delete')) {
                    e.stopPropagation();
                    const wrapper = e.target.closest('[data-id]');
                    if (!wrapper) return;

                    if (!confirm('Hapus pesan ini?')) return;

                    const menu = wrapper.querySelector('.message-menu');
                    if (menu) menu.classList.add('hidden');

                    hapusPesan(wrapper.dataset.id);
                    return;
                }

                if (e.target.classList.contains('btn-voice-delete')) {
                    e.stopPropagation();
                    const wrapper = e.target.closest('[data-id]');
                    if (!wrapper) return;

                    if (!confirm('Hapus rekaman suara ini?')) return;

                    hapusPesan(wrapper.dataset.id);
                    return;
                }

                // Tutup menu saat klik di luar
                document.querySelectorAll('.message-menu').forEach(menu => {
                    if (!menu.contains(e.target) && !e.target.classList.contains('edit-menu')) {
                        menu.classList.add('hidden');
                    }
                });
            });
        });
    </script>
@endpush
