<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    Daftar Surat Saya
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Seluruh riwayat dan status permohonan surat administrasi milik Anda
                </p>
            </div>
            <div>
                <a href="{{ route('mahasiswa.surat.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Ajukan Surat Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <!-- Filter & Search Bar -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                <form method="GET" action="{{ route('mahasiswa.surat.index') }}" class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                        <a href="{{ route('mahasiswa.surat.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                            Semua
                        </a>
                        <a href="{{ route('mahasiswa.surat.index', ['status' => 'menunggu']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'menunggu' ? 'bg-amber-600 text-white' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 hover:bg-amber-100' }}">
                            Menunggu Verifikasi
                        </a>
                        <a href="{{ route('mahasiswa.surat.index', ['status' => 'diproses']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'diproses' ? 'bg-blue-600 text-white' : 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 hover:bg-blue-100' }}">
                            Sedang Diproses
                        </a>
                        <a href="{{ route('mahasiswa.surat.index', ['status' => 'disetujui']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'disetujui' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100' }}">
                            Disetujui
                        </a>
                        <a href="{{ route('mahasiswa.surat.index', ['status' => 'ditolak']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'ditolak' ? 'bg-rose-600 text-white' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 hover:bg-rose-100' }}">
                            Ditolak
                        </a>
                    </div>

                    <div class="w-full md:w-72">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari surat / keperluan..." class="w-full pl-9 pr-4 py-1.5 text-xs rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3.5">ID / No. Surat</th>
                                <th class="px-6 py-3.5">Jenis Surat</th>
                                <th class="px-6 py-3.5">Keperluan & Keterangan</th>
                                <th class="px-6 py-3.5">Tanggal Pengajuan</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($surats as $surat)
                                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-4 font-mono text-xs">
                                        <span class="font-bold text-gray-900 dark:text-white">#{{ $surat->id }}</span>
                                        @if($surat->nomor_surat)
                                            <div class="text-[11px] text-indigo-600 dark:text-indigo-400 font-medium">{{ $surat->nomor_surat }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $surat->jenis_surat }}</div>
                                        @if($surat->lampiran)
                                            <span class="inline-flex items-center gap-1 text-[11px] text-gray-400 mt-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                                Ada Lampiran
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 max-w-sm">
                                        <p class="text-xs text-gray-700 dark:text-gray-300 line-clamp-2">{{ $surat->keperluan }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-xs whitespace-nowrap">
                                        {{ $surat->created_at->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $surat->status_badge_class }}">
                                            {{ $surat->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                                        <a href="{{ route('mahasiswa.surat.show', $surat) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 rounded-lg transition">
                                            Detail & Status
                                        </a>

                                        @if($surat->status === 'menunggu')
                                            <form action="{{ route('mahasiswa.surat.destroy', $surat) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan surat ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 rounded-lg transition">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <p class="text-sm">Tidak ada permohonan surat yang ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($surats->hasPages())
                    <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                        {{ $surats->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
