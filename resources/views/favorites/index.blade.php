<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-slate-800 leading-tight">
                {{ __('Mes Favoris') }}
            </h2>
            <span class="px-4 py-1 bg-rose-50 text-rose-600 rounded-full text-xs font-black uppercase tracking-widest border border-rose-100">
                <i class="fa-solid fa-heart mr-2"></i> {{ $favorites->total() }} Bien(s)
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($favorites->isEmpty())
                <div class="premium-card p-24 text-center bg-white">
                    <div class="w-24 h-24 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-regular fa-heart text-4xl text-rose-200"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">{{ __('Votre liste est vide') }}</h3>
                    <p class="text-slate-500 mt-4 max-w-sm mx-auto font-medium">Parcourez notre catalogue et cliquez sur le cœur pour sauvegarder les propriétés qui vous plaisent.</p>
                    <div class="mt-10">
                        <a href="{{ route('biens.index') }}" class="btn-premium">
                            Explorer le catalogue
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($favorites as $bien)
                        <div class="premium-card group overflow-hidden flex flex-col h-full bg-white relative">
                            {{-- Favorite Button --}}
                            <div class="absolute top-4 right-4 z-10">
                                <form action="{{ route('favorites.toggle', $bien) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2.5 bg-white/90 backdrop-blur rounded-xl shadow-lg hover:scale-110 transition-transform">
                                        <i class="fa-solid fa-heart text-rose-500 text-lg"></i>
                                    </button>
                                </form>
                            </div>

                            {{-- Image --}}
                            <div class="relative h-64 overflow-hidden bg-slate-900">
                                @if($bien->images->count() > 0)
                                    <img src="{{ Storage::url($bien->images->where('is_main', true)->first()->path ?? $bien->images->first()->path) }}" alt="{{ $bien->titre }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                                        <i class="fa-solid fa-camera text-3xl text-slate-200"></i>
                                    </div>
                                @endif
                                
                                <div class="absolute bottom-4 left-4">
                                    <div class="bg-indigo-600/90 backdrop-blur text-white px-4 py-2 rounded-xl shadow-lg inline-block font-black text-lg">
                                        {{ number_format($bien->prix, 0, ',', ' ') }} <small class="text-[10px] uppercase opacity-75">GNF</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-black text-slate-900 group-hover:text-indigo-600 transition-colors truncate">{{ $bien->titre }}</h3>
                                </div>
                                <p class="text-xs font-bold text-slate-400 mb-4 flex items-center">
                                    <i class="fa-solid fa-location-dot mr-2 text-indigo-500"></i>
                                    {{ $bien->ville }}
                                </p>

                                <div class="grid grid-cols-2 gap-4 py-4 border-y border-slate-50 my-4 text-xs font-black text-slate-500 uppercase tracking-widest">
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-ruler-combined mr-2 text-slate-300"></i>
                                        {{ $bien->surface }} m²
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-door-open mr-2 text-slate-300"></i>
                                        {{ $bien->nb_pieces }} p.
                                    </div>
                                </div>

                                <div class="mt-auto flex justify-between items-center">
                                    <span class="text-[10px] font-black text-indigo-500 bg-indigo-50 px-2 py-1 rounded-md uppercase">{{ $bien->nature }}</span>
                                    <a href="{{ route('biens.show', $bien) }}" class="text-sm font-black text-slate-900 hover:text-indigo-600 transition-colors">
                                        Voir l'annonce →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-16">
                    {{ $favorites->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
