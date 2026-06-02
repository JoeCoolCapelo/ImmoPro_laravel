<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-white leading-tight">
                {{ __('Catalogue Immobilier') }}
            </h2>
            @can('create', App\Models\Bien::class)
                <a href="{{ route('biens.create') }}" class="btn-premium flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Ajouter un bien') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Notifications --}}
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm animate-fade-in">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            {{-- Advanced Filter Section --}}
            <div class="glass-card mb-12 overflow-hidden border-indigo-100/50">
                <div class="p-6 md:p-8">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-indigo-600 rounded-lg shadow-lg shadow-indigo-200">
                            <i class="fa-solid fa-magnifying-glass text-white text-sm"></i>
                        </div>
                        <h3 class="text-sm font-black text-white uppercase tracking-widest">Recherche Avancée</h3>
                    </div>

                    <form action="{{ route('biens.index') }}" method="GET" class="space-y-6">
                        {{-- Row 1: Search & Type --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div class="md:col-span-5 relative">
                                <i class="fa-solid fa-keyboard absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    placeholder="Rechercher un lieu, un titre..." 
                                    class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-indigo-500 focus:border-indigo-500 transition-all font-bold text-sm">
                            </div>
                            <div class="md:col-span-3">
                                <select name="type" class="w-full py-3 rounded-2xl border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-indigo-500 font-bold text-sm">
                                    <option value="">Tous les types</option>
                                    @foreach(['appartement','maison','terrain','bureau','commerce'] as $t)
                                        <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <select name="nature" class="w-full py-3 rounded-2xl border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-indigo-500 font-bold text-sm">
                                    <option value="">Nature (Toutes)</option>
                                    <option value="vente" {{ request('nature') == 'vente' ? 'selected' : '' }}>Vente</option>
                                    <option value="location" {{ request('nature') == 'location' ? 'selected' : '' }}>Location</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <button type="submit" class="w-full h-full bg-slate-900 text-white font-black rounded-2xl hover:bg-indigo-600 transition-all shadow-lg hover:shadow-indigo-200 uppercase tracking-widest text-[10px]">
                                    Trouver
                                </button>
                            </div>
                        </div>

                        {{-- Row 2: Price & Surface --}}
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 pt-4 border-t border-slate-50">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Prix Minimum (GNF)</label>
                                <div class="relative">
                                    <i class="fa-solid fa-money-bill-wave absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                    <input type="number" name="prix_min" value="{{ request('prix_min') }}" placeholder="Ex: 500 000" 
                                        class="w-full pl-11 py-2.5 rounded-xl border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-indigo-500 font-bold text-xs">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Prix Maximum (GNF)</label>
                                <div class="relative">
                                    <i class="fa-solid fa-money-bill-trend-up absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                    <input type="number" name="prix_max" value="{{ request('prix_max') }}" placeholder="Ex: 5 000 000" 
                                        class="w-full pl-11 py-2.5 rounded-xl border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-indigo-500 font-bold text-xs">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Surface Min (m²)</label>
                                <div class="relative">
                                    <i class="fa-solid fa-ruler-combined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                    <input type="number" name="surface_min" value="{{ request('surface_min') }}" placeholder="Ex: 100" 
                                        class="w-full pl-11 py-2.5 rounded-xl border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-indigo-500 font-bold text-xs">
                                </div>
                            </div>
                            <div class="flex items-end">
                                <a href="{{ route('biens.index') }}" class="w-full py-2.5 bg-slate-100 text-slate-500 text-center rounded-xl hover:bg-slate-200 transition-all font-black text-[10px] uppercase tracking-widest">
                                    <i class="fa-solid fa-rotate mr-2"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Results Grid --}}
            @if($biens->isEmpty())
                <div class="premium-card p-20 text-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">{{ __('Aucun résultat trouvé') }}</h3>
                    <p class="text-slate-500 mt-2">Essayez de modifier vos filtres ou de réinitialiser la recherche.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($biens as $bien)
                        <div class="premium-card group overflow-hidden flex flex-col h-full">
                            {{-- Image Container with Looping Animation --}}
                            <div class="relative h-64 overflow-hidden bg-slate-900 group/slider">
                                @if($bien->images->count() > 0)
                                    <style>
                                        @keyframes steppedScroll {
                                            @php 
                                                $count = $bien->images->count();
                                                $step = 100 / $count;
                                                $pause = $step * 0.8; // Stay for 80% of the step time
                                            @endphp
                                            @foreach($bien->images as $index => $image)
                                                {{ $index * $step }}% { transform: translateX(-{{ ($index / ($count * 2)) * 100 }}%); }
                                                {{ ($index * $step) + $pause }}% { transform: translateX(-{{ ($index / ($count * 2)) * 100 }}%); }
                                            @endforeach
                                            100% { transform: translateX(-50%); }
                                        }
                                        .animate-stepped-scroll-{{ $bien->id }} {
                                            animation: steppedScroll {{ $bien->images->count() * 4 }}s ease-in-out infinite;
                                        }
                                        .animate-stepped-scroll-{{ $bien->id }}:hover {
                                            animation-play-state: paused;
                                        }
                                    </style>
                                    <div class="flex h-full {{ $bien->images->count() > 1 ? 'animate-stepped-scroll-'.$bien->id : '' }}" 
                                         style="width: {{ $bien->images->count() * 200 }}%">
                                        @foreach($bien->images as $image)
                                            <div class="h-full" style="width: {{ 100 / ($bien->images->count() * 2) }}%">
                                                <img src="{{ Storage::url($image->path) }}" alt="{{ $bien->titre }}" class="w-full h-full object-contain">
                                            </div>
                                        @endforeach
                                        {{-- Duplicate for seamless loop --}}
                                        @foreach($bien->images as $image)
                                            <div class="h-full" style="width: {{ 100 / ($bien->images->count() * 2) }}%">
                                                <img src="{{ Storage::url($image->path) }}" alt="{{ $bien->titre }}" class="w-full h-full object-contain">
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                                        <i class="fa-solid fa-camera text-3xl text-slate-200"></i>
                                    </div>
                                @endif
                                
                                {{-- Badges --}}
                                <div class="absolute top-4 left-4 flex flex-col space-y-2">
                                    <span class="px-3 py-1 bg-white/90 backdrop-blur text-[10px] font-black uppercase tracking-widest text-slate-900 rounded-lg shadow-sm">
                                        {{ $bien->nature }}
                                    </span>
                                </div>

                                {{-- Sold/Rented Ribbon --}}
                                @if($bien->statut === 'vendu' || $bien->statut === 'loué')
                                    <div class="absolute top-0 left-0 w-32 h-32 overflow-hidden z-20 pointer-events-none">
                                        <div class="absolute top-6 -left-8 bg-emerald-500 text-white font-black text-[10px] uppercase tracking-widest py-1 w-40 text-center -rotate-45 shadow-lg">
                                            {{ $bien->statut }}
                                        </div>
                                    </div>
                                @endif

                                {{-- Favorite Button --}}
                                @auth
                                    <div class="absolute top-4 right-4 z-10">
                                        <form action="{{ route('favorites.toggle', $bien) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2.5 bg-white/90 backdrop-blur rounded-xl shadow-lg hover:scale-110 transition-transform group/fav">
                                                @if(auth()->user()->favorites->contains($bien->id))
                                                    <i class="fa-solid fa-heart text-rose-500 text-lg"></i>
                                                @else
                                                    <i class="fa-regular fa-heart text-slate-400 group-hover/fav:text-rose-500 text-lg transition-colors"></i>
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                @endauth
                                
                                <div class="absolute bottom-4 left-4 right-4">
                                    <div class="bg-indigo-600/90 backdrop-blur text-white px-4 py-2 rounded-xl shadow-lg inline-block font-black text-lg">
                                        {{ number_format($bien->prix, 0, ',', ' ') }} <small class="text-[10px] uppercase opacity-75">GNF</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Content - More Compact --}}
                            <div class="p-3 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-1">
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors truncate max-w-[180px]">{{ $bien->titre }}</h3>
                                        <p class="text-[9px] font-bold text-slate-400 mt-0.5 flex items-center">
                                            <svg class="h-2.5 w-2.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                            {{ $bien->ville }}
                                        </p>
                                    </div>
                                    <span class="text-[9px] font-black text-indigo-500 bg-indigo-50 px-1.5 py-0.5 rounded-md uppercase">{{ $bien->type }}</span>
                                </div>

                                <div class="py-2 border-y border-slate-50 my-2 text-[10px] font-bold text-slate-500">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="h-3 w-3 mr-1 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                            {{ $bien->surface }} m²
                                        </div>
                                        <div>{{ $bien->nb_pieces }} p.</div>
                                    </div>
                                </div>

                                <div class="mt-auto pt-3 flex justify-between items-center">
                                    <div class="flex items-center text-[10px] font-bold text-slate-400">
                                        <i class="fa-solid fa-eye mr-1.5 text-indigo-400"></i>
                                        {{ $bien->vues ?? 0 }} vues
                                    </div>
                                    <a href="{{ route('biens.show', $bien) }}" class="text-xs font-black text-indigo-600 hover:text-indigo-800 transition-colors">
                                        {{ __('Détails') }} →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-16">
                    {{ $biens->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
