<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-white leading-tight">
                {{ __('Mes Rendez-vous & Visites') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm">
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if($visites->isEmpty())
                <div class="premium-card p-12 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">{{ __('Aucune visite planifiée') }}</h3>
                    <p class="text-slate-500 mt-2">Vous n'avez pas encore de rendez-vous de visite enregistrés.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($visites as $visite)
                        <div class="premium-card overflow-hidden flex flex-col h-full group">
                            {{-- Header image or placeholder --}}
                            <div class="h-48 relative overflow-hidden bg-slate-900">
                                @if($visite->bien && $visite->bien->images->count() > 0)
                                    <img src="{{ Storage::url($visite->bien->images->first()->path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-violet-600">
                                        <i class="fa-solid fa-calendar-check text-5xl text-white/20"></i>
                                    </div>
                                @endif
                                <div class="absolute top-4 right-4">
                                    @php
                                        $statusClasses = [
                                            'en_attente' => 'bg-amber-500 text-white',
                                            'confirmée' => 'bg-indigo-600 text-white',
                                            'effectuée' => 'bg-emerald-500 text-white',
                                            'annulée' => 'bg-rose-500 text-white',
                                            'finalisée' => 'bg-emerald-600 text-white ring-4 ring-white/20',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg {{ $statusClasses[$visite->statut] ?? 'bg-slate-500 text-white' }}">
                                        {{ $visite->statut }}
                                    </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="mb-4">
                                    <h4 class="text-lg font-black text-slate-900 mb-1 truncate">
                                        @if($visite->bien)
                                            <a href="{{ route('biens.show', $visite->bien) }}" class="hover:text-indigo-600 transition-colors">
                                                {{ $visite->bien->titre }}
                                            </a>
                                        @else
                                            <span class="text-rose-500 italic">{{ __('Propriété supprimée') }}</span>
                                        @endif
                                    </h4>
                                    <p class="text-xs font-bold text-slate-400 flex items-center">
                                        <i class="fa-solid fa-location-dot mr-1.5 text-indigo-500"></i>
                                        {{ $visite->bien ? $visite->bien->ville : 'N/A' }}
                                    </p>
                                </div>

                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center p-3 bg-slate-50 rounded-2xl border border-slate-100">
                                        <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center text-indigo-600 shadow-sm mr-4">
                                            <i class="fa-solid fa-calendar"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Date & Heure</p>
                                            <p class="text-xs font-black text-slate-900">{{ $visite->date_visite->translatedFormat('d F Y') }} — {{ $visite->date_visite->format('H:i') }}</p>
                                        </div>
                                    </div>

                                    @if(!auth()->user()->hasRole('client'))
                                    <div class="flex items-center p-3 bg-slate-50 rounded-2xl border border-slate-100">
                                        <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center text-indigo-600 shadow-sm mr-4">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Client</p>
                                            <p class="text-xs font-black text-slate-900">{{ $visite->client->name }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                {{-- Feedback & WhatsApp for Agents --}}
                                @if(auth()->user()->hasRole('agent') || auth()->user()->hasRole('admin'))
                                    <div class="mt-6 pt-6 border-t border-slate-100">
                                        @if($visite->statut === 'effectuée' || $visite->statut === 'finalisée')
                                            @if(empty($visite->feedback_agent))
                                                <form method="POST" action="{{ route('visites.update', $visite) }}" class="space-y-3">
                                                    @csrf @method('PATCH')
                                                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Ressenti de la visite (pour le propriétaire)</label>
                                                    <textarea name="feedback_agent" rows="2" required class="w-full bg-slate-50 border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-indigo-500" placeholder="Saisissez votre bilan ici..."></textarea>
                                                    <button type="submit" class="w-full py-2 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 transition shadow-sm">
                                                        Enregistrer le Bilan & Verrouiller
                                                    </button>
                                                </form>
                                            @else
                                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 relative overflow-hidden">
                                                    <div class="absolute right-2 top-2 opacity-10">
                                                        <i class="fa-solid fa-lock text-xl"></i>
                                                    </div>
                                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Bilan enregistré (Verrouillé)</p>
                                                    <p class="text-xs font-bold text-slate-700 italic">"{{ $visite->feedback_agent }}"</p>
                                                </div>
                                            @endif

                                            <div class="mt-4 flex gap-2">
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $visite->client->phone ?? '') }}?text={{ urlencode('Bonjour ' . ($visite->client->name ?? '') . ', je reviens vers vous concernant la visite du bien « ' . ($visite->bien->titre ?? 'cette propriété') . ' ». Qu\'en avez-vous pensé ?') }}" target="_blank"
                                                    class="flex-1 py-2 bg-emerald-100 text-emerald-700 rounded-lg text-[9px] font-black text-center uppercase hover:bg-emerald-200 transition">
                                                    <i class="fa-brands fa-whatsapp mr-1 text-xs"></i> WhatsApp Client
                                                </a>
                                                @if($visite->bien && $visite->bien->owner)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $visite->bien->owner->phone ?? '') }}?text={{ urlencode('Bonjour ' . ($visite->bien->owner->name ?? '') . ', j\'ai effectué la visite de votre bien « ' . ($visite->bien->titre ?? 'votre propriété') . ' » avec un client. Je vous ferai un retour détaillé très prochainement.') }}" target="_blank"
                                                    class="flex-1 py-2 bg-indigo-100 text-indigo-700 rounded-lg text-[9px] font-black text-center uppercase hover:bg-indigo-200 transition">
                                                    <i class="fa-brands fa-whatsapp mr-1 text-xs"></i> WhatsApp Propriétaire
                                                </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                @endif
                                
                                {{-- Affichage du Feedback pour le Propriétaire --}}
                                @if(auth()->user()->hasRole('proprietaire') && !empty($visite->feedback_agent))
                                    <div class="mt-6 pt-6 border-t border-slate-100">
                                        <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-2xl">
                                            <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-2">
                                                <i class="fa-solid fa-comment-dots mr-1"></i> Bilan de votre agent
                                            </p>
                                            <p class="text-xs font-bold text-indigo-900 italic leading-relaxed">
                                                "{{ $visite->feedback_agent }}"
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Client Interest --}}
                                {{-- Client Interest (Alpine.js AJAX version) --}}
                                @if(auth()->user()->hasRole('client') && $visite->statut === 'effectuée' && $visite->user_id === auth()->id())
                                    <div x-data="{ 
                                        interested: {{ $visite->interested === null ? 'null' : ($visite->interested ? 'true' : 'false') }},
                                        loading: false,
                                        async submitInterest(val) {
                                            if (this.loading) return;
                                            this.loading = true;
                                            try {
                                                const response = await fetch('{{ route('visites.update', $visite) }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'Accept': 'application/json'
                                                    },
                                                    body: JSON.stringify({ 
                                                        _method: 'PATCH',
                                                        interested: val 
                                                    })
                                                });
                                                const data = await response.json();
                                                if (data.success) {
                                                    this.interested = data.interested;
                                                }
                                            } catch (e) {
                                                console.error(e);
                                            }
                                            this.loading = false;
                                        }
                                    }" class="mt-6 pt-6 border-t border-slate-100">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">
                                            <i class="fa-solid fa-heart text-rose-500 mr-1"></i> Êtes-vous intéressé par ce bien ?
                                        </p>
                                        <div class="flex gap-3">
                                            <button @click="submitInterest(1)" :disabled="loading" 
                                                :class="interested === true ? 'bg-emerald-500 text-white' : 'bg-slate-50 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600'"
                                                class="flex-1 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition shadow-sm disabled:opacity-50">
                                                <span x-show="!loading">Oui, beaucoup !</span>
                                                <span x-show="loading"><i class="fa-solid fa-spinner fa-spin"></i></span>
                                            </button>
                                            <button @click="submitInterest(0)" :disabled="loading" 
                                                :class="interested === false ? 'bg-rose-500 text-white' : 'bg-slate-50 text-slate-600 hover:bg-rose-50 hover:text-rose-600'"
                                                class="flex-1 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition shadow-sm disabled:opacity-50">
                                                <span x-show="!loading">Pas vraiment</span>
                                                <span x-show="loading"><i class="fa-solid fa-spinner fa-spin"></i></span>
                                            </button>
                                        </div>
                                        <template x-if="interested !== null">
                                            <p class="mt-3 text-[9px] font-bold text-slate-400 italic text-center">
                                                Votre choix : <span x-text="interested ? 'Intéressé' : 'Non intéressé'"></span>
                                            </p>
                                        </template>
                                    </div>
                                @endif

                                {{-- WhatsApp vers l'agent pour le propriétaire (uniquement si visite effectuée) --}}
                                @if(auth()->user()->hasRole('proprietaire') && $visite->statut === 'effectuée' && $visite->bien && $visite->bien->user_id === auth()->id())
                                    @php
                                        $agent = $visite->bien->agent;
                                        $agentPhone = $agent ? preg_replace('/[^0-9]/', '', $agent->phone ?? '') : '';
                                        $whatsappMsg = urlencode('Bonjour ' . ($agent->name ?? 'agent') . ', comment s\'est passée la visite de mon bien « ' . ($visite->bien->titre ?? '') . ' » ? Merci pour votre retour.');
                                    @endphp
                                    <div class="mt-6 pt-6 border-t border-slate-100">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">
                                            <i class="fa-solid fa-circle-check text-emerald-500 mr-1"></i> Visite effectuée — Contacter votre agent
                                        </p>
                                        @if($agent && $agentPhone)
                                            <a href="https://wa.me/{{ $agentPhone }}?text={{ $whatsappMsg }}" target="_blank"
                                                class="flex items-center justify-center gap-2 w-full py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition shadow-sm">
                                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                                Demander le bilan à {{ $agent->name }}
                                            </a>
                                        @elseif($agent)
                                            <div class="flex items-center justify-center gap-2 w-full py-2.5 bg-slate-100 text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest cursor-not-allowed" title="Numéro de téléphone non renseigné">
                                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                                {{ $agent->name }} — Téléphone non renseigné
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center gap-2 w-full py-2.5 bg-slate-100 text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest cursor-not-allowed">
                                                <i class="fa-solid fa-user-slash text-sm"></i>
                                                Aucun agent assigné
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                {{-- Statut Transaction --}}
                                @if($visite->bien && in_array($visite->bien->statut, ['vendu', 'loué']))
                                    <div class="mt-4 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3">
                                        <div class="h-10 w-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200">
                                            <i class="fa-solid fa-file-invoice-dollar text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Transaction Finalisée</p>
                                            <p class="text-xs font-bold text-slate-700">Le bien a été {{ $visite->bien->nature === 'location' ? 'loué' : 'vendu' }} avec succès !</p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Actions --}}
                                <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        @if(auth()->user()->hasPermissionTo('visites.validate'))
                                            {{-- Si déjà annulée ou effectuée, on ne peut plus rien changer --}}
                                            @if(in_array($visite->statut, ['annulée', 'effectuée', 'finalisée']))
                                                <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                                    Dossier Clôturé
                                                </span>
                                            @else
                                                <div class="flex flex-wrap gap-2">
                                                    {{-- Boutons pour "En Attente" --}}
                                                    @if($visite->statut === 'en_attente')
                                                        <form method="POST" action="{{ route('visites.update', $visite) }}">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="statut" value="confirmée">
                                                            <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-indigo-700 transition shadow-sm">
                                                                Confirmer
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('visites.update', $visite) }}">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="statut" value="annulée">
                                                            <button type="submit" onclick="return confirm('Annuler cette visite ?')" class="px-3 py-2 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-rose-600 hover:text-white transition">
                                                                Annuler
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- Bouton pour "Confirmée" --}}
                                                    @if($visite->statut === 'confirmée')
                                                        <form method="POST" action="{{ route('visites.update', $visite) }}">
                                                            @csrf @method('PATCH')
                                                            <input type="hidden" name="statut" value="effectuée">
                                                            <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-emerald-600 transition shadow-md">
                                                                <i class="fa-solid fa-check mr-1"></i> Effectuer
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        {{-- Badge Supprimée (Admin seulement) --}}
                                        @if($visite->trashed())
                                            <span class="px-2 py-1 bg-rose-100 text-rose-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-rose-200">
                                                <i class="fa-solid fa-trash mr-1"></i> Masquée
                                            </span>
                                        @endif

                                        @if(!auth()->user()->hasRole('client') && in_array($visite->statut, ['confirmée', 'effectuée']) && !$visite->trashed())
                                            <a href="{{ route('visites.pdf', $visite) }}" class="h-10 w-10 flex items-center justify-center bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-xl transition-all shadow-sm" title="Télécharger l'attestation">
                                                <i class="fa-solid fa-file-pdf text-lg"></i>
                                            </a>
                                        @endif

                                        {{-- Bouton Supprimer (Cacher pour Agent, Supprimer pour Client en attente) --}}
                                        @if(auth()->user()->can('delete', $visite) && !$visite->trashed())
                                            <form method="POST" action="{{ route('visites.destroy', $visite) }}" onsubmit="return confirm('{{ auth()->user()->hasRole('agent') ? 'Masquer cette visite de votre historique ?' : 'Annuler cette demande ?' }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="h-10 w-10 flex items-center justify-center bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl transition-all shadow-sm" title="Supprimer / Masquer">
                                                    <i class="fa-solid {{ auth()->user()->hasRole('agent') ? 'fa-eye-slash' : 'fa-trash-can' }}"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                {{-- Bouton Finaliser Vente/Location (Agent uniquement, client intéressé) --}}
                                @if((auth()->user()->hasRole('agent') || auth()->user()->hasRole('admin'))
                                    && $visite->statut === 'effectuée'
                                    && $visite->interested === true
                                    && $visite->bien
                                    && !in_array($visite->bien->statut, ['vendu', 'loué'])
                                    && !$visite->trashed())
                                    <div class="mt-4 pt-4 border-t-2 border-emerald-100">
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-emerald-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                            </span>
                                            <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">
                                                Client intéressé — Prêt à finaliser !
                                            </p>
                                        </div>
                                        <a href="{{ route('transactions.create', ['bien_id' => $visite->bien_id, 'user_id' => $visite->user_id, 'visite_id' => $visite->id]) }}"
                                            class="flex items-center justify-center gap-2 w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                            <i class="fa-solid fa-handshake text-sm"></i>
                                            Finaliser la {{ $visite->bien->nature === 'location' ? 'Location' : 'Vente' }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-10">
                    {{ $visites->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
