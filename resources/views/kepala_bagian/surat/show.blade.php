<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('kepala-bagian.surat.index') }}" class="p-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                        Otorisasi Permohonan Surat #{{ $surat->id }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Pemohon: <span class="font-semibold text-gray-900 dark:text-white">{{ $surat->mahasiswa->name }}</span> (NIM: {{ $surat->mahasiswa->nim ?? '-' }})
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
                <!-- Info Berkas & Dokumen (2 Cols) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Mahasiswa & Dokumen Info -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm space-y-5">
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4">
                            <div>
                                <span class="text-xs font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wider">Jenis Permohonan</span>
                                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mt-1">{{ $surat->jenis_surat }}</h3>
                            </div>
                            @if($surat->nomor_surat)
                                <div class="text-right">
                                    <span class="text-xs text-gray-400 block">Nomor Surat Resmi</span>
                                    <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400 text-sm">{{ $surat->nomor_surat }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-gray-400 block">Nama Mahasiswa:</span>
                                <span class="font-bold text-gray-900 dark:text-white text-sm">{{ $surat->mahasiswa->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block">NIM & Program Studi:</span>
                                <span class="font-bold text-gray-900 dark:text-white text-sm">{{ $surat->mahasiswa->nim ?? '-' }} ({{ $surat->mahasiswa->prodi ?? '-' }})</span>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Keperluan Pengajuan</h4>
                            <p class="text-sm text-gray-800 dark:text-gray-200 mt-1 leading-relaxed bg-gray-50 dark:bg-gray-900/40 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                                {{ $surat->keperluan }}
                            </p>
                        </div>

                        @if($surat->keterangan_tambahan)
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Keterangan Tambahan / Instansi Tujuan</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 leading-relaxed bg-gray-50 dark:bg-gray-900/40 p-3 rounded-xl border border-gray-100 dark:border-gray-800">
                                    {{ $surat->keterangan_tambahan }}
                                </p>
                            </div>
                        @endif

                        @if($surat->lampiran)
                            <div>
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Lampiran Pendukung</h4>
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $surat->lampiran) }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-950/60 hover:bg-purple-100 dark:hover:bg-purple-900/60 rounded-xl transition border border-purple-200 dark:border-purple-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        Buka Lampiran Berkas
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Catatan Petugas TU -->
                        <div class="p-4 rounded-xl bg-blue-50/60 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/40">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-blue-900 dark:text-blue-300 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Hasil Verifikasi Petugas Tata Usaha
                            </h4>
                            <p class="text-xs text-blue-950 dark:text-blue-200 mt-1 leading-relaxed">
                                {{ $surat->catatan_petugas ?? 'Petugas belum menyertakan catatan verifikasi.' }}
                            </p>
                            @if($surat->petugas)
                                <p class="text-[11px] text-blue-600 dark:text-blue-400 mt-2">Diverifikasi oleh: {{ $surat->petugas->name }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Official Document Preview if Approved -->
                    @if($surat->status === 'disetujui')
                        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl border-2 border-emerald-300 dark:border-emerald-700 shadow-sm relative overflow-hidden">
                            <div class="absolute -right-12 -top-12 w-36 h-36 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center pointer-events-none opacity-40">
                                <svg class="w-20 h-20 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>

                            <div class="text-center border-b-2 border-gray-900 dark:border-gray-100 pb-4 mb-6">
                                <h3 class="font-serif font-bold text-lg text-gray-900 dark:text-gray-100 uppercase tracking-widest">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h3>
                                <h4 class="font-serif font-extrabold text-xl text-gray-900 dark:text-gray-100 tracking-wide mt-1">FAKULTAS TEKNOLOGI</h4>
                                <p class="text-[11px] text-gray-500 font-serif">Jl. Kampus Terpadu No. 123, Telp. (021) 555-0199 | Laman: teknologi.ac.id</p>
                            </div>

                            <div class="text-center mb-6">
                                <h5 class="font-bold underline text-sm uppercase text-gray-900 dark:text-white">{{ $surat->jenis_surat }}</h5>
                                <p class="font-mono text-xs text-gray-600 dark:text-gray-300 mt-0.5">Nomor: {{ $surat->nomor_surat }}</p>
                            </div>

                            <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed indent-8">
                                Yang bertanda tangan di bawah ini Kepala Bagian Tata Usaha Fakultas Teknologi menerangkan dengan sebenarnya bahwa:
                            </p>

                            <div class="my-4 text-xs space-y-1.5 pl-8">
                                <div class="grid grid-cols-3">
                                    <span class="text-gray-500">Nama Lengkap</span>
                                    <span class="col-span-2 font-bold text-gray-900 dark:text-white">: {{ $surat->mahasiswa->name }}</span>
                                </div>
                                <div class="grid grid-cols-3">
                                    <span class="text-gray-500">NIM</span>
                                    <span class="col-span-2 font-mono font-bold text-gray-900 dark:text-white">: {{ $surat->mahasiswa->nim ?? '-' }}</span>
                                </div>
                                <div class="grid grid-cols-3">
                                    <span class="text-gray-500">Program Studi</span>
                                    <span class="col-span-2 font-semibold text-gray-900 dark:text-white">: {{ $surat->mahasiswa->prodi ?? '-' }}</span>
                                </div>
                            </div>

                            <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed indent-8">
                                Adalah benar mahasiswa aktif pada Fakultas Teknologi. Surat keterangan ini diterbitkan untuk dipergunakan sebagai: <em>{{ $surat->keperluan }}</em>.
                            </p>

                            <div class="mt-8 flex justify-end text-xs text-right">
                                <div>
                                    <p class="text-gray-500">Ditetapkan di: Kampus Terpadu</p>
                                    <p class="text-gray-500">Pada tanggal: {{ $surat->tanggal_selesai?->translatedFormat('d F Y') }}</p>
                                    <p class="font-bold text-gray-900 dark:text-white mt-1">Kepala Bagian Akademik & Administrasi</p>
                                    <div class="my-3 font-serif italic text-purple-700 dark:text-purple-400 font-bold text-sm tracking-wider">
                                        [Disetujui Secara Elektronik]
                                    </div>
                                    <p class="font-bold text-gray-900 dark:text-white underline">{{ $surat->kepalaBagian->name ?? 'Dr. Ir. Hendra Gunawan, M.T.' }}</p>
                                    <p class="text-[10px] text-gray-400">NIP. 19780512 200501 1 002</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Kolom Aksi Otorisasi (1 Col) -->
                <div class="space-y-6">
                    <!-- Form Persetujuan -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Otorisasi Surat</h3>
                                <p class="text-[11px] text-gray-400">Keputusan Kepala Bagian</p>
                            </div>
                        </div>

                        <!-- Form Setujui -->
                        <form method="POST" action="{{ route('kepala-bagian.surat.setujui', $surat) }}" class="space-y-4">
                            @csrf

                            <div>
                                <label for="nomor_surat" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                    Nomor Surat Resmi <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $suggestedNomorSurat) }}" required class="w-full text-xs font-mono rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-purple-500 py-2">
                                <x-input-error :messages="$errors->get('nomor_surat')" class="mt-1" />
                            </div>

                            <div>
                                <label for="catatan_kepala_bagian" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                    Catatan Persetujuan (Opsional)
                                </label>
                                <textarea id="catatan_kepala_bagian" name="catatan_kepala_bagian" rows="2" placeholder="Catatan pengesahan..." class="w-full text-xs rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-purple-500">{{ old('catatan_kepala_bagian', $surat->catatan_kepala_bagian ?? 'Surat telah diperiksa dan disetujui secara resmi.') }}</textarea>
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Setujui & Terbitkan Surat
                            </button>
                        </form>

                        <div class="relative flex py-3 items-center">
                            <div class="flex-grow border-t border-gray-200 dark:border-gray-700"></div>
                            <span class="flex-shrink mx-3 text-[10px] text-gray-400 uppercase font-semibold">Atau</span>
                            <div class="flex-grow border-t border-gray-200 dark:border-gray-700"></div>
                        </div>

                        <!-- Form Tolak -->
                        <form method="POST" action="{{ route('kepala-bagian.surat.tolak', $surat) }}" class="space-y-3" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK permohonan surat ini?');">
                            @csrf

                            <div>
                                <label for="alasan_tolak" class="block text-xs font-semibold text-rose-600 dark:text-rose-400 mb-1">
                                    Alasan Penolakan <span class="text-rose-500">*</span>
                                </label>
                                <textarea id="alasan_tolak" name="catatan_kepala_bagian" rows="2" required placeholder="Tuliskan alasan penolakan secara jelas..." class="w-full text-xs rounded-xl border border-rose-300 dark:border-rose-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-rose-500"></textarea>
                            </div>

                            <button type="submit" class="w-full py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tolak Permohonan Ini
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
