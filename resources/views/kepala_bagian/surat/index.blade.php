<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    Persetujuan Surat Administrasi
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Daftar permohonan surat mahasiswa yang memerlukan otorisasi Kepala Bagian
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <!-- Filter & Search Bar -->
            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                <form method="GET" action="{{ route('kepala-bagian.surat.index') }}" class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                        <a href="{{ route('kepala-bagian.surat.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ !request('status') ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}">
                            Semua
                        </a>
                        <a href="{{ route('kepala-bagian.surat.index', ['status' => 'diproses']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'diproses' ? 'bg-purple-600 text-white' : 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 hover:bg-purple-100' }}">
                            Perlu Persetujuan (Diproses)
                        </a>
                        <a href="{{ route('kepala-bagian.surat.index', ['status' => 'disetujui']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'disetujui' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100' }}">
                            Telah Disetujui
                        </a>
                        <a href="{{ route('kepala-bagian.surat.index', ['status' => 'ditolak']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'ditolak' ? 'bg-rose-600 text-white' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 hover:bg-rose-100' }}">
                            Ditolak
                        </a>
                    </div>

                    <div class="w-full md:w-80">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pemohon, NIM, surat..." class="w-full pl-9 pr-4 py-1.5 text-xs rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-purple-500 focus:border-purple-500">
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
                                <th class="px-6 py-3.5">Mahasiswa</th>
                                <th class="px-6 py-3.5">Jenis Dokumen</th>
                                <th class="px-6 py-3.5">Verifikator Petugas</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Otorisasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($surats as $surat)
                                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-4 font-mono text-xs">
                                        <span class="font-bold text-gray-900 dark:text-white">#{{ $surat->id }}</span>
                                        @if($surat->nomor_surat)
                                            <div class="text-[11px] text-purple-600 dark:text-purple-400 font-medium">{{ $surat->nomor_surat }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $surat->mahasiswa->name }}</div>
                                        <div class="text-xs text-gray-500">NIM: {{ $surat->mahasiswa->nim ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $surat->jenis_surat }}</div>
                                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ $surat->keperluan }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        @if($surat->petugas)
                                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $surat->petugas->name }}</span>
                                            <div class="text-[11px] text-gray-400">{{ $surat->tanggal_diproses?->format('d M Y') }}</div>
                                        @else
                                            <span class="text-gray-400 italic">Belum diproses</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $surat->status_badge_class }}">
                                            {{ $surat->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('kepala-bagian.surat.show', $surat) }}" class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold text-white bg-purple-600 hover:bg-purple-700 rounded-lg shadow-sm transition">
                                            Evaluasi & Sahkan &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <p class="text-sm">Tidak ada permohonan surat ditemukan.</p>
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
