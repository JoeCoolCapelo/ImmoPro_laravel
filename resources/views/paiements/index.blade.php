<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-black text-xl text-white leading-tight">
                    {{ __('Suivi des Loyers') }}
                </h2>
                <p class="text-xs text-white/60 mt-1">{{ $transaction->bien->titre }} — {{ $transaction->client->name }}</p>
            </div>
            <a href="{{ route('transactions.show', $transaction) }}" class="text-[10px] font-black uppercase tracking-widest text-white/60 hover:text-white transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Retour
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm">
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Résumé financier --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                @php
                    $totalLoyers = $paiements->where('statut', 'payé')->sum('montant_loyer');
                    $totalCommissions = $paiements->where('statut', 'payé')->sum('commission_montant');
                    $enAttente = $paiements->where('statut', 'en_attente')->count();
                    $enRetard = $paiements->filter(fn($p) => $p->statut === 'en_attente' && now()->isAfter($p->date_echeance))->count();
                @endphp
                <div class="premium-card p-6">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Loyer Mensuel</p>
                    <p class="text-2xl font-black text-slate-900 mt-1">{{ number_format($transaction->montant, 0, ',', ' ') }} <small class="text-xs text-slate-400">FCFA</small></p>
                </div>
                <div class="premium-card p-6">
                    <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Commission/Mois</p>
                    <p class="text-2xl font-black text-emerald-600 mt-1">{{ number_format(($transaction->montant * $transaction->commission_pourcentage) / 100, 0, ',', ' ') }} <small class="text-xs text-slate-400">FCFA ({{ $transaction->commission_pourcentage }}%)</small></p>
                </div>
                <div class="premium-card p-6">
                    <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest">Total Perçu</p>
                    <p class="text-2xl font-black text-indigo-600 mt-1">{{ number_format($totalCommissions, 0, ',', ' ') }} <small class="text-xs text-slate-400">FCFA</small></p>
                </div>
                <div class="premium-card p-6">
                    <p class="text-[9px] font-black text-rose-500 uppercase tracking-widest">En Retard</p>
                    <p class="text-2xl font-black {{ $enRetard > 0 ? 'text-rose-600' : 'text-slate-300' }} mt-1">{{ $enRetard }} <small class="text-xs text-slate-400">mois</small></p>
                </div>
            </div>

            {{-- Boutons de génération --}}
            @if(auth()->user()->hasRole('agent') || auth()->user()->hasRole('admin'))
                <div class="mb-8 flex justify-end gap-4">
                    <form method="POST" action="{{ route('paiements.generer-annee', $transaction) }}">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition shadow-sm">
                            <i class="fa-solid fa-calendar-days mr-2"></i> Générer l'échéancier annuel
                        </button>
                    </form>
                    <form method="POST" action="{{ route('paiements.generer', $transaction) }}">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition shadow-md">
                            <i class="fa-solid fa-plus mr-2"></i> Générer le prochain mois
                        </button>
                    </form>
                    <form method="POST" action="{{ route('paiements.encaisser-tout', $transaction) }}">
                        @csrf
                        <button type="submit" onclick="return confirm('Voulez-vous vraiment encaisser tous les mois en attente ?')" 
                            class="px-6 py-3 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">
                            <i class="fa-solid fa-money-bill-stack mr-2"></i> Tout Encaisser
                        </button>
                    </form>
                </div>
            @endif

            {{-- Cartes des échéances --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($paiements as $paiement)
                    @php
                        $isRetard = $paiement->statut === 'en_attente' && now()->isAfter($paiement->date_echeance);
                    @endphp
                    <div class="premium-card p-6 bg-white flex flex-col relative overflow-hidden group" 
                        x-data="{ 
                            status: '{{ $paiement->statut }}', 
                            loading: false,
                            datePaye: '{{ $paiement->date_paiement ? $paiement->date_paiement->translatedFormat('d M Y') : '' }}',
                            isRetard: {{ $isRetard ? 'true' : 'false' }},
                            async collect() {
                                if(!confirm('Confirmer la réception du loyer ?')) return;
                                this.loading = true;
                                try {
                                    const response = await fetch('{{ route('paiements.payer', $paiement) }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({ _method: 'PATCH' })
                                    });
                                    const data = await response.json();
                                    if (data.success) {
                                        this.status = 'payé';
                                        this.datePaye = data.date_paiement;
                                        this.isRetard = false;
                                    } else {
                                        alert('Erreur: ' + (data.message || 'Impossible d\'enregistrer le paiement.'));
                                    }
                                } catch (e) { 
                                    console.error(e);
                                    alert('Erreur de connexion au serveur. Vérifiez votre connexion.');
                                }
                                this.loading = false;
                            }
                        }">
                        
                        {{-- Background Accent --}}
                        <div class="absolute -right-4 -top-4 w-16 h-16 opacity-5 rounded-full group-hover:scale-150 transition-transform duration-700"
                            :class="status === 'payé' ? 'bg-emerald-500' : (isRetard ? 'bg-rose-500' : 'bg-amber-500')"></div>

                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-lg font-black text-slate-900 leading-tight">{{ $paiement->date_echeance->translatedFormat('F Y') }}</h4>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Échéance : {{ $paiement->date_echeance->translatedFormat('d M Y') }}</p>
                            </div>
                            
                            <template x-if="status === 'payé'">
                                <span class="h-8 w-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs shadow-sm">
                                    <i class="fa-solid fa-check"></i>
                                </span>
                            </template>
                            <template x-if="status !== 'payé' && isRetard">
                                <span class="h-8 w-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xs animate-pulse shadow-sm">
                                    <i class="fa-solid fa-exclamation-triangle"></i>
                                </span>
                            </template>
                            <template x-if="status === 'en_attente' && !isRetard">
                                <span class="h-8 w-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xs shadow-sm">
                                    <i class="fa-solid fa-clock"></i>
                                </span>
                            </template>
                        </div>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-[10px] font-black text-slate-400 uppercase">Loyer Net</span>
                                <span class="text-sm font-black text-slate-900">{{ number_format($paiement->montant_loyer, 0, ',', ' ') }} <small>GNF</small></span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-[10px] font-black text-slate-400 uppercase">Commission ({{ $transaction->commission_pourcentage }}%)</span>
                                <span class="text-sm font-black text-emerald-600">+ {{ number_format($paiement->commission_montant, 0, ',', ' ') }} <small>GNF</small></span>
                            </div>
                            <template x-if="datePaye">
                                <div class="p-2 rounded-lg bg-emerald-50 border border-emerald-100 text-center">
                                    <p class="text-[10px] font-black text-emerald-700 uppercase tracking-tighter">Encaissement validé le <span x-text="datePaye"></span></p>
                                </div>
                            </template>
                        </div>

                        <div class="mt-auto pt-4 border-t border-slate-50">
                            <template x-if="status === 'payé'">
                                <a href="{{ route('paiements.pdf', $paiement) }}" 
                                    class="flex items-center justify-center gap-2 w-full py-3 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition shadow-lg shadow-slate-200">
                                    <i class="fa-solid fa-file-pdf"></i> Télécharger Quittance
                                </a>
                            </template>

                            <template x-if="status !== 'payé' && ({{ auth()->user()->hasRole('agent') || auth()->user()->hasRole('admin') ? 'true' : 'false' }})">
                                <button @click="collect()" :disabled="loading"
                                    class="flex items-center justify-center gap-2 w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition shadow-lg shadow-emerald-100 disabled:opacity-50">
                                    <i class="fa-solid fa-coins" x-show="!loading"></i>
                                    <i class="fa-solid fa-circle-notch fa-spin" x-show="loading"></i>
                                    <span x-text="loading ? 'Traitement...' : 'Encaisser Loyer'"></span>
                                </button>
                            </template>

                            <template x-if="status !== 'payé' && !({{ auth()->user()->hasRole('agent') || auth()->user()->hasRole('admin') ? 'true' : 'false' }})">
                                <div class="w-full py-3 bg-slate-100 text-slate-400 rounded-xl text-[10px] font-black text-center uppercase">
                                    Paiement en attente
                                </div>
                            </template>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-dashed border-slate-200">
                        <i class="fa-solid fa-calendar-xmark text-4xl text-slate-200 mb-4"></i>
                        <p class="text-slate-400 font-bold">Aucune échéance générée pour le moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
