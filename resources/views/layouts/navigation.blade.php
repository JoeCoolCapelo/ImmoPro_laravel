<nav x-data="{ open: false }" class="relative bg-white/10 dark:bg-slate-900/40 backdrop-blur-xl border-b border-white/10 w-full z-[100] transition-all duration-300 shadow-[0_10px_30px_-10px_rgba(0,0,0,0.3)]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-[95%] mx-auto px-2 sm:px-4 lg:px-6">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        @if($__agencyLogo)
                            <img src="{{ Storage::url($__agencyLogo) }}" alt="Logo" class="h-10 w-10 rounded-xl object-cover border border-slate-100 shadow-sm transition-transform group-hover:scale-110">
                        @else
                            <div class="h-10 w-10 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-white shadow-md border border-white/10">
                                <i class="fa-solid fa-building text-lg"></i>
                            </div>
                        @endif
                        <span class="font-black text-white tracking-tighter text-lg">{{ $__agencyName }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-6 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-black uppercase tracking-widest text-[11px] flex items-center">
                        <i class="fa-solid fa-table-columns mr-2 text-white"></i>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('biens.index')" :active="request()->routeIs('biens.*')" class="font-black uppercase tracking-widest text-[11px] flex items-center">
                        <i class="fa-solid fa-house-chimney mr-2 text-white"></i>
                        {{ __('Catalogue') }}
                    </x-nav-link>
                    <x-nav-link :href="route('visites.index')" :active="request()->routeIs('visites.*')" class="font-black uppercase tracking-widest text-[11px] flex items-center">
                        <i class="fa-solid fa-calendar-check mr-2 text-white"></i>
                        {{ __('Visites') }}
                    </x-nav-link>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('agent'))
                        <x-nav-link :href="route('crm.index')" :active="request()->routeIs('crm.index')" class="font-black uppercase tracking-widest text-[11px] flex items-center">
                            <i class="fa-solid fa-layer-group mr-2 text-white"></i>
                            {{ __('Pipeline Leads') }}
                        </x-nav-link>
                    @endif
                    @can('viewAny', App\Models\Transaction::class)
                        <x-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')" class="font-black uppercase tracking-widest text-[11px] flex items-center">
                            <i class="fa-solid fa-file-invoice-dollar mr-2 text-white"></i>
                            {{ __('Transactions') }}
                        </x-nav-link>
                    @endcan
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('agent'))
                        <x-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')" class="font-black uppercase tracking-widest text-[11px] flex items-center">
                            <i class="fa-solid fa-tools mr-2 text-white"></i>
                            {{ __('Entretien') }}
                        </x-nav-link>
                    @endif
                    @can('users.manage')
                        <div class="hidden sm:flex sm:items-center sm:ms-4 relative">
                            <x-dropdown align="right" width="48" contentClasses="py-1 bg-slate-900/95 backdrop-blur-xl border border-white/10">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-[11px] font-black uppercase tracking-widest text-white hover:bg-white/10 rounded-xl transition ease-in-out duration-150">
                                        <i class="fa-solid fa-gears mr-2 text-white"></i>
                                        <div>{{ __('Gestion') }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </button>
                                </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('admin.users')">{{ __('Utilisateurs') }}</x-dropdown-link>
                                        <x-dropdown-link :href="route('documents.index')">{{ __('Documents & Contrats') }}</x-dropdown-link>
                                        <x-dropdown-link :href="route('reports.index')">{{ __('Rapports & Stats') }}</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.logs')">{{ __('Audit & Logs') }}</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.settings')">{{ __('Paramètres Agence') }}</x-dropdown-link>
                                    </x-slot>
                            </x-dropdown>
                        </div>
                    @endcan
                </div>
            </div>

            @auth
            <div class="flex items-center space-x-2">
                <!-- Dark Mode Toggle -->
                <div class="hidden sm:flex sm:items-center">
                    <button 
                        @click="
                            darkMode = !darkMode; 
                            localStorage.setItem('dark-mode', darkMode);
                            if (darkMode) {
                                document.documentElement.classList.add('dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                            }
                        "
                        x-data="{ darkMode: localStorage.getItem('dark-mode') === 'true' }"
                        class="p-2 text-slate-400 hover:text-indigo-600 transition-colors duration-200"
                    >
                        <i x-show="!darkMode" class="fa-solid fa-moon text-xl"></i>
                        <i x-show="darkMode" class="fa-solid fa-sun text-xl text-amber-400"></i>
                    </button>
                </div>

                <!-- Notifications Bell -->
                <div class="hidden sm:flex sm:items-center">
                    <a href="{{ route('notifications.index') }}" class="relative p-2 text-white/70 hover:text-white transition-colors duration-200">
                        <i class="fa-solid fa-bell text-xl"></i>
                        @if($__unreadCount > 0)
                            <span class="absolute top-1 right-1 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-500 text-[10px] text-white font-black items-center justify-center">
                                    {{ $__unreadCount }}
                                </span>
                            </span>
                        @endif
                    </a>
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-4">
                    <x-dropdown align="right" width="48" contentClasses="py-1 bg-slate-900/95 backdrop-blur-xl border border-white/10">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center p-1 border border-white/10 text-sm leading-4 font-black rounded-full text-white/70 bg-white/5 hover:text-white hover:bg-white/10 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                                <div class="h-9 w-9 rounded-full bg-white/10 flex items-center justify-center border-2 border-white/20 shadow-sm overflow-hidden">
                                    @if(Auth::user()->photo_url)
                                        <img src="{{ Storage::url(Auth::user()->photo_url) }}" class="h-full w-full object-cover">
                                    @else
                                        <span class="text-white text-xs font-black">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                            </button>
                        </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')" class="flex items-center">
                                    <i class="fa-solid fa-user-circle mr-2 text-slate-400"></i>
                                    {{ __('Profil') }}
                                </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        class="flex items-center text-rose-600 hover:bg-rose-50"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    <i class="fa-solid fa-right-from-bracket mr-2"></i>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
            @else
            <div class="flex items-center ms-6 space-x-6">
                <a href="{{ route('login') }}" class="text-xs font-black uppercase tracking-widest text-white/70 hover:text-white transition-colors">
                    {{ __('Connexion') }}
                </a>
                <a href="{{ route('register') }}" class="bg-white text-slate-900 hover:bg-slate-200 font-black py-2 px-6 rounded-full text-[10px] uppercase tracking-widest shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                    {{ __('Créer un compte') }}
                </a>
            </div>
            @endauth

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-white/70 hover:text-white hover:bg-white/10 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>



    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden px-4 pt-4 pb-2 bg-slate-950/90 backdrop-blur-2xl rounded-b-3xl border-x border-b border-white/10 shadow-2xl">
        <div class="flex items-center space-x-3 mb-4 p-2 bg-white/5 rounded-2xl border border-white/10">
            @if($__agencyLogo)
                <img src="{{ Storage::url($__agencyLogo) }}" alt="Logo" class="h-8 w-8 rounded-lg object-cover">
            @else
                <div class="h-8 w-8 rounded-lg bg-white/10 flex items-center justify-center text-white shadow-md border border-white/10">
                    <i class="fa-solid fa-building text-sm"></i>
                </div>
            @endif
            <span class="font-black text-white tracking-tighter">{{ $__agencyName }}</span>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('biens.index')" :active="request()->routeIs('biens.*')">
                {{ __('Catalogue') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('visites.index')" :active="request()->routeIs('visites.*')">
                {{ __('Visites') }}
            </x-responsive-nav-link>
            @can('viewAny', App\Models\Transaction::class)
                <x-responsive-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')">
                    {{ __('Transactions') }}
                </x-responsive-nav-link>
            @endcan
            @can('users.manage')
                <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')">
                    {{ __('Administration') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.logs')" :active="request()->routeIs('admin.logs')">
                    {{ __('Audit') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')">
                    {{ __('Paramètres') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-white/10">
            <div class="px-4 flex items-center">
                <div class="h-12 w-12 rounded-full bg-white/10 flex items-center justify-center mr-3 border-2 border-white/20 shadow-md overflow-hidden">
                    @if(Auth::user()->photo_url)
                        <img src="{{ Storage::url(Auth::user()->photo_url) }}" class="h-full w-full object-cover">
                    @else
                        <span class="text-white text-sm font-black">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div>
                    <div class="font-black text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center">
                    <i class="fa-solid fa-user-circle mr-3 text-slate-400"></i>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('favorites.index')" class="flex items-center">
                    <i class="fa-solid fa-heart mr-3 text-rose-500"></i>
                    {{ __('Mes Favoris') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            class="flex items-center text-rose-600"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i class="fa-solid fa-right-from-bracket mr-3"></i>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <div class="pt-4 pb-1 border-t border-slate-200 dark:border-slate-800">
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('login')">
                    Connexion
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">
                    Inscription
                </x-responsive-nav-link>
            </div>
        </div>
        @endauth
    </div>
</nav>

