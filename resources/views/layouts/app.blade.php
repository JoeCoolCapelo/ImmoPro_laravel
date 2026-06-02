@php
    // Cache all settings in one DB query for the whole page
    $__settings = \App\Models\Setting::all()->pluck('value', 'key');
    $__agencyName = $__settings->get('agency_name', config('app.name', 'ImmoPro'));
    $__agencyLogo = $__settings->get('agency_logo');
    $__unreadCount = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
    // Share with views
    View::share('__settings', $__settings);
    View::share('__agencyName', $__agencyName);
    View::share('__agencyLogo', $__agencyLogo);
    View::share('__unreadCount', $__unreadCount);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $__agencyName }}</title>

        <!-- Favicon -->
        @if($__agencyLogo)
            <link rel="icon" type="image/png" href="{{ Storage::url($__agencyLogo) }}">
        @endif

        <!-- Fonts: preconnect for speed -->
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link rel="preload" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"></noscript>

        <!-- Font Awesome: async load -->
        <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'" crossorigin>
        <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

        <!-- Scripts (Alpine.js already bundled inside app.js via Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Chart.js loaded before page content so inline scripts can use it -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <style>[x-cloak] { display: none !important; }</style>
        <script>
            if (localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen relative transition-colors duration-300">
            {{-- Background: CSS gradient (no external image = instant load) --}}
            <div class="fixed inset-0 z-0">
                <div class="absolute inset-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 40%, #1e1b4b 70%, #0f172a 100%);"></div>
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20% 50%, #6366f1 0%, transparent 50%), radial-gradient(circle at 80% 20%, #4f46e5 0%, transparent 40%), radial-gradient(circle at 60% 80%, #3730a3 0%, transparent 40%);"></div>
            </div>

            <div class="relative z-10">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
                    <div class="text-white">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <!-- Page Content -->
            <main class="mt-4 pb-24 md:pb-8">
                {{ $slot }}
            </main>

            {{-- Bottom Navigation (Mobile Only) --}}
            <div class="fixed bottom-6 left-4 right-4 z-50 md:hidden">
                <div class="bg-slate-900/80 backdrop-blur-2xl border border-white/10 rounded-[2rem] p-4 shadow-2xl flex justify-between items-center px-8">
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center group">
                        <div class="p-2 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-400 group-hover:text-white' }} rounded-xl transition-all duration-300">
                            <i class="fa-solid fa-house-chimney text-lg"></i>
                        </div>
                    </a>
                    <a href="{{ route('biens.index') }}" class="flex flex-col items-center group">
                        <div class="p-2 {{ request()->routeIs('biens.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 group-hover:text-white' }} rounded-xl transition-all duration-300">
                            <i class="fa-solid fa-magnifying-glass text-lg"></i>
                        </div>
                    </a>
                    <a href="{{ route('visites.index') }}" class="flex flex-col items-center group">
                        <div class="p-2 {{ request()->routeIs('visites.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 group-hover:text-white' }} rounded-xl transition-all duration-300">
                            <i class="fa-solid fa-calendar-check text-lg"></i>
                        </div>
                    </a>
                    <a href="{{ route('notifications.index') }}" class="flex flex-col items-center group relative">
                        <div class="p-2 {{ request()->routeIs('notifications.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 group-hover:text-white' }} rounded-xl transition-all duration-300">
                            <i class="fa-solid fa-bell text-lg"></i>
                        </div>
                        @if($__unreadCount > 0)
                            <span class="absolute top-1 right-1 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center group">
                        <div class="p-2 {{ request()->routeIs('profile.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 group-hover:text-white' }} rounded-xl transition-all duration-300">
                            @if(auth()->user() && auth()->user()->photo_url)
                                <img src="{{ Storage::url(auth()->user()->photo_url) }}" class="h-6 w-6 rounded-lg object-cover">
                            @else
                                <i class="fa-solid fa-user-circle text-lg"></i>
                            @endif
                        </div>
                    </a>
                </div>
            </div>

            <footer class="py-8 border-t border-white/10 mt-auto pb-28 md:pb-8">
                <div class="max-w-7xl mx-auto px-4 text-center">
                    <p class="text-xs font-bold text-white/40 uppercase tracking-[0.2em]">
                        &copy; {{ date('Y') }} {{ $__agencyName }} &mdash; {{ __('Plateforme Immobilière Certifiée') }}
                    </p>
                </div>
            </footer>
            </div>
        </div>
    </body>
</html>
