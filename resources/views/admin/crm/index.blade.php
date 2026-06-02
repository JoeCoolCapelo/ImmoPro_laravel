<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-white leading-tight">
            {{ __('Pipeline des Ventes & Leads') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100 min-h-screen">
        <div class="max-w-[98%] mx-auto px-4">
            
            <div class="flex gap-6 overflow-x-auto pb-8 snap-x">
                
                {{-- Colonne 1: Leads --}}
                <div class="flex-shrink-0 w-80 snap-start">
                    <div class="flex items-center justify-between mb-4 px-2">
                        <h3 class="font-black text-slate-400 text-[10px] uppercase tracking-widest flex items-center">
                            <span class="w-2 h-2 rounded-full bg-amber-400 mr-2"></span>
                            Nouveaux Leads ({{ $leads->count() }})
                        </h3>
                    </div>
                    <div class="space-y-4">
                        @foreach($leads as $lead)
                            <div class="premium-card p-4 bg-white border-t-4 border-amber-400 shadow-sm hover:shadow-md transition-shadow cursor-pointer group">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">RDV à confirmer</span>
                                    <i class="fa-solid fa-ellipsis-vertical text-slate-300"></i>
                                </div>
                                <h4 class="text-xs font-black text-slate-900 mb-1 group-hover:text-indigo-600 transition-colors">{{ $lead->bien->titre ?? 'Bien supprimé' }}</h4>
                                <p class="text-[10px] font-bold text-slate-500 mb-3"><i class="fa-solid fa-user mr-1"></i> {{ $lead->client->name ?? 'Client inconnu' }}</p>
                                <div class="flex items-center justify-between pt-3 border-t border-slate-50">
                                    <span class="text-[9px] font-black text-slate-400">{{ $lead->date_visite->format('d/m H:i') }}</span>
                                    <a href="{{ route('visites.index') }}" class="h-6 w-6 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 hover:bg-amber-100 hover:text-amber-600 transition-colors">
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Colonne 2: RDV Confirmés --}}
                <div class="flex-shrink-0 w-80 snap-start">
                    <div class="flex items-center justify-between mb-4 px-2">
                        <h3 class="font-black text-slate-400 text-[10px] uppercase tracking-widest flex items-center">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                            RDV Confirmés ({{ $confirmed->count() }})
                        </h3>
                    </div>
                    <div class="space-y-4">
                        @foreach($confirmed as $c)
                            <div class="premium-card p-4 bg-white border-t-4 border-indigo-500 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-[9px] font-black text-indigo-400 uppercase tracking-tighter">En attente de visite</span>
                                </div>
                                <h4 class="text-xs font-black text-slate-900 mb-1">{{ $c->bien->titre ?? 'Bien supprimé' }}</h4>
                                <p class="text-[10px] font-bold text-slate-500 mb-3"><i class="fa-solid fa-user mr-1"></i> {{ $c->client->name ?? 'Client inconnu' }}</p>
                                <div class="flex items-center justify-between pt-3 border-t border-slate-50">
                                    <span class="text-[9px] font-black text-indigo-600">{{ $c->date_visite->format('d/m H:i') }}</span>
                                    <div class="flex gap-1">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $c->client->phone ?? '') }}" class="h-6 w-6 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-500 hover:bg-emerald-500 hover:text-white transition-colors">
                                            <i class="fa-brands fa-whatsapp text-[10px]"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Colonne 3: Négociation --}}
                <div class="flex-shrink-0 w-80 snap-start">
                    <div class="flex items-center justify-between mb-4 px-2">
                        <h3 class="font-black text-slate-400 text-[10px] uppercase tracking-widest flex items-center">
                            <span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span>
                            Négociation Chaude ({{ $negotiations->count() }})
                        </h3>
                    </div>
                    <div class="space-y-4">
                        @foreach($negotiations as $n)
                            <div class="premium-card p-4 bg-rose-50 border-t-4 border-rose-500 shadow-sm relative overflow-hidden">
                                <div class="absolute -right-2 -top-2 opacity-10">
                                    <i class="fa-solid fa-fire text-4xl"></i>
                                </div>
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-[9px] font-black text-rose-600 uppercase tracking-tighter">Client très intéressé</span>
                                </div>
                                <h4 class="text-xs font-black text-slate-900 mb-1">{{ $n->bien->titre ?? 'Bien supprimé' }}</h4>
                                <p class="text-[10px] font-bold text-slate-500 mb-3"><i class="fa-solid fa-user mr-1"></i> {{ $n->client->name ?? 'Client inconnu' }}</p>
                                <div class="flex items-center justify-between pt-3 border-t border-rose-100">
                                    <span class="text-[9px] font-black text-rose-600 font-bold italic">Visite effectuée</span>
                                    <a href="{{ route('transactions.index') }}" class="px-3 py-1 bg-slate-900 text-white rounded-lg text-[8px] font-black uppercase tracking-widest hover:bg-rose-600 transition-colors">
                                        Créer Acte
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Colonne 4: Conclu --}}
                <div class="flex-shrink-0 w-80 snap-start">
                    <div class="flex items-center justify-between mb-4 px-2">
                        <h3 class="font-black text-slate-400 text-[10px] uppercase tracking-widest flex items-center">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                            Dossiers Conclus ({{ $won->count() }})
                        </h3>
                    </div>
                    <div class="space-y-4">
                        @foreach($won as $w)
                            <div class="premium-card p-4 bg-emerald-50 border-t-4 border-emerald-500 shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-[9px] font-black text-emerald-600 uppercase tracking-tighter">Transaction #{{ $w->id }}</span>
                                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                </div>
                                <h4 class="text-xs font-black text-slate-900 mb-1">{{ $w->bien->titre ?? 'Bien supprimé' }}</h4>
                                <p class="text-sm font-black text-emerald-700">{{ number_format($w->montant, 0, ',', ' ') }} GNF</p>
                                <div class="flex items-center justify-between pt-3 border-t border-emerald-100 mt-2">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">Par {{ $w->agent->name ?? 'N/A' }}</span>
                                    <a href="{{ route('transactions.show', $w) }}" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest">Voir Détails</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
