<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    Dashboard Petugas Administrasi
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kelola antrean permohonan surat masuk mahasiswa Fakultas Teknologi
                </p>
            </div>
            <div>
                <a href="{{ route('petugas.surat.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Lihat Semua Antrean Surat
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Masuk</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</p>
                    <span class="text-[11px] text-gray-400 mt-1 block">Seluruh permohonan mahasiswa</span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-amber-200 dark:border-amber-800/60 shadow-sm bg-amber-50/20">
                    <p class="text-xs font-semibold text-amber-600 dark:text-amber-400">Perlu Verifikasi</p>
                    <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-2">{{ $stats['menunggu'] }}</p>
                    <span class="text-[11px] text-amber-600/80 mt-1 block font-medium">Status: Menunggu</span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-blue-200 dark:border-blue-800/60 shadow-sm bg-blue-50/20">
                    <p class="text-xs font-semibold text-blue-600 dark:text-blue-400">Sedang Diproses</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ $stats['diproses'] }}</p>
                    <span class="text-[11px] text-blue-600/80 mt-1 block font-medium">Draft & Pengajuan Kabag</span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-emerald-200 dark:border-emerald-800/60 shadow-sm">
                    <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Disetujui Selesai</p>
                    <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">{{ $stats['disetujui'] }}</p>
                    <span class="text-[11px] text-gray-400 mt-1 block">Telah disahkan Kabag</span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                    <p class="text-xs font-medium text-rose-600 dark:text-rose-400">Ditolak</p>
                    <p class="text-3xl font-bold text-rose-600 dark:text-rose-400 mt-2">{{ $stats['ditolak'] }}</p>
                    <span class="text-[11px] text-gray-400 mt-1 block">Berkas tidak memenuhi</span>
                </div>
            </div>

            <!-- Antrean Perlu Tindakan -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Antrean Masuk Yang Memerlukan Tindakan</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Surat dengan status "Menunggu" atau "Sedang Diproses"</p>
                    </div>
                    <a href="{{ route('petugas.surat.index', ['status' => 'menunggu']) }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                        Buka Antrean Menunggu &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3.5">ID</th>
                                <th class="px-6 py-3.5">Nama & NIM Mahasiswa</th>
                                <th class="px-6 py-3.5">Jenis Surat</th>
                                <th class="px-6 py-3.5">Tgl Diajukan</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($antreanTerbaru as $surat)
                                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-4 font-mono font-bold text-xs text-gray-900 dark:text-white">
                                        #{{ $surat->id }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $surat->mahasiswa->name }}</div>
                                        <div class="text-xs text-gray-500">NIM: {{ $surat->mahasiswa->nim ?? '-' }} ({{ $surat->mahasiswa->prodi ?? '-' }})</div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-800 dark:text-gray-200">
                                        {{ $surat->jenis_surat }}
                                    </td>
                                    <td class="px-6 py-4 text-xs whitespace-nowrap">
                                        {{ $surat->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $surat->status_badge_class }}">
                                            {{ $surat->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('petugas.surat.show', $surat) }}" class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">
                                            Verifikasi & Proses
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <p class="text-sm">Tidak ada antrean surat yang menunggu tindakan saat ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
