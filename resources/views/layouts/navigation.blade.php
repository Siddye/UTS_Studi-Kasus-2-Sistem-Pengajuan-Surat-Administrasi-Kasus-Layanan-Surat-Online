<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-bold text-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>SuratOnline FT</span>
                    </a>
                </div>

                <!-- Navigation Links Based on User Role -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(Auth::user()->isMahasiswa())
                        <x-nav-link :href="route('mahasiswa.dashboard')" :active="request()->routeIs('mahasiswa.dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('mahasiswa.surat.index')" :active="request()->routeIs('mahasiswa.surat.index')">
                            Surat Saya
                        </x-nav-link>
                        <x-nav-link :href="route('mahasiswa.surat.create')" :active="request()->routeIs('mahasiswa.surat.create')">
                            + Ajukan Surat
                        </x-nav-link>
                    @elseif(Auth::user()->isPetugas())
                        <x-nav-link :href="route('petugas.dashboard')" :active="request()->routeIs('petugas.dashboard')">
                            Dashboard Petugas
                        </x-nav-link>
                        <x-nav-link :href="route('petugas.surat.index')" :active="request()->routeIs('petugas.surat.*')">
                            Kelola Permohonan Surat
                        </x-nav-link>
                    @elseif(Auth::user()->isKepalaBagian())
                        <x-nav-link :href="route('kepala-bagian.dashboard')" :active="request()->routeIs('kepala-bagian.dashboard')">
                            Dashboard Kabag
                        </x-nav-link>
                        <x-nav-link :href="route('kepala-bagian.surat.index')" :active="request()->routeIs('kepala-bagian.surat.*')">
                            Persetujuan Surat
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Role Badge -->
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold me-3
                    @if(Auth::user()->isMahasiswa()) bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300
                    @elseif(Auth::user()->isPetugas()) bg-blue-100 text-blue-800 dark:bg-blue-900/60 dark:text-blue-300
                    @elseif(Auth::user()->isKepalaBagian()) bg-purple-100 text-purple-800 dark:bg-purple-900/60 dark:text-purple-300
                    @endif">
                    {{ Auth::user()->role_label }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->isMahasiswa())
                <x-responsive-nav-link :href="route('mahasiswa.dashboard')" :active="request()->routeIs('mahasiswa.dashboard')">
                    Dashboard
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('mahasiswa.surat.index')" :active="request()->routeIs('mahasiswa.surat.index')">
                    Surat Saya
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('mahasiswa.surat.create')" :active="request()->routeIs('mahasiswa.surat.create')">
                    Ajukan Surat Baru
                </x-responsive-nav-link>
            @elseif(Auth::user()->isPetugas())
                <x-responsive-nav-link :href="route('petugas.dashboard')" :active="request()->routeIs('petugas.dashboard')">
                    Dashboard Petugas
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('petugas.surat.index')" :active="request()->routeIs('petugas.surat.*')">
                    Kelola Permohonan Surat
                </x-responsive-nav-link>
            @elseif(Auth::user()->isKepalaBagian())
                <x-responsive-nav-link :href="route('kepala-bagian.dashboard')" :active="request()->routeIs('kepala-bagian.dashboard')">
                    Dashboard Kabag
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('kepala-bagian.surat.index')" :active="request()->routeIs('kepala-bagian.surat.*')">
                    Persetujuan Surat
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }} ({{ Auth::user()->role_label }})</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
