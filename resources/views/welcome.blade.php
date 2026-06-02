<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ImmoPro - Agence Immobilière</title>
    <meta name="description" content="ImmoPro - Votre agence immobilière de confiance. Trouvez la propriété de vos rêves.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0ea5e9 100%); }
        .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        .reveal { opacity: 0; transform: translateY(40px); transition: opacity 0.8s ease-out, transform 0.8s ease-out; }
        .reveal.active { opacity: 1; transform: translateY(0); }
        .delay-1 { transition-delay: 0.15s; }
        .delay-2 { transition-delay: 0.3s; }
        .delay-3 { transition-delay: 0.45s; }
        .service-card { background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%); }
        .btn-primary { background: #0f172a; transition: all 0.3s ease; }
        .btn-primary:hover { background: #1e293b; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(15,23,42,0.4); }
        .glass { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); }
        .stat-card { background: linear-gradient(145deg, rgba(255,255,255,0.15), rgba(255,255,255,0.05)); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); }
        .price-badge { background: #0f172a; }
        .cta-gradient { background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%); }
    </style>
</head>
<body x-data="{ sidebarOpen: false }" class="bg-gray-50 text-gray-800 antialiased">

    {{-- Navigation --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" @click.prevent="sidebarOpen = true" class="flex items-center space-x-2 group cursor-pointer">
                    <div class="w-9 h-9 rounded-lg btn-primary flex items-center justify-center transition-transform group-hover:scale-110 group-hover:rotate-3">
                        <i class="fa-solid fa-building text-white text-sm"></i>
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span class="text-xl font-bold text-gray-900">Immo<span class="text-slate-500">Pro</span></span>
                        <span class="text-[7px] font-black uppercase tracking-[0.2em] text-slate-500">Menu Principal</span>
                    </div>
                </a>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('biens.index') }}" class="text-sm font-medium text-gray-600 hover:text-slate-900 transition px-3 py-2">Catalogue</a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-white btn-primary px-5 py-2 rounded-lg">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-slate-900 transition px-3 py-2">Connexion</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-semibold text-white btn-primary px-5 py-2 rounded-lg">Inscription</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Slide-over Sidebar --}}
    <div x-show="sidebarOpen" 
         class="fixed inset-0 overflow-hidden z-[60]" 
         style="display: none;">
        <!-- Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="ease-in-out duration-500" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in-out duration-500" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             @click="sidebarOpen = false" 
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-y-0 left-0 max-w-full flex">
            <div x-show="sidebarOpen" 
                 x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" 
                 x-transition:enter-start="-translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" 
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="-translate-x-full" 
                 class="w-screen max-w-[16rem] sm:max-w-[18rem]">
                <div class="h-full flex flex-col bg-white shadow-2xl overflow-y-auto rounded-r-3xl border-r border-slate-100">
                    <div class="flex-1 py-6 px-5 sm:px-6">
                        <!-- Sidebar Header -->
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-xl btn-primary flex items-center justify-center text-white shadow-md shadow-slate-300">
                                    <i class="fa-solid fa-building text-lg"></i>
                                </div>
                                <div class="flex flex-col leading-tight">
                                    <h2 class="text-lg font-black text-slate-900 tracking-tighter">ImmoPro</h2>
                                    <p class="text-[8px] font-black uppercase tracking-widest text-slate-500">Navigation</p>
                                </div>
                            </div>
                            <button @click="sidebarOpen = false" class="p-2 rounded-xl bg-slate-50 text-slate-400 hover:text-slate-900 transition-colors">
                                <i class="fa-solid fa-xmark text-lg"></i>
                            </button>
                        </div>

                        <!-- Sidebar Links -->
                        <nav class="space-y-2.5">
                            <p class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-400 ml-2 mb-2">Explorer</p>
                            
                            <a href="#services" @click="sidebarOpen = false" class="group flex items-center p-2.5 rounded-2xl bg-slate-50 hover:bg-slate-900 transition-all duration-300">
                                <div class="h-8 w-8 rounded-xl bg-white flex items-center justify-center mr-3 shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-hand-holding-heart text-slate-900 text-xs"></i>
                                </div>
                                <span class="font-black text-slate-900 group-hover:text-white tracking-tight uppercase text-[10px]">Nos Services</span>
                                <i class="fa-solid fa-chevron-right ml-auto text-slate-300 group-hover:text-slate-400 text-[10px]"></i>
                            </a>

                            <a href="#membres" @click="sidebarOpen = false" class="group flex items-center p-2.5 rounded-2xl bg-slate-50 hover:bg-slate-900 transition-all duration-300">
                                <div class="h-8 w-8 rounded-xl bg-white flex items-center justify-center mr-3 shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-users text-slate-900 text-xs"></i>
                                </div>
                                <span class="font-black text-slate-900 group-hover:text-white tracking-tight uppercase text-[10px]">Membres & Équipe</span>
                                <i class="fa-solid fa-chevron-right ml-auto text-slate-300 group-hover:text-slate-400 text-[10px]"></i>
                            </a>

                            <a href="#aide" @click="sidebarOpen = false" class="group flex items-center p-2.5 rounded-2xl bg-slate-50 hover:bg-slate-900 transition-all duration-300">
                                <div class="h-8 w-8 rounded-xl bg-white flex items-center justify-center mr-3 shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-circle-info text-slate-900 text-xs"></i>
                                </div>
                                <span class="font-black text-slate-900 group-hover:text-white tracking-tight uppercase text-[10px]">Aide & Support</span>
                                <i class="fa-solid fa-chevron-right ml-auto text-slate-300 group-hover:text-slate-400 text-[10px]"></i>
                            </a>

                            <a href="#propos" @click="sidebarOpen = false" class="group flex items-center p-2.5 rounded-2xl bg-slate-50 hover:bg-slate-900 transition-all duration-300">
                                <div class="h-8 w-8 rounded-xl bg-white flex items-center justify-center mr-3 shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-building-circle-check text-slate-900 text-xs"></i>
                                </div>
                                <span class="font-black text-slate-900 group-hover:text-white tracking-tight uppercase text-[10px]">À propos de nous</span>
                                <i class="fa-solid fa-chevron-right ml-auto text-slate-300 group-hover:text-slate-400 text-[10px]"></i>
                            </a>
                        </nav>

                        <!-- Special Offer / Contact -->
                        <div class="mt-10 p-5 rounded-2xl bg-slate-100 border border-slate-200">
                            <h4 class="font-black text-slate-900 uppercase tracking-widest text-[9px] mb-2">Rejoignez-nous</h4>
                            <p class="text-[10px] text-slate-600 leading-relaxed mb-4">Créez un compte pour accéder à des offres exclusives et gérer vos visites facilement.</p>
                            @guest
                                <a href="{{ route('register') }}" class="btn-primary py-2 text-[9px] w-full text-center inline-block rounded-lg text-white font-bold">
                                    S'INSCRIRE GRATUITEMENT
                                </a>
                            @endguest
                        </div>
                    </div>

                    <div class="p-5 border-t border-slate-100 flex justify-between items-center bg-slate-50">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">&copy; {{ date('Y') }} ImmoPro</p>
                        <div class="flex space-x-3 text-xs">
                            <a href="#" class="text-slate-400 hover:text-sky-600 transition-colors"><i class="fa-brands fa-facebook"></i></a>
                            <a href="https://wa.me/224625997903?text=Bonjour ImmoPro, j'aimerais avoir des informations concernant vos services immobiliers." target="_blank" class="text-slate-400 hover:text-emerald-500 transition-colors"><i class="fa-brands fa-whatsapp"></i></a>
                            <a href="#" class="text-slate-400 hover:text-sky-600 transition-colors"><i class="fa-brands fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hero Section --}}
    {{-- Hero Section --}}
    <section id="propos" class="min-h-[85vh] flex items-center pt-16 relative overflow-hidden bg-slate-950">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <!-- Replace the URL below with the path to your image -->
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80')] bg-cover bg-top filter brightness-75"></div>
            <!-- Overlay sombre pour la lisibilité -->
            <div class="absolute inset-0 bg-slate-900/50 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/70 via-transparent to-slate-900/60"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="inline-block px-4 py-1.5 text-xs font-semibold text-slate-300 glass rounded-full mb-6 reveal"> Votre partenaire immobilier de confiance</span>
                    <h1 class="text-4xl lg:text-5xl xl:text-6xl font-extrabold text-white leading-tight mb-6 reveal delay-1">
                        Trouvez le bien <span class="text-slate-400">idéal</span> pour vous
                    </h1>
                    <p class="text-lg text-gray-300 mb-8 max-w-lg reveal delay-2">Que vous cherchiez à acheter, vendre ou louer, ImmoPro vous accompagne à chaque étape de votre projet immobilier.</p>
                    <div class="flex flex-wrap gap-4 reveal delay-3">
                        <a href="{{ route('biens.index') }}" class="btn-primary text-white font-semibold px-8 py-3 rounded-xl text-sm shadow-xl shadow-slate-900/40">Explorer le catalogue</a>
                        @guest
                            <a href="{{ route('login') }}" class="bg-white text-slate-900 font-bold px-8 py-3 rounded-xl text-sm hover:bg-slate-100 transition shadow-lg">Se connecter</a>
                            <a href="{{ route('register') }}" class="glass text-white font-semibold px-8 py-3 rounded-xl text-sm hover:bg-white/20 transition">Créer un compte</a>
                        @endguest
                    </div>
                </div>
                <div class="hidden lg:grid grid-cols-2 gap-4">
                    <div class="stat-card rounded-2xl p-6 text-center reveal delay-1">
                        <i class="fa-solid fa-house-chimney text-slate-400 text-2xl mb-2"></i>
                        @php
                            $user = auth()->user();
                            $count = ($user && $user->hasRole('proprietaire')) 
                                ? \App\Models\Bien::where('user_id', $user->id)->count()
                                : \App\Models\Bien::where('statut','publié')->count();
                        @endphp
                        <p class="text-3xl font-bold text-white">{{ $count }}+</p>
                        <p class="text-sm text-gray-300 mt-1">Biens disponibles</p>
                    </div>
                    <div class="stat-card rounded-2xl p-6 text-center reveal delay-2">
                        <i class="fa-solid fa-handshake text-slate-400 text-2xl mb-2"></i>
                        <p class="text-3xl font-bold text-white">{{ \App\Models\Transaction::count() }}</p>
                        <p class="text-sm text-gray-300 mt-1">Transactions réussies</p>
                    </div>
                    <div class="stat-card rounded-2xl p-6 text-center reveal delay-2">
                        <i class="fa-solid fa-user-tie text-slate-400 text-2xl mb-2"></i>
                        <p class="text-3xl font-bold text-white">{{ \App\Models\User::role('agent')->count() }}</p>
                        <p class="text-sm text-gray-300 mt-1">Agents experts</p>
                    </div>
                    <div class="stat-card rounded-2xl p-6 text-center reveal delay-3">
                        <i class="fa-solid fa-users text-slate-400 text-2xl mb-2"></i>
                        <p class="text-3xl font-bold text-white">{{ \App\Models\User::role('client')->count() }}+</p>
                        <p class="text-sm text-gray-300 mt-1">Clients satisfaits</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Derniers Biens --}}
    @if($biens->count() > 0)
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14 reveal">
                <span class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Nos dernières offres</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2">Biens récemment publiés</h2>
                <p class="text-gray-500 mt-3 max-w-2xl mx-auto">Découvrez nos dernières propriétés mises en ligne par nos agents.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($biens as $bien)
                <a href="{{ route('biens.show', $bien) }}" class="card-hover rounded-2xl overflow-hidden bg-white border border-gray-100 shadow-sm group reveal delay-{{ $loop->index % 3 + 1 }}">
                    <div class="relative h-56 bg-slate-900 overflow-hidden group/slider">
                        @if($bien->images->count() > 0)
                            <style>
                                @keyframes steppedScrollWelcome{{ $bien->id }} {
                                    @php 
                                        $count = $bien->images->count();
                                        $step = 100 / $count;
                                        $pause = $step * 0.8;
                                    @endphp
                                    @foreach($bien->images as $index => $image)
                                        {{ $index * $step }}% { transform: translateX(-{{ ($index / ($count * 2)) * 100 }}%); }
                                        {{ ($index * $step) + $pause }}% { transform: translateX(-{{ ($index / ($count * 2)) * 100 }}%); }
                                    @endforeach
                                    100% { transform: translateX(-50%); }
                                }
                                .animate-welcome-{{ $bien->id }} {
                                    animation: steppedScrollWelcome{{ $bien->id }} {{ $bien->images->count() * 4 }}s ease-in-out infinite;
                                }
                                .animate-welcome-{{ $bien->id }}:hover {
                                    animation-play-state: paused;
                                }
                            </style>
                            <div class="flex h-full {{ $bien->images->count() > 1 ? 'animate-welcome-'.$bien->id : '' }}" 
                                 style="width: {{ $bien->images->count() * 200 }}%">
                                @foreach($bien->images as $image)
                                    <div class="h-full" style="width: {{ 100 / ($bien->images->count() * 2) }}%">
                                        <img src="{{ Storage::url($image->path) }}" alt="{{ $bien->titre }}" class="w-full h-full object-contain">
                                    </div>
                                @endforeach
                                @foreach($bien->images as $image)
                                    <div class="h-full" style="width: {{ 100 / ($bien->images->count() * 2) }}%">
                                        <img src="{{ Storage::url($image->path) }}" alt="{{ $bien->titre }}" class="w-full h-full object-contain">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="w-full h-full relative group">
                                <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" class="w-full h-full object-cover filter brightness-75">
                                <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                    <span class="text-white font-bold text-xs uppercase tracking-widest bg-black/40 backdrop-blur-sm px-4 py-2 rounded-lg border border-white/20 italic">Image en attente</span>
                                </div>
                            </div>
                        @endif
                        <span class="absolute top-3 left-3 price-badge text-white text-xs font-bold px-3 py-1 rounded-full">{{ number_format($bien->prix, 0, ',', ' ') }} GNF</span>
                        <span class="absolute top-3 right-3 bg-white/90 backdrop-blur text-gray-700 text-xs font-semibold px-3 py-1 rounded-full capitalize">{{ $bien->nature }}</span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 text-lg mb-1 group-hover:text-slate-600 transition">{{ $bien->titre }}</h3>
                        <p class="text-sm text-gray-500 flex items-center mb-3">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $bien->ville }}
                        </p>
                        <div class="flex items-center justify-between text-sm text-gray-400 border-t border-gray-100 pt-3">
                            <span>{{ $bien->surface }} m²</span>
                            <span>{{ $bien->nb_pieces }} pièces</span>
                            <span class="capitalize">{{ $bien->type }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="text-center mt-12 reveal">
                <a href="{{ route('biens.index') }}" class="btn-primary inline-block text-white font-semibold px-8 py-3 rounded-xl text-sm">Voir tout le catalogue →</a>
            </div>
        </div>
    </section>
    @endif

    {{-- Services --}}
    <section id="services" class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 reveal">
                <span class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Ce que nous offrons</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2">Nos Services</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="service-card rounded-2xl p-6 shadow-sm border border-gray-100 card-hover text-center reveal delay-1">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-coins text-slate-900 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Vente</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Nous vous aidons à vendre votre bien au meilleur prix grâce à notre réseau d'acheteurs qualifiés.</p>
                </div>
                <div class="service-card rounded-2xl p-6 shadow-sm border border-gray-100 card-hover text-center reveal delay-2">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-key text-slate-900 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Location</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Trouvez le logement idéal à louer parmi notre sélection de biens vérifiés et certifiés.</p>
                </div>
                <div class="service-card rounded-2xl p-6 shadow-sm border border-gray-100 card-hover text-center reveal delay-3">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-chart-pie text-slate-900 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Gestion</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Confiez-nous la gestion complète de vos biens : locataires, entretien, comptabilité.</p>
                </div>
            </div>
        </div>
    </section>
    
    {{-- Membres & Équipe --}}
    <section id="membres" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <span class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Des experts à votre service</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-2">Notre Équipe d'Elite</h2>
                <p class="text-gray-500 mt-3 max-w-2xl mx-auto">Rencontrez les agents professionnels qui vous accompagnent dans la réussite de vos projets.</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-12 max-w-5xl mx-auto">
                {{-- Member 1 --}}
                @if(\App\Models\Setting::get('team_member_1_name'))
                    <div class="group reveal delay-1">
                        <div class="relative mb-6 overflow-hidden rounded-[3rem] bg-slate-100 aspect-[4/5] shadow-xl">
                            @if(\App\Models\Setting::get('team_member_1_photo'))
                                <img src="{{ Storage::url(\App\Models\Setting::get('team_member_1_photo')) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <img src="https://i.pravatar.cc/500?u={{ urlencode(\App\Models\Setting::get('team_member_1_name')) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                <div class="flex space-x-4">
                                    <a href="#" class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-emerald-500 transition-all"><i class="fa-brands fa-whatsapp"></i></a>
                                    <a href="#" class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-indigo-500 transition-all"><i class="fa-solid fa-envelope"></i></a>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 text-center mb-1">{{ \App\Models\Setting::get('team_member_1_name') }}</h3>
                        <p class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em] text-center">{{ \App\Models\Setting::get('team_member_1_role', 'Directeur') }}</p>
                    </div>
                @endif

                {{-- Member 2 --}}
                @if(\App\Models\Setting::get('team_member_2_name'))
                    <div class="group reveal delay-2">
                        <div class="relative mb-6 overflow-hidden rounded-[3rem] bg-slate-100 aspect-[4/5] shadow-xl">
                            @if(\App\Models\Setting::get('team_member_2_photo'))
                                <img src="{{ Storage::url(\App\Models\Setting::get('team_member_2_photo')) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <img src="https://i.pravatar.cc/500?u={{ urlencode(\App\Models\Setting::get('team_member_2_name')) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                <div class="flex space-x-4">
                                    <a href="#" class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-emerald-500 transition-all"><i class="fa-brands fa-whatsapp"></i></a>
                                    <a href="#" class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-indigo-500 transition-all"><i class="fa-solid fa-envelope"></i></a>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 text-center mb-1">{{ \App\Models\Setting::get('team_member_2_name') }}</h3>
                        <p class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em] text-center">{{ \App\Models\Setting::get('team_member_2_role', 'Comptable') }}</p>
                    </div>
                @endif

                {{-- Member 3 --}}
                @if(\App\Models\Setting::get('team_member_3_name'))
                    <div class="group reveal delay-3">
                        <div class="relative mb-6 overflow-hidden rounded-[3rem] bg-slate-100 aspect-[4/5] shadow-xl">
                            @if(\App\Models\Setting::get('team_member_3_photo'))
                                <img src="{{ Storage::url(\App\Models\Setting::get('team_member_3_photo')) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <img src="https://i.pravatar.cc/500?u={{ urlencode(\App\Models\Setting::get('team_member_3_name')) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent flex items-end justify-center pb-8 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                <div class="flex space-x-4">
                                    <a href="#" class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-emerald-500 transition-all"><i class="fa-brands fa-whatsapp"></i></a>
                                    <a href="#" class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-indigo-500 transition-all"><i class="fa-solid fa-envelope"></i></a>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 text-center mb-1">{{ \App\Models\Setting::get('team_member_3_name') }}</h3>
                        <p class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em] text-center">{{ \App\Models\Setting::get('team_member_3_role', 'Manager') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 relative overflow-hidden bg-slate-950">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <!-- Replace the URL below with the path to your image -->
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80')] bg-cover bg-bottom filter brightness-75"></div>
            <!-- Overlay sombre pour la lisibilité -->
            <div class="absolute inset-0 bg-slate-900/50 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/30 to-slate-900/80"></div>
        </div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4 reveal">Prêt à trouver votre bien idéal ?</h2>
            <p class="text-gray-300 text-lg mb-8 reveal delay-1">Rejoignez ImmoPro et accédez à des centaines de propriétés exclusives.</p>
            <div class="flex flex-wrap justify-center gap-4 reveal delay-2">
                <a href="{{ route('biens.index') }}" class="btn-primary text-white font-semibold px-10 py-3.5 rounded-xl text-sm">Explorer maintenant</a>
                @guest
                    <a href="{{ route('register') }}" class="bg-white text-gray-900 font-semibold px-10 py-3.5 rounded-xl text-sm hover:bg-gray-100 transition">Créer un compte gratuit</a>
                @endguest
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer id="aide" class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="reveal delay-1">
                    @php $logo = \App\Models\Setting::get('agency_logo'); @endphp
                    <div class="flex items-center space-x-3 mb-3">
                        @if($logo)
                            <img src="{{ Storage::url($logo) }}" alt="Logo" class="w-10 h-10 rounded-lg object-cover border border-gray-700">
                        @else
                            <div class="w-10 h-10 rounded-lg btn-primary flex items-center justify-center">
                                <i class="fa-solid fa-building text-white text-lg"></i>
                            </div>
                        @endif
                        <span class="text-xl font-bold text-white">{{ \App\Models\Setting::get('agency_name', 'ImmoPro') }}</span>
                    </div>
                    <p class="text-sm mt-3">Votre agence immobilière de confiance en Guinée. Nous vous accompagnons dans tous vos projets immobiliers.</p>
                </div>
                <div class="reveal delay-2">
                    <h4 class="text-white font-semibold mb-3">Liens rapides</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('biens.index') }}" class="hover:text-white transition">Catalogue</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Connexion</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Inscription</a></li>
                    </ul>
                </div>
                <div class="reveal delay-3">
                    <h4 class="text-white font-semibold mb-3">Réseaux Sociaux</h4>
                    <div class="flex flex-col space-y-3 mt-4">
                        <a href="https://wa.me/224625997903?text=Bonjour ImmoPro, j'aimerais avoir des informations concernant vos services immobiliers." target="_blank" class="flex items-center text-sm hover:text-white transition group">
                            <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center mr-3 group-hover:bg-emerald-600 transition-colors">
                                <i class="fa-brands fa-whatsapp text-slate-400 group-hover:text-white"></i>
                            </div>
                            WhatsApp
                        </a>
                        <a href="#" target="_blank" class="flex items-center text-sm hover:text-white transition group">
                            <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center mr-3 group-hover:bg-gray-700 transition-colors">
                                <i class="fa-brands fa-facebook-f text-slate-400"></i>
                            </div>
                            Facebook
                        </a>
                        <a href="#" target="_blank" class="flex items-center text-sm hover:text-white transition group">
                            <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center mr-3 group-hover:bg-gray-700 transition-colors">
                                <i class="fa-brands fa-instagram text-slate-400"></i>
                            </div>
                            Instagram
                        </a>
                    </div>
                </div>
                <div class="reveal delay-3">
                    <h4 class="text-white font-semibold mb-3">Contact</h4>
                    <ul class="space-y-2 text-sm">
                        <li><i class="fa-solid fa-location-dot text-slate-500 mr-2"></i>Conakry, Guinée</li>
                        <li><i class="fa-solid fa-phone text-slate-500 mr-2"></i>{{ \App\Models\Setting::get('contact_phone', '+224 625 99 79 03') }}</li>
                        <li><i class="fa-solid fa-envelope text-slate-500 mr-2"></i><a href="mailto:{{ \App\Models\Setting::get('contact_email', 'josephbangoura0204@gmail.com') }}" class="hover:text-white transition">{{ \App\Models\Setting::get('contact_email', 'josephbangoura0204@gmail.com') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-10 pt-6 text-center text-sm">
                <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('agency_name', 'ImmoPro') }}. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        // Optionnel: décommenter pour jouer l'animation une seule fois
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px"
            });

            document.querySelectorAll('.reveal').forEach((el) => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
