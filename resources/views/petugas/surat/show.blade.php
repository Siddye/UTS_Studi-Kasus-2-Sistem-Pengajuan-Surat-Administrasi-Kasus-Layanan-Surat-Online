<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('petugas.surat.index') }}" class="p-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                        Pemeriksaan Berkas Surat #{{ $surat->id }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Pemohon: <span class="font-semibold text-gray-900 dark:text-white">{{ $surat->mahasiswa->name }}</span> ({{ $surat->mahasiswa->nim ?? '-' }})
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
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Data Pemohon & Pengajuan (2 Cols) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Biodata Mahasiswa -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Informasi Mahasiswa Pemohon</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-xs text-gray-400 block">Nama Lengkap</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $surat->mahasiswa->name }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block">Nomor Induk Mahasiswa (NIM)</span>
                                <span class="font-bold text-gray-900 dark:text-white font-mono">{{ $surat->mahasiswa->nim ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block">Program Studi</span>
                                <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $surat->mahasiswa->prodi ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block">Email Kampus</span>
                                <span class="text-gray-800 dark:text-gray-200">{{ $surat->mahasiswa->email }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Permohonan -->
                    <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm space-y-5">
                        <div>
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Jenis Surat</span>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $surat->jenis_surat }}</h3>
                        </div>

                        <div>
                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Keperluan</h4>
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
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Berkas Lampiran Mahasiswa</h4>
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $surat->lampiran) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 dark:hover:bg-blue-900/60 rounded-xl transition border border-blue-200 dark:border-blue-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        Buka Lampiran Mahasiswa
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($surat->catatan_kepala_bagian)
                            <div class="p-4 rounded-xl {{ $surat->status === 'ditolak' ? 'bg-rose-50 dark:bg-rose-950/30 border-rose-200' : 'bg-purple-50 dark:bg-purple-950/30 border-purple-200' }} border">
                                <h4 class="text-xs font-bold uppercase tracking-wider {{ $surat->status === 'ditolak' ? 'text-rose-800' : 'text-purple-800' }}">Catatan Keputusan Kepala Bagian</h4>
                                <p class="text-xs mt-1 text-gray-700 dark:text-gray-300">{{ $surat->catatan_kepala_bagian }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Form Tindakan Petugas (1 Col) -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Proses Permohonan</h3>
                                <p class="text-[11px] text-gray-400">Tindakan Petugas Administrasi</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('petugas.surat.proses', $surat) }}" class="space-y-4">
                            @csrf

                            <div>
                                <label for="status" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                    Tentukan Status Proses <span class="text-rose-500">*</span>
                                </label>
                                <select id="status" name="status" required class="w-full text-xs rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-blue-500 py-2">
                                    <option value="diproses" {{ (old('status', $surat->status) === 'diproses' || $surat->status === 'menunggu') ? 'selected' : '' }}>
                                        Terima & Proses (Siapkan ke Kabag)
                                    </option>
                                    <option value="ditolak" {{ old('status', $surat->status) === 'ditolak' ? 'selected' : '' }}>
                                        Tolak Berkas (Tidak Memenuhi Syarat)
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label for="nomor_surat" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                    Draft Nomor Surat (Opsional)
                                </label>
                                <input type="text" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $surat->nomor_surat) }}" placeholder="Contoh: 014/FT-UNIV/AKD/2026" class="w-full text-xs rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-blue-500 py-2 font-mono">
                                <span class="text-[10px] text-gray-400 block mt-1">Dapat diisi sekarang atau disempurnakan oleh Kabag.</span>
                            </div>

                            <div>
                                <label for="catatan_petugas" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                    Catatan Petugas <span class="text-rose-500">*</span>
                                </label>
                                <textarea id="catatan_petugas" name="catatan_petugas" rows="3" required placeholder="Catatan hasil verifikasi berkas mahasiswa..." class="w-full text-xs rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-blue-500">{{ old('catatan_petugas', $surat->catatan_petugas ?? 'Berkas mahasiswa telah diverifikasi valid dan lengkap. Siap diteruskan untuk pengesahan Kepala Bagian.') }}</textarea>
                                <x-input-error :messages="$errors->get('catatan_petugas')" class="mt-1" />
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                                Simpan & Perbarui Status
                            </button>
                        </form>
                    </div>

                    @if($surat->petugas)
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 text-xs">
                            <span class="text-gray-400 block">Terakhir diproses oleh:</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $surat->petugas->name }}</span>
                            <span class="text-gray-400 block mt-0.5">{{ $surat->tanggal_diproses?->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
