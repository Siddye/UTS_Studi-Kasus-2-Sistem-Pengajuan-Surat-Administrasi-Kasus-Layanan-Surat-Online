<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Layanan Surat Mahasiswa Online (SuratOnline FT)</h2>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Fakultas Teknologi — Akses Berbasis Peran & Hak Kepemilikan</p>
    </div>

    <!-- Quick Demo Accounts Helper -->
    <div class="mb-6 p-4 rounded-xl bg-indigo-50/70 dark:bg-gray-800/80 border border-indigo-100 dark:border-gray-700 text-xs">
        <p class="font-semibold text-indigo-900 dark:text-indigo-300 mb-2 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Akun Demo Pengujian (Klik untuk Isi Otomatis):
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
            <button type="button" onclick="fillLogin('mahasiswa1@teknologi.ac.id', 'password')" class="text-left px-2.5 py-1.5 rounded-lg bg-white dark:bg-gray-900 hover:bg-indigo-100 dark:hover:bg-indigo-950/60 border border-gray-200 dark:border-gray-700 transition">
                <span class="font-medium text-emerald-700 dark:text-emerald-400 block">Mahasiswa 1</span>
                <span class="text-gray-500 text-[11px]">mahasiswa1@teknologi.ac.id</span>
            </button>
            <button type="button" onclick="fillLogin('mahasiswa2@teknologi.ac.id', 'password')" class="text-left px-2.5 py-1.5 rounded-lg bg-white dark:bg-gray-900 hover:bg-indigo-100 dark:hover:bg-indigo-950/60 border border-gray-200 dark:border-gray-700 transition">
                <span class="font-medium text-emerald-700 dark:text-emerald-400 block">Mahasiswa 2</span>
                <span class="text-gray-500 text-[11px]">mahasiswa2@teknologi.ac.id</span>
            </button>
            <button type="button" onclick="fillLogin('petugas@teknologi.ac.id', 'password')" class="text-left px-2.5 py-1.5 rounded-lg bg-white dark:bg-gray-900 hover:bg-blue-100 dark:hover:bg-blue-950/60 border border-gray-200 dark:border-gray-700 transition">
                <span class="font-medium text-blue-700 dark:text-blue-400 block">Petugas Administrasi</span>
                <span class="text-gray-500 text-[11px]">petugas@teknologi.ac.id</span>
            </button>
            <button type="button" onclick="fillLogin('kabag@teknologi.ac.id', 'password')" class="text-left px-2.5 py-1.5 rounded-lg bg-white dark:bg-gray-900 hover:bg-purple-100 dark:hover:bg-purple-950/60 border border-gray-200 dark:border-gray-700 transition">
                <span class="font-medium text-purple-700 dark:text-purple-400 block">Kepala Bagian</span>
                <span class="text-gray-500 text-[11px]">kabag@teknologi.ac.id</span>
            </button>
        </div>
        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-2 text-center">Password semua akun demo: <strong>password</strong></p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md" href="{{ route('register') }}">
                Daftar Mahasiswa Baru
            </a>

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</x-guest-layout>
