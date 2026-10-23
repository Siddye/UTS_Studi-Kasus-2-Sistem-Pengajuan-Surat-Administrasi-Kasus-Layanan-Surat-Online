<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    Dashboard Mahasiswa
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Selamat datang, <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ Auth::user()->name }}</span> (NIM: {{ Auth::user()->nim ?? '-' }} | Prodi: {{ Auth::user()->prodi ?? '-' }})
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

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Pengajuan</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</p>
                    <span class="text-[11px] text-gray-400 mt-1 block">Semua surat yang pernah diajukan</span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                    <p class="text-xs font-medium text-amber-600 dark:text-amber-400">Menunggu</p>
                    <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-2">{{ $stats['menunggu'] }}</p>
                    <span class="text-[11px] text-gray-400 mt-1 block">Belum diproses petugas</span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                    <p class="text-xs font-medium text-blue-600 dark:text-blue-400">Sedang Diproses</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ $stats['diproses'] }}</p>
                    <span class="text-[11px] text-gray-400 mt-1 block">Verifikasi tata usaha</span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                    <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Disetujui</p>
                    <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">{{ $stats['disetujui'] }}</p>
                    <span class="text-[11px] text-gray-400 mt-1 block">Resmi disetujui Kabag</span>
                </div>
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                    <p class="text-xs font-medium text-rose-600 dark:text-rose-400">Ditolak</p>
                    <p class="text-3xl font-bold text-rose-600 dark:text-rose-400 mt-2">{{ $stats['ditolak'] }}</p>
                    <span class="text-[11px] text-gray-400 mt-1 block">Perlu perbaikan berkas</span>
                </div>
            </div>

            <!-- Recent Letters Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pengajuan Surat Terakhir</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Daftar permohonan surat terbaru milik Anda</p>
                    </div>
                    <a href="{{ route('mahasiswa.surat.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                        Lihat Semua Surat &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3.5">ID / No. Surat</th>
                                <th class="px-6 py-3.5">Jenis Surat</th>
                                <th class="px-6 py-3.5">Keperluan</th>
                                <th class="px-6 py-3.5">Tgl Pengajuan</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @forelse($recentSurats as $surat)
                                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-4 font-mono text-xs">
                                        <span class="font-bold text-gray-900 dark:text-white">#{{ $surat->id }}</span>
                                        @if($surat->nomor_surat)
                                            <div class="text-[11px] text-gray-500 dark:text-gray-400">{{ $surat->nomor_surat }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        {{ $surat->jenis_surat }}
                                    </td>
                                    <td class="px-6 py-4 max-w-xs truncate">
                                        {{ $surat->keperluan }}
                                    </td>
                                    <td class="px-6 py-4 text-xs whitespace-nowrap">
                                        {{ $surat->created_at->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $surat->status_badge_class }}">
                                            {{ $surat->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('mahasiswa.surat.show', $surat) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 rounded-lg transition">
                                            Detail & Status
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <p class="text-sm">Belum ada permohonan surat yang diajukan.</p>
                                        <a href="{{ route('mahasiswa.surat.create') }}" class="inline-block mt-3 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                            + Ajukan permohonan surat sekarang
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Security & Privacy Card -->
            <div class="p-5 rounded-2xl bg-indigo-50/70 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex items-start gap-4">
                <div class="p-2.5 rounded-xl bg-indigo-600 text-white shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-indigo-950 dark:text-indigo-200">Keamanan & Kebijakan Kepemilikan Data (Data Ownership Authorization)</h4>
                    <p class="text-xs text-indigo-800/80 dark:text-indigo-300/80 mt-1 leading-relaxed">
                        Sistem ini menerapkan <code>SuratPolicy</code> dan pemeriksaan <code>user_id</code> di sisi backend. Setiap mahasiswa hanya memiliki hak untuk mengakses dan memantau status permohonan surat miliknya sendiri. Upaya membuka atau memanipulasi ID surat orang lain melalui URL browser akan langsung dicegah dengan respon <strong>HTTP 403 Forbidden</strong>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
