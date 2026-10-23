<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('mahasiswa.surat.index') }}" class="p-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    Ajukan Permohonan Surat Baru
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Isi formulir berikut dengan data yang valid dan lengkap
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-flash-message />

            <!-- Info Mahasiswa Box -->
            <div class="p-4 rounded-2xl bg-indigo-50/70 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">NIM: {{ Auth::user()->nim ?? '-' }} | Prodi: {{ Auth::user()->prodi ?? '-' }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                    Mahasiswa Aktif Terverifikasi
                </span>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
                <form method="POST" action="{{ route('mahasiswa.surat.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Jenis Surat -->
                    <div>
                        <label for="jenis_surat" class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-1">
                            Pilih Jenis Surat <span class="text-rose-500">*</span>
                        </label>
                        <select id="jenis_surat" name="jenis_surat" required class="w-full rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 text-sm py-2.5">
                            <option value="">-- Pilih Jenis Layanan Surat --</option>
                            @foreach($jenisSurat as $key => $label)
                                <option value="{{ $key }}" {{ old('jenis_surat') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('jenis_surat')" class="mt-1" />
                    </div>

                    <!-- Keperluan -->
                    <div>
                        <label for="keperluan" class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-1">
                            Keperluan Permohonan Surat <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="keperluan" name="keperluan" rows="3" required placeholder="Jelaskan secara jelas keperluan pembuatan surat ini (contoh: Untuk pengurusan beasiswa KIP Kuliah Semester Genap 2026, atau keperluan izin penelitian di PT XYZ)..." class="w-full rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 text-sm">{{ old('keperluan') }}</textarea>
                        <x-input-error :messages="$errors->get('keperluan')" class="mt-1" />
                        <p class="text-[11px] text-gray-400 mt-1">Sebutkan alasan atau pihak yang meminta surat ini diterbitkan.</p>
                    </div>

                    <!-- Keterangan Tambahan -->
                    <div>
                        <label for="keterangan_tambahan" class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-1">
                            Keterangan Tambahan / Instansi Tujuan (Opsional)
                        </label>
                        <textarea id="keterangan_tambahan" name="keterangan_tambahan" rows="2" placeholder="Contoh: Ditujukan kepada Kepala Dinas Komunikasi dan Informatika Provinsi, Periode Magang: 1 Juli - 31 Agustus 2026..." class="w-full rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 text-sm">{{ old('keterangan_tambahan') }}</textarea>
                        <x-input-error :messages="$errors->get('keterangan_tambahan')" class="mt-1" />
                    </div>

                    <!-- Lampiran Berkas -->
                    <div>
                        <label for="lampiran" class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-1">
                            Unggah Berkas Pendukung (Opsional)
                        </label>
                        <input type="file" id="lampiran" name="lampiran" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-950/60 file:text-indigo-700 dark:file:text-indigo-400 hover:file:bg-indigo-100 border border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer">
                        <x-input-error :messages="$errors->get('lampiran')" class="mt-1" />
                        <p class="text-[11px] text-gray-400 mt-1">Format: PDF, JPG, PNG (Maksimal 2MB). Contoh: KRS aktif, proposal penelitian, atau kartu mahasiswa.</p>
                    </div>

                    <!-- Submit Actions -->
                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
                        <a href="{{ route('mahasiswa.surat.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md transition">
                            Kirim Pengajuan Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
