<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Banner --}}
            <div class="bg-[#00C2FF] text-white rounded-2xl shadow-md px-6 py-4 flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold">Kelola Akun Pengguna</h1>
                    <p class="text-lg">Kelola Data dan Akun Pengguna</p>
                </div>
                <span class="font-bold text-lg">{{ now()->locale('id')->translatedFormat('d F Y') }}</span>
            </div>

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
            @endif

            {{-- Card utama --}}
            <div class="bg-white rounded-2xl shadow-md p-5 min-h-[420px]">

                {{-- Search --}}
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-3 mb-4">
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama, email......"
                               class="w-64 pl-9 pr-3 py-2 text-xs border border-gray-500 rounded-sm focus:ring-0 focus:border-blue-500">
                    </div>
                    <button type="submit" class="bg-[#0A84FF] hover:bg-blue-600 text-white font-bold px-5 py-2 rounded-lg">
                        cari
                    </button>
                </form>

                {{-- Tabel --}}
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#8FB4E0] text-white text-base font-bold">
                            <th class="px-4 py-2 text-center rounded-l-lg w-16">No</th>
                            <th class="px-4 py-2 text-left">Nama</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-center">Role</th>
                            <th class="px-4 py-2 text-center">Status</th>
                            <th class="px-4 py-2 text-center rounded-r-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $u)
                            @php
                                $aktif = $u->is_active ?? true;
                                $role  = strtolower($u->role);
                                $roleStyle = [
                                    'konsultan' => 'bg-[#D6E6FA] text-[#2B6CC4]',
                                    'audiens'   => 'bg-[#EADCF8] text-[#7B3FBF]',
                                ];
                            @endphp
                            <tr class="border-b border-blue-300">
                                <td class="px-4 py-3 text-center font-bold text-gray-500">{{ $users->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $u->name }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $u->email }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block font-bold text-xs px-4 py-1 rounded-full {{ $roleStyle[$role] ?? 'bg-gray-200 text-gray-600' }}">
                                        {{ ucfirst($role) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($aktif)
                                        <span class="inline-block bg-green-200 text-green-600 font-bold text-xs px-5 py-1 rounded-full">Aktif</span>
                                    @else
                                        <span class="inline-block bg-red-100 text-red-600 font-bold text-xs px-4 py-1 rounded-full">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.users.edit', $u->id) }}"
                                       class="inline-block bg-[#0A84FF] hover:bg-blue-600 text-white font-bold px-4 py-1 rounded">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-gray-400">Belum ada pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>