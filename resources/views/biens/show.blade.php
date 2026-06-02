<x-app-layout>
    <div class="pt-10 pb-12 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Nouveau Header intégré pour supprimer l'espace --}}
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('biens.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-slate-100 hover:bg-slate-50 transition-colors">
                        <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    <h2 class="font-black text-2xl text-slate-800 leading-tight truncate max-w-md">
                        {{ $bien->titre }}
                    </h2>
                </div>
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('biens.pdf', $bien) }}" target="_blank" class="p-2.5 bg-white text-rose-600 rounded-xl border border-slate-200 hover:bg-rose-50 transition-all shadow-sm flex items-center space-x-2">
                        <i class="fa-solid fa-file-pdf text-lg"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest hidden md:inline">Fiche PDF</span>
                    </a>

                    @auth
                        <form action="{{ route('favorites.toggle', $bien) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-2.5 bg-white rounded-xl border border-slate-200 hover:bg-rose-50 transition-all shadow-sm group">
                                @if(auth()->user()->favorites->contains($bien->id))
                                    <i class="fa-solid fa-heart text-rose-500 text-lg"></i>
                                @else
                                    <i class="fa-regular fa-heart text-slate-400 group-hover:text-rose-500 text-lg transition-colors"></i>
                                @endif
                            </button>
                        </form>
                    @endauth

                    @can('update', $bien)
                        <a href="{{ route('biens.edit', $bien) }}" class="p-2.5 bg-white text-slate-600 rounded-xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </a>
                    @endcan

                    @can('delete', $bien)
                        <form method="POST" action="{{ route('biens.destroy', $bien) }}" onsubmit="return confirm('Supprimer ce bien définitivement ?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2.5 bg-white text-rose-500 rounded-xl border border-slate-200 hover:bg-rose-50 transition-all shadow-sm">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm">
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Validation / Admin Actions Bar --}}
            @can('validate', $bien)
                <div class="glass-card mb-6 p-6 flex flex-col md:flex-row justify-between items-center border-indigo-100 bg-indigo-50/30">
                    <div class="mb-4 md:mb-0">
                        <h4 class="text-indigo-900 font-black text-lg">Actions de gestion</h4>
                        <p class="text-indigo-600 text-xs font-bold uppercase tracking-widest">Statut actuel : {{ $bien->statut }}</p>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        @if($bien->statut === 'en_attente' || $bien->statut === 'brouillon')
                            <form method="POST" action="{{ route('biens.publier', $bien) }}" class="flex items-center space-x-3 bg-white p-2 rounded-2xl shadow-sm border border-indigo-100">
                                @csrf
                                @method('PATCH')
                                @role('admin')
                                    <select name="agent_id" class="text-xs font-bold border-none bg-slate-50 rounded-xl focus:ring-0">
                                        <option value="{{ auth()->id() }}">Moi-même</option>
                                        @foreach($agents as $agent)
                                            @if($agent->id !== auth()->id())
                                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @endrole
                                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                                    {{ __('Publier') }}
                                </button>
                            </form>
                        @endif
                        @if($bien->statut === 'publié')
                            <form method="POST" action="{{ route('biens.rejeter', $bien) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-white text-amber-600 border border-amber-200 px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-amber-50 transition-colors">
                                    {{ __('Rejeter') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endcan

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                {{-- Left Content --}}
                <div class="lg:col-span-2 space-y-10">
                    
                    {{-- Premium Gallery --}}
                    <div class="grid grid-cols-4 gap-4">
                        <div class="col-span-4 md:col-span-3 h-[500px] rounded-[2rem] overflow-hidden shadow-2xl relative group">
                            @if($bien->images->where('is_main', true)->first())
                                <img src="{{ Storage::url($bien->images->where('is_main', true)->first()->path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                    <svg class="h-20 w-20 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif

                            @if($bien->statut === 'vendu' || $bien->statut === 'loué')
                                <div class="absolute top-10 left-0 w-64 h-64 overflow-hidden z-20 pointer-events-none">
                                    <div class="absolute top-12 -left-16 bg-emerald-500 text-white font-black text-sm uppercase tracking-widest py-2 w-80 text-center -rotate-45 shadow-2xl ring-4 ring-white/20">
                                        {{ $bien->statut }}
                                    </div>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>
                        <div class="hidden md:flex md:flex-col space-y-4">
                            @foreach($bien->images->where('is_main', false)->take(3) as $image)
                                <div class="h-[155px] rounded-3xl overflow-hidden shadow-lg">
                                    <img src="{{ Storage::url($image->path) }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                            @if($bien->images->count() > 4)
                                <div class="h-[155px] rounded-3xl bg-slate-900 flex items-center justify-center text-white font-black text-lg">
                                    +{{ $bien->images->count() - 4 }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Header Details --}}
                    <div class="premium-card p-10">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-50 pb-8 mb-8">
                            <div>
                                <div class="flex items-center space-x-3 mb-4">
                                    <span class="status-badge bg-indigo-50 text-indigo-600">{{ $bien->type }}</span>
                                    <span class="status-badge bg-emerald-50 text-emerald-600">{{ $bien->nature }}</span>
                                </div>
                                <h1 class="text-4xl font-black text-slate-900 mb-2">{{ $bien->titre }}</h1>
                                <p class="text-slate-500 flex items-center font-bold">
                                    <svg class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ $bien->adresse }}, {{ $bien->ville }}
                                </p>
                            </div>
                            <div class="mt-6 md:mt-0 text-right">
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Prix de vente</p>
                                <div class="text-4xl font-black text-indigo-600">
                                    {{ number_format($bien->prix, 0, ',', ' ') }} <small class="text-sm">GNF</small>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10">
                            <div class="p-4 bg-slate-50/80 rounded-3xl border border-slate-100 transition-all hover:bg-white hover:shadow-md group">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-white rounded-2xl text-indigo-500 shadow-sm group-hover:scale-110 transition-transform">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Surface</p>
                                        <p class="text-base font-black text-slate-800">{{ $bien->surface }} m²</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-slate-50/80 rounded-3xl border border-slate-100 transition-all hover:bg-white hover:shadow-md group">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-white rounded-2xl text-indigo-500 shadow-sm group-hover:scale-110 transition-transform">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pièces</p>
                                        <p class="text-base font-black text-slate-800">{{ $bien->nb_pieces ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-slate-50/80 rounded-3xl border border-slate-100 transition-all hover:bg-white hover:shadow-md group">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-white rounded-2xl text-indigo-500 shadow-sm group-hover:scale-110 transition-transform">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Statut</p>
                                        <p class="text-base font-black text-slate-800 capitalize">{{ $bien->statut }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-slate-50/80 rounded-3xl border border-slate-100 transition-all hover:bg-white hover:shadow-md group">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-white rounded-2xl text-indigo-500 shadow-sm group-hover:scale-110 transition-transform">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Référence</p>
                                        <p class="text-base font-black text-slate-800">#{{ str_pad($bien->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-10">
                            <div>
                                <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center">
                                    <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-3 text-sm">
                                        <i class="fa-solid fa-align-left"></i>
                                    </span>
                                    {{ __('Description') }}
                                </h3>
                                <div class="text-slate-600 leading-relaxed font-medium whitespace-pre-line text-lg">
                                    {{ $bien->description }}
                                </div>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center">
                                    <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-3 text-sm">
                                        <i class="fa-solid fa-list-check"></i>
                                    </span>
                                    {{ __('Fiche technique') }}
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between py-3 border-b border-slate-50">
                                        <span class="text-slate-500 font-bold">Type de bien</span>
                                        <span class="text-slate-900 font-black capitalize">{{ $bien->type }}</span>
                                    </div>
                                    <div class="flex justify-between py-3 border-b border-slate-50">
                                        <span class="text-slate-500 font-bold">Nature du contrat</span>
                                        <span class="text-slate-900 font-black capitalize">{{ $bien->nature }}</span>
                                    </div>
                                    <div class="flex justify-between py-3 border-b border-slate-50">
                                        <span class="text-slate-500 font-bold">Ville</span>
                                        <span class="text-slate-900 font-black">{{ $bien->ville }}</span>
                                    </div>
                                    <div class="flex justify-between py-3 border-b border-slate-50">
                                        <span class="text-slate-500 font-bold">Quartier / Adresse</span>
                                        <span class="text-slate-900 font-black">{{ $bien->adresse }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Geolocation Map --}}
                        @if($bien->latitude && $bien->longitude)
                        <div class="mt-12">
                            <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center">
                                <svg class="h-6 w-6 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ __('Localisation précise') }}
                            </h3>
                            <div id="property-map" class="h-[400px] w-full rounded-3xl shadow-lg border border-slate-100 z-0 overflow-hidden"></div>
                            
                            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const map = L.map('property-map').setView([{{ $bien->latitude }}, {{ $bien->longitude }}], 15);
                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                    }).addTo(map);
                                    L.marker([{{ $bien->latitude }}, {{ $bien->longitude }}]).addTo(map)
                                        .bindPopup('<b>{{ $bien->titre }}</b><br>{{ $bien->ville }}')
                                        .openPopup();
                                });
                            </script>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Right Sidebar --}}
                <div class="space-y-8">
                    {{-- Contact Card --}}
                    <div class="premium-card p-8 bg-slate-900 text-white relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 opacity-10">
                            <svg class="h-40 w-40" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                        </div>
                        
                        <h3 class="text-xl font-black mb-6">{{ __('Planifier une visite') }}</h3>
                        
                        @if(in_array($bien->statut, ['vendu', 'loué']))
                            <div class="p-6 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-center">
                                <i class="fa-solid fa-lock text-emerald-500 text-2xl mb-3"></i>
                                <p class="text-sm font-black text-emerald-400 uppercase tracking-widest">{{ __('Ce bien n\'est plus disponible') }}</p>
                                <p class="text-[10px] text-slate-400 mt-1 italic">{{ __('Une transaction a déjà été finalisée.') }}</p>
                            </div>
                        @elseif(auth()->check())
                            @if(auth()->user()->hasRole('client') || auth()->user()->hasRole('admin'))
                                <a href="{{ route('visites.create', ['bien_id' => $bien->id]) }}" class="block w-full py-4 text-center bg-indigo-500 text-white font-black rounded-[1.25rem] hover:bg-indigo-400 transition shadow-xl shadow-indigo-500/20 uppercase tracking-widest text-sm">
                                    {{ __('Prendre rendez-vous') }}
                                </a>
                            @else
                                <div class="p-4 bg-white/10 rounded-2xl border border-white/10">
                                    <p class="text-xs font-bold text-slate-300 italic">
                                        {{ __('Action réservée aux clients. Vous êtes connecté en tant que') }} {{ auth()->user()->getRoleNames()->first() }}.
                                    </p>
                                </div>
                            @endif
                        @else
                            <p class="text-sm text-slate-400 mb-6 font-medium">Connectez-vous pour demander une visite guidée de cette propriété.</p>
                            <a href="{{ route('login') }}" class="block w-full py-4 text-center bg-white text-slate-900 font-black rounded-[1.25rem] hover:bg-slate-100 transition shadow-xl shadow-white/10 uppercase tracking-widest text-sm">
                                {{ __('Se connecter') }}
                            </a>
                        @endif

                        {{-- WhatsApp Quick Contact --}}
                        @if(!in_array($bien->statut, ['vendu', 'loué']) && auth()->check() && auth()->user()->hasRole('client'))
                            <div class="mt-4 pt-4 border-t border-white/10 text-center">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('agency_phone', '224000000000')) }}?text={{ urlencode('Bonjour ' . \App\Models\Setting::get('agency_name', 'ImmoPro') . ', je suis intéressé par le bien : ' . $bien->titre . ' (Ref: #' . str_pad($bien->id, 5, '0', STR_PAD_LEFT) . '). Pouvez-vous me donner plus d\'informations ? ' . route('biens.show', $bien)) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center text-emerald-400 hover:text-emerald-300 transition-colors text-xs font-black uppercase tracking-widest">
                                    <i class="fa-brands fa-whatsapp text-lg mr-2"></i>
                                    {{ __('Contact Rapide WhatsApp') }}
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Owner Info --}}
                    <div class="premium-card p-8">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">{{ __('Informations Propriétaire') }}</h4>
                        <div class="flex items-center">
                            <div class="h-16 w-16 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-3xl flex items-center justify-center text-white text-2xl font-black shadow-lg">
                                {{ substr($bien->owner->name, 0, 1) }}
                            </div>
                            <div class="ms-4">
                                <p class="font-black text-slate-900 text-lg">{{ $bien->owner->name }}</p>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Particulier certifié</p>
                            </div>
                        </div>
                        <div class="mt-6 pt-6 border-t border-slate-50">
                            <div class="flex justify-between text-sm font-bold">
                                <span class="text-slate-400">Inscrit depuis</span>
                                <span class="text-slate-900">{{ $bien->owner->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Agent Assigned (if published) --}}
                    @if($bien->agent)
                    <div class="premium-card p-8 bg-indigo-50/50 border-indigo-100">
                        <h4 class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-6">{{ __('Agent responsable') }}</h4>
                        <div class="flex items-center">
                            <div class="h-12 w-12 bg-white rounded-2xl flex items-center justify-center text-indigo-600 font-black border border-indigo-100 shadow-sm">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div class="ms-4">
                                <p class="font-black text-slate-900">{{ $bien->agent->name }}</p>
                                <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Expert Immobilier</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
