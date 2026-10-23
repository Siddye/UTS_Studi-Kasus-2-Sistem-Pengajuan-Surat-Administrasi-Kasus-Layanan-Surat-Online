<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('mahasiswa.surat.index') }}" class="p-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                        Detail Permohonan Surat #{{ $surat->id }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Diajukan pada {{ $surat->created_at->translatedFormat('l, d F Y - H:i') }} WIB
                    </p>
                </div>
            </div>

            <div>
                <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold shadow-sm {{ $surat->status_badge_class }}">
                    Status: {{ $surat->status_label }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <!-- Progress Stepper -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-6">Alur Proses Permohonan Surat</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 relative">
                    <!-- Step 1 -->
                    <div class="p-4 rounded-xl border {{ in_array($surat->status, ['menunggu', 'diproses', 'disetujui']) ? 'border-emerald-500/50 bg-emerald-50/40 dark:bg-emerald-950/20' : 'border-gray-200 dark:border-gray-700' }}">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs">
                                1
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Permohonan Masuk</h4>
                                <p class="text-[11px] text-gray-500">{{ $surat->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">Surat berhasil diajukan oleh mahasiswa.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="p-4 rounded-xl border {{ in_array($surat->status, ['diproses', 'disetujui']) ? 'border-blue-500/50 bg-blue-50/40 dark:bg-blue-950/20' : 'border-gray-200 dark:border-gray-700' }}">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full {{ in_array($surat->status, ['diproses', 'disetujui']) ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500' }} flex items-center justify-center font-bold text-xs">
                                2
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Verifikasi Petugas</h4>
                                <p class="text-[11px] text-gray-500">
                                    {{ $surat->tanggal_diproses ? $surat->tanggal_diproses->format('d M Y, H:i') : 'Menunggu antrean' }}
                                </p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">Pemeriksaan berkas & penyiapan draft surat.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="p-4 rounded-xl border {{ $surat->status === 'disetujui' ? 'border-emerald-500/50 bg-emerald-50/40 dark:bg-emerald-950/20' : ($surat->status === 'ditolak' ? 'border-rose-500/50 bg-rose-50/40 dark:bg-rose-950/20' : 'border-gray-200 dark:border-gray-700') }}">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full {{ $surat->status === 'disetujui' ? 'bg-emerald-600 text-white' : ($surat->status === 'ditolak' ? 'bg-rose-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500') }} flex items-center justify-center font-bold text-xs">
                                3
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Persetujuan Kabag</h4>
                                <p class="text-[11px] text-gray-500">
                                    {{ $surat->tanggal_selesai ? $surat->tanggal_selesai->format('d M Y, H:i') : 'Menunggu persetujuan' }}
                                </p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">
                            {{ $surat->status === 'disetujui' ? 'Telah disetujui & nomor surat diterbitkan.' : ($surat->status === 'ditolak' ? 'Permohonan ditolak.' : 'Persetujuan resmi Kepala Bagian.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detail Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Main Letter Info -->
                <div class="md:col-span-2 bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm space-y-6">
                    <div>
                        <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">Jenis Dokumen Layanan</span>
                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $surat->jenis_surat }}</h3>
                        
                        @if($surat->nomor_surat)
                            <div class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-mono text-xs font-semibold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                Nomor Resmi: {{ $surat->nomor_surat }}
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-700 pt-5 space-y-4">
                        <div>
                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Keperluan Pengajuan</h4>
                            <p class="text-sm text-gray-800 dark:text-gray-200 mt-1 leading-relaxed bg-gray-50 dark:bg-gray-900/40 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                                {{ $surat->keperluan }}
                            </p>
                        </div>

                        @if($surat->keterangan_tambahan)
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Keterangan Tambahan / Instansi</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 leading-relaxed bg-gray-50 dark:bg-gray-900/40 p-3.5 rounded-xl border border-gray-100 dark:border-gray-800">
                                    {{ $surat->keterangan_tambahan }}
                                </p>
                            </div>
                        @endif

                        @if($surat->lampiran)
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Berkas Lampiran</h4>
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $surat->lampiran) }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-medium text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 rounded-xl transition border border-indigo-200 dark:border-indigo-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        Buka / Unduh Berkas Lampiran
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Cancel Button if waiting -->
                    @if($surat->status === 'menunggu')
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-5">
                            <form action="{{ route('mahasiswa.surat.destroy', $surat) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan dan menghapus pengajuan surat ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-xl transition">
                                    Batalkan Permohonan Ini
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Sidebar Feedback & Notes -->
                <div class="space-y-6">
                    <!-- Verifikasi Petugas Note -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-6 h-6 rounded-lg bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-300 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Catatan Petugas TU</h4>
                        </div>
                        @if($surat->catatan_petugas)
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed bg-blue-50/50 dark:bg-blue-950/30 p-3 rounded-xl border border-blue-100 dark:border-blue-900/40">
                                {{ $surat->catatan_petugas }}
                            </p>
                            @if($surat->petugas)
                                <p class="text-[11px] text-gray-400 mt-2">Diverifikasi oleh: {{ $surat->petugas->name }}</p>
                            @endif
                        @else
                            <p class="text-xs text-gray-400 italic">Belum ada catatan dari petugas administrasi.</p>
                        @endif
                    </div>

                    <!-- Keputusan Kabag Note -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-6 h-6 rounded-lg bg-purple-100 dark:bg-purple-900/60 text-purple-600 dark:text-purple-300 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            </div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Keputusan Kepala Bagian</h4>
                        </div>
                        @if($surat->catatan_kepala_bagian)
                            <p class="text-xs {{ $surat->status === 'ditolak' ? 'text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800' : 'text-purple-700 dark:text-purple-300 bg-purple-50/50 dark:bg-purple-950/30 border-purple-100 dark:border-purple-900/40' }} leading-relaxed p-3 rounded-xl border">
                                {{ $surat->catatan_kepala_bagian }}
                            </p>
                            @if($surat->kepalaBagian)
                                <p class="text-[11px] text-gray-400 mt-2">Oleh: {{ $surat->kepalaBagian->name }}</p>
                            @endif
                        @else
                            <p class="text-xs text-gray-400 italic">Belum ada keputusan resmi dari Kepala Bagian.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
