<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Layanan Surat Mahasiswa Online (SuratOnline FT) — Fakultas Teknologi</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-slate-100 min-h-screen font-sans antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Header Navigation -->
    <header class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <span class="font-bold text-lg tracking-tight block">Fakultas Teknologi</span>
                <span class="text-xs text-indigo-300 block -mt-1">SuratOnline FT — Layanan Surat Mahasiswa Online</span>
            </div>
        </div>

        <div>
            @if (Route::has('login'))
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 font-semibold text-sm shadow-md transition">
                            Buka Dashboard &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white transition">
                            Masuk / Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 font-semibold text-sm text-white shadow-md transition">
                                Daftar Mahasiswa
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <!-- Hero Section -->
    <main class="max-w-7xl mx-auto px-6 pt-12 pb-20">
        <div class="text-center max-w-4xl mx-auto space-y-6">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Studi Kasus 2: Middleware + Route Group + Otorisasi Kepemilikan Data
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                Layanan Surat Mahasiswa Online<br />
                <span class="text-indigo-400">(SuratOnline FT)</span>
            </h1>

            <p class="text-slate-300 text-base sm:text-lg leading-relaxed max-w-2xl mx-auto">
                Portal resmi Fakultas Teknologi untuk pengajuan dan penerbitan surat aktif kuliah, izin penelitian, cuti, dan magang bagi mahasiswa secara aman, cepat, dan transparan.
            </p>

            <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="px-8 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 font-bold text-sm text-white shadow-xl shadow-indigo-600/30 transition transform hover:-translate-y-0.5">
                    Mulai Layanan Sekarang
                </a>
                <a href="#fitur" class="px-6 py-3.5 rounded-xl bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 font-medium text-sm text-slate-300 hover:text-white transition">
                    Pelajari Struktur Peran & Keamanan
                </a>
            </div>
        </div>

        <!-- 3 Role Matrix Cards -->
        <div id="fitur" class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Mahasiswa Card -->
            <div class="p-6 rounded-3xl bg-slate-800/50 border border-slate-700/60 backdrop-blur-sm hover:border-emerald-500/50 transition space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-white">1. Mahasiswa</h3>
                <p class="text-sm text-slate-300 leading-relaxed">
                    Mengajukan surat aktif kuliah, izin penelitian, magang, dll. Hanya dapat melihat dan memantau surat miliknya sendiri melalui proteksi kepemilikan data (Policy & Gate).
                </p>
                <div class="pt-2 text-xs text-emerald-400 font-mono">
                    Prefix: /mahasiswa/*
                </div>
            </div>

            <!-- Petugas TU Card -->
            <div class="p-6 rounded-3xl bg-slate-800/50 border border-slate-700/60 backdrop-blur-sm hover:border-blue-500/50 transition space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h3 class="text-xl font-bold text-white">2. Petugas Administrasi</h3>
                <p class="text-sm text-slate-300 leading-relaxed">
                    Memverifikasi kelengkapan berkas, membedakan surat masuk (menunggu vs diproses), menyiapkan draf penomoran, dan meneruskan berkas siap uji ke pimpinan.
                </p>
                <div class="pt-2 text-xs text-blue-400 font-mono">
                    Prefix: /petugas/*
                </div>
            </div>

            <!-- Kepala Bagian Card -->
            <div class="p-6 rounded-3xl bg-slate-800/50 border border-slate-700/60 backdrop-blur-sm hover:border-purple-500/50 transition space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-white">3. Kepala Bagian</h3>
                <p class="text-sm text-slate-300 leading-relaxed">
                    Memiliki hak otorisasi final. Berwenang menyetujui penerbitan surat resmi dengan nomor registrasi sah atau menolak permohonan dengan menyertakan alasan tertulis.
                </p>
                <div class="pt-2 text-xs text-purple-400 font-mono">
                    Prefix: /kepala-bagian/*
                </div>
            </div>
        </div>

        <!-- Demonstration Account Card -->
        <div class="mt-12 p-8 rounded-3xl bg-slate-800/80 border border-slate-700/80">
            <h4 class="text-lg font-bold text-white mb-2">Akun Uji Coba Cepat (Demo Testing Accounts)</h4>
            <p class="text-xs text-slate-400 mb-6">Gunakan akun-akun di bawah ini untuk menguji hak akses URL, middleware, dan pencegahan pembobolan ID surat:</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs font-mono">
                <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-700">
                    <span class="text-emerald-400 font-bold block mb-1">Mahasiswa 1 (Pemilik Surat #1 & #2)</span>
                    <span class="text-slate-300 block">mahasiswa1@teknologi.ac.id</span>
                    <span class="text-slate-500 block mt-1">Pass: password</span>
                </div>
                <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-700">
                    <span class="text-emerald-400 font-bold block mb-1">Mahasiswa 2 (Pemilik Surat #3 & #4)</span>
                    <span class="text-slate-300 block">mahasiswa2@teknologi.ac.id</span>
                    <span class="text-slate-500 block mt-1">Pass: password</span>
                </div>
                <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-700">
                    <span class="text-blue-400 font-bold block mb-1">Petugas Administrasi</span>
                    <span class="text-slate-300 block">petugas@teknologi.ac.id</span>
                    <span class="text-slate-500 block mt-1">Pass: password</span>
                </div>
                <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-700">
                    <span class="text-purple-400 font-bold block mb-1">Kepala Bagian</span>
                    <span class="text-slate-300 block">kabag@teknologi.ac.id</span>
                    <span class="text-slate-500 block mt-1">Pass: password</span>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800 text-center py-6 text-xs text-slate-500">
        &copy; 2026 Fakultas Teknologi — Sistem Pengajuan Surat Administrasi Online.
    </footer>
</body>
</html>
