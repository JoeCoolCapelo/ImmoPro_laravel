<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-white leading-tight">
                {{ __('Tableau de bord') }}
            </h2>
            <div class="text-xs text-white/60 font-black uppercase tracking-widest">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @role('admin')
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">
                        <div class="premium-card p-6 bg-slate-900 text-white relative overflow-hidden border-none">
                            <div class="absolute -right-4 -bottom-4 opacity-10">
                                <i class="fa-solid fa-vault text-6xl"></i>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Chiffre d'Affaires Global</p>
                            <p class="text-2xl font-black">{{ number_format($stats['total_volume'] ?? 0, 0, ',', ' ') }} <small class="text-[10px]">GNF</small></p>
                            <p class="text-[9px] font-bold text-slate-500 mt-2 italic">Volume total des ventes finalisées</p>
                        </div>
                        <div class="premium-card p-6 bg-indigo-600 text-white relative overflow-hidden border-none shadow-xl shadow-indigo-200">
                            <div class="absolute -right-4 -bottom-4 opacity-10">
                                <i class="fa-solid fa-hand-holding-dollar text-6xl"></i>
                            </div>
                            <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-1">Revenu Réel Agence</p>
                            <p class="text-2xl font-black">{{ number_format($stats['total_commissions'] ?? 0, 0, ',', ' ') }} <small class="text-[10px]">GNF</small></p>
                            <p class="text-[9px] font-bold text-indigo-100 mt-2 italic">Total des commissions perçues</p>
                        </div>
                        <div class="premium-card p-6 bg-white relative overflow-hidden group">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Activité Visites</p>
                            <p class="text-2xl font-black text-slate-900">{{ $stats['total_visites'] ?? 0 }}</p>
                            <div class="mt-2 flex items-center text-[9px] font-black text-emerald-500">
                                <i class="fa-solid fa-arrow-up mr-1"></i> Global
                            </div>
                        </div>
                        <div class="premium-card p-6 bg-white relative overflow-hidden group">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Base Utilisateurs</p>
                            <p class="text-2xl font-black text-slate-900">{{ $stats['total_users'] ?? 0 }}</p>
                            <div class="mt-2 flex items-center text-[9px] font-black text-indigo-500">
                                <i class="fa-solid fa-user-group mr-1"></i> Actifs
                            </div>
                        </div>
                    </div>
                @endrole

                @role('agent')
                    <div class="premium-card p-6 bg-gradient-to-br from-indigo-600 to-indigo-800 text-white relative overflow-hidden group">
                        <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-white/10 rounded-xl">
                                <i class="fa-solid fa-money-bill-trend-up text-xl"></i>
                            </div>
                            <div class="ms-4">
                                <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-1">Commissions (Ventes)</p>
                                <div class="flex items-baseline">
                                    <p class="text-2xl font-black">{{ number_format($stats['commissions_vente'], 0, ',', ' ') }}</p>
                                    <span class="ms-1 text-[10px] font-bold text-indigo-200 uppercase">GNF</span>
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-white/10 flex justify-between items-center">
                            <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest">Locations</p>
                            <p class="text-lg font-black">{{ number_format($stats['commissions_location'], 0, ',', ' ') }} <small class="text-[8px] opacity-70">GNF</small></p>
                        </div>
                    </div>

                    <div class="premium-card p-6 bg-white relative overflow-hidden group">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-emerald-500 rounded-xl shadow-lg shadow-emerald-200">
                                <i class="fa-solid fa-chart-pie text-xl text-white"></i>
                            </div>
                            <div class="ms-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Taux de Conversion</p>
                                <p class="text-3xl font-black text-slate-900">{{ $stats['conversion_rate'] }}%</p>
                            </div>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500" style="width: {{ $stats['conversion_rate'] }}%"></div>
                        </div>
                        <p class="mt-2 text-[10px] font-bold text-slate-500 italic">Visites effectuées vs Finalisations</p>
                    </div>

                    <div class="premium-card p-6 bg-white relative overflow-hidden group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="p-3 bg-rose-500 rounded-xl shadow-lg shadow-rose-200">
                                    <i class="fa-solid fa-calendar-check text-xl text-white"></i>
                                </div>
                                <div class="ms-4">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Visites Effectuées</p>
                                    <p class="text-3xl font-black text-slate-900">{{ $stats['visites_effectuees'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1">Annulées</p>
                                <p class="text-xl font-black text-slate-400">{{ $stats['visites_annulees'] }}</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-slate-50">
                            <p class="text-[10px] font-bold text-indigo-600">À venir (Confirmées) : <span class="text-sm font-black">{{ $stats['visites_confirmees'] }}</span></p>
                            <p class="text-[10px] font-bold text-amber-500">En attente : <span class="text-sm font-black">{{ $stats['visites_attente'] }}</span></p>
                        </div>
                    </div>
                @endrole

                @role('proprietaire')
                <div class="col-span-1 md:col-span-2 lg:col-span-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="premium-card p-6 bg-white relative overflow-hidden group">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-emerald-500 rounded-xl shadow-lg shadow-emerald-200">
                                <i class="fa-solid fa-wallet text-xl text-white"></i>
                            </div>
                            <div class="ms-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Revenu Net Réel</p>
                                <p class="text-2xl font-black text-slate-900">{{ number_format($stats['revenu_net'], 0, ',', ' ') }} <span class="text-[10px] text-slate-400">GNF</span></p>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-slate-500">Après commissions et dépenses</p>
                    </div>

                    <div class="premium-card p-6 bg-white relative overflow-hidden group">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-rose-500 rounded-xl shadow-lg shadow-rose-200">
                                <i class="fa-solid fa-tools text-xl text-white"></i>
                            </div>
                            <div class="ms-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Dépenses Entretien</p>
                                <p class="text-2xl font-black text-slate-900">{{ number_format($stats['total_depenses'], 0, ',', ' ') }} <span class="text-[10px] text-slate-400">GNF</span></p>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-rose-500">Réparations et maintenance</p>
                    </div>

                    <div class="premium-card p-6 bg-white relative overflow-hidden group">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-indigo-500 rounded-xl shadow-lg shadow-indigo-200">
                                <i class="fa-solid fa-gem text-xl text-white"></i>
                            </div>
                            <div class="ms-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Patrimoine Estimé</p>
                                <p class="text-2xl font-black text-slate-900">{{ number_format($stats['mon_patrimoine_estime'], 0, ',', ' ') }} <span class="text-[10px] text-slate-400">GNF</span></p>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-indigo-600">Valeur actuelle des biens en ligne</p>
                    </div>

                    <div class="premium-card p-6 bg-slate-900 text-white relative overflow-hidden group">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-white/10 rounded-xl">
                                <i class="fa-solid fa-handshake-slash text-xl"></i>
                            </div>
                            <div class="ms-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Marge Négociation</p>
                                <p class="text-2xl font-black">{{ number_format($stats['marge_negociation'], 0, ',', ' ') }} <span class="text-[10px] text-slate-400">GNF</span></p>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 italic">Différence prix initial vs final</p>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2 lg:col-span-3 flex justify-end">
                    <a href="{{ route('biens.owner-report') }}" class="btn-premium px-6 py-3 text-xs">
                        <i class="fa-solid fa-file-pdf mr-2"></i> Télécharger mon Bilan de Performance
                    </a>
                </div>
                @endrole

                @role('client')
                    {{-- Statistiques Rapides --}}
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="premium-card p-6 bg-gradient-to-br from-indigo-600 to-violet-700 text-white relative overflow-hidden group">
                            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                            <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-1">Visites Demandées</p>
                            <p class="text-4xl font-black mb-2">{{ $stats['visites_demandees'] }}</p>
                            <p class="text-[10px] text-indigo-100 italic">{{ $stats['visites_confirmees'] }} confirmées</p>
                        </div>

                        <div class="premium-card p-6 bg-white relative overflow-hidden group">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Coups de Coeur</p>
                            <p class="text-4xl font-black text-slate-900">{{ $stats['favoris_count'] }}</p>
                            <div class="mt-4 flex -space-x-2">
                                @foreach($stats['mes_favoris'] as $fav)
                                    <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-100 overflow-hidden">
                                        <img src="{{ $fav->images->first() ? asset('storage/' . $fav->images->first()->image_path) : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80' }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="premium-card p-6 bg-white border-l-4 border-emerald-500">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Contrats Actifs</p>
                            <p class="text-4xl font-black text-slate-900">{{ $stats['transactions_effectuees'] }}</p>
                            <p class="text-[10px] font-bold text-emerald-600 mt-2"><i class="fa-solid fa-circle-check mr-1"></i> Dossier à jour</p>
                        </div>

                        <div class="premium-card p-6 bg-slate-900 text-white flex flex-col justify-center text-center">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Besoin d'aide ?</p>
                            @php
                                $phone = $stats['mon_agent']->phone ?? $stats['agency_phone'];
                                $agentName = $stats['mon_agent']->name ?? 'notre équipe';
                                $message = urlencode("Bonjour " . $agentName . ", je suis " . auth()->user()->name . ". J'ai besoin d'assistance concernant mon dossier sur ImmoPro.");
                            @endphp
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $phone) }}?text={{ $message }}" target="_blank" 
                                class="btn-premium py-2 text-[10px] font-black uppercase tracking-widest bg-indigo-600 hover:bg-emerald-500 transition-colors">
                                <i class="fa-brands fa-whatsapp mr-2 text-sm"></i> Contacter Support
                            </a>
                        </div>
                    </div>

                    {{-- Mes Locations & Échéances --}}
                    @if($stats['mes_locations']->count() > 0)
                        <div class="col-span-1 md:col-span-2 lg:col-span-2 space-y-6">
                            <div class="flex items-center justify-between">
                                <h3 class="font-black text-slate-900 text-sm uppercase tracking-widest">
                                    <i class="fa-solid fa-key mr-2 text-indigo-600"></i> Mes Locations Actives
                                </h3>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                @foreach($stats['mes_locations'] as $loc)
                                    @if($loc->bien)
                                    <div class="premium-card p-4 bg-white flex items-center gap-4 group hover:border-indigo-200 transition-colors">
                                        <div class="h-16 w-16 rounded-xl overflow-hidden shadow-sm">
                                            <img src="{{ $loc->bien->images->first() ? asset('storage/' . $loc->bien->images->first()->image_path) : asset('img/default-bien.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-bold text-slate-900">{{ $loc->bien->titre }}</h4>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $loc->bien->ville }} — {{ $loc->bien->quartier }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs font-black text-slate-900">{{ number_format($loc->montant, 0, ',', ' ') }} GNF</p>
                                            <a href="{{ route('paiements.index', $loc) }}" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest hover:underline mt-1 block">Suivre mes loyers</a>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="col-span-1 lg:col-span-1 space-y-6">
                            <div class="flex items-center justify-between">
                                <h3 class="font-black text-slate-900 text-sm uppercase tracking-widest">
                                    <i class="fa-solid fa-calendar-clock mr-2 text-rose-600"></i> Prochains Loyers
                                </h3>
                            </div>
                            <div class="space-y-4">
                                @forelse($stats['prochaines_echeances'] as $echeance)
                                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex justify-between items-center">
                                        <div>
                                            <p class="text-xs font-black text-slate-900">{{ $echeance->date_echeance->translatedFormat('F Y') }}</p>
                                            <p class="text-[10px] text-rose-500 font-bold italic">Échéance : {{ $echeance->date_echeance->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-black text-slate-900">{{ number_format($echeance->montant_loyer, 0, ',', ' ') }} GNF</p>
                                            <span class="text-[9px] font-black text-amber-600 uppercase">À régler</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-6 text-center bg-white rounded-2xl border border-dashed border-slate-200">
                                        <p class="text-[10px] font-bold text-slate-400 italic">Aucune échéance à venir.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @else
                        {{-- Si pas de location, afficher les favoris --}}
                        <div class="col-span-1 md:col-span-2 lg:col-span-3 space-y-6">
                            <div class="flex items-center justify-between">
                                <h3 class="font-black text-slate-900 text-sm uppercase tracking-widest">
                                    <i class="fa-solid fa-heart mr-2 text-rose-600"></i> Mes Favoris Récents
                                </h3>
                                <a href="{{ route('favorites.index') }}" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Voir tout</a>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @forelse($stats['mes_favoris'] as $fav)
                                    <div class="premium-card group overflow-hidden bg-white">
                                        <div class="h-40 relative">
                                            <img src="{{ $fav->images->first() ? asset('storage/' . $fav->images->first()->image_path) : asset('img/default-bien.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                                            <div class="absolute bottom-3 left-3">
                                                <p class="text-[10px] font-black text-white uppercase tracking-widest">{{ $fav->ville }}</p>
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <h4 class="text-xs font-black text-slate-900 truncate mb-2">{{ $fav->titre }}</h4>
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs font-black text-indigo-600">{{ number_format($fav->prix, 0, ',', ' ') }} GNF</span>
                                                <a href="{{ route('biens.show', $fav) }}" class="h-7 w-7 rounded-lg bg-slate-900 text-white flex items-center justify-center text-[10px] hover:bg-indigo-600 transition">
                                                    <i class="fa-solid fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full p-12 text-center bg-white rounded-[2.5rem] border border-dashed border-slate-200">
                                        <i class="fa-solid fa-heart-crack text-4xl text-slate-200 mb-4"></i>
                                        <p class="text-slate-400 font-bold italic">Vous n'avez pas encore de favoris.</p>
                                        <a href="{{ route('biens.index') }}" class="mt-4 inline-block text-[10px] font-black text-indigo-600 uppercase tracking-widest underline">Explorer les biens</a>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                @endrole
            </div>

            @role('admin|agent|proprietaire')
            {{-- Graphical Stats --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="premium-card p-6 bg-white">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-black text-slate-900 dark:text-white text-sm uppercase tracking-widest">
                            <i class="fa-solid fa-chart-line mr-2 text-indigo-600"></i>
                            {{ auth()->user()->hasRole('proprietaire') ? 'Évolution de l\'Audience (Vues)' : 'Croissance du Catalogue' }}
                        </h3>
                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">6 derniers mois</span>
                    </div>
                    <div class="h-[250px]">
                        <canvas id="biensChart"></canvas>
                    </div>
                </div>

                <div class="premium-card p-6 bg-white">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-black text-slate-900 dark:text-white text-sm uppercase tracking-widest">
                            <i class="fa-solid fa-chart-area mr-2 text-emerald-600"></i>
                            {{ auth()->user()->hasRole('proprietaire') ? 'Revenus Mensuels (GNF)' : 'Volume de Ventes (GNF)' }}
                        </h3>
                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">6 derniers mois</span>
                    </div>
                    <div class="h-[250px]">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Chart.defaults.font.family = "'Figtree', sans-serif";
                    Chart.defaults.color = '#94a3b8';

                    new Chart(document.getElementById('biensChart'), {
                        type: 'line',
                        data: {
                            labels: @json($stats['chart_labels'] ?? []),
                            datasets: [{
                                label: '{{ auth()->user()->hasRole('proprietaire') ? 'Vues cumulées' : 'Nouveaux Biens' }}',
                                data: @json($stats['biens_chart'] ?? []),
                                borderColor: '#4f46e5',
                                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                borderWidth: 4,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#4f46e5'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }
                        }
                    });

                    new Chart(document.getElementById('revenueChart'), {
                        type: 'bar',
                        data: {
                            labels: @json($stats['chart_labels'] ?? []),
                            datasets: [{
                                label: 'Revenu',
                                data: @json($stats['transactions_chart'] ?? []),
                                backgroundColor: '#10b981',
                                borderRadius: 8,
                                barThickness: 20
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true, grid: { borderDash: [5, 5] } }, x: { grid: { display: false } } }
                        }
                    });
                });
            </script>
            @endrole

            @role('proprietaire')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                {{-- Prochaines Visites --}}
                <div class="lg:col-span-2 premium-card p-6 bg-white">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-black text-slate-900 text-sm uppercase tracking-widest">Prochaines Visites</h3>
                        <a href="{{ route('visites.index') }}" class="text-[10px] font-black text-indigo-600 hover:underline">Voir tout</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($stats['prochaines_visites'] as $visite)
                            <div class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-indigo-200 transition-all">
                                <div class="h-12 w-12 bg-white rounded-xl flex flex-col items-center justify-center border border-slate-200 shadow-sm">
                                    <span class="text-[10px] font-black text-slate-400 uppercase">{{ $visite->date_visite->translatedFormat('M') }}</span>
                                    <span class="text-lg font-black text-slate-900 leading-none">{{ $visite->date_visite->format('d') }}</span>
                                </div>
                                <div class="ms-4 flex-1">
                                    <h5 class="text-sm font-bold text-slate-900">{{ $visite->bien->titre }}</h5>
                                    <p class="text-xs text-slate-500">{{ $visite->date_visite->format('H:i') }} • {{ $visite->client->name }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider 
                                    {{ $visite->statut == 'confirmée' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $visite->statut }}
                                </span>
                            </div>
                        @empty
                            <div class="py-12 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-calendar-xmark text-slate-200 text-2xl"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-400">Aucune visite programmée</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Carte Agent --}}
                <div class="premium-card p-6 bg-slate-900 text-white relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-500/10 rounded-full -mr-16 -mt-16"></div>
                    <h3 class="font-black text-indigo-300 text-[10px] uppercase tracking-widest mb-6">Mon Agent Dédié</h3>
                    @if($stats['mon_agent'])
                        <div class="flex flex-col items-center text-center">
                            <div class="h-24 w-24 rounded-full border-4 border-white/10 p-1 mb-4">
                                <img src="https://i.pravatar.cc/200?u={{ $stats['mon_agent']->email }}" class="w-full h-full object-cover rounded-full">
                            </div>
                            <h4 class="text-xl font-black">{{ $stats['mon_agent']->name }}</h4>
                            <p class="text-xs text-indigo-200 mb-6 italic">Expert en gestion de patrimoine</p>
                            
                            <div class="grid grid-cols-2 gap-3 w-full">
                                <a href="tel:{{ $stats['mon_agent']->phone ?? '+224000000' }}" class="flex items-center justify-center p-3 bg-white/10 rounded-xl hover:bg-white/20 transition-all group">
                                    <i class="fa-solid fa-phone text-indigo-300 group-hover:scale-110 transition-transform"></i>
                                </a>
                                <a href="https://wa.me/{{ $stats['mon_agent']->phone ?? '000' }}" class="flex items-center justify-center p-3 bg-white/10 rounded-xl hover:bg-white/20 transition-all group">
                                    <i class="fa-brands fa-whatsapp text-emerald-400 group-hover:scale-110 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-full py-8">
                            <i class="fa-solid fa-user-tie text-4xl text-slate-700 mb-4"></i>
                            <p class="text-xs text-slate-500 font-bold">Assignation en cours...</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                {{-- Feedbacks --}}
                <div class="premium-card p-6 bg-white">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-black text-slate-900 text-sm uppercase tracking-widest">Retours d'Expérience</h3>
                        <i class="fa-solid fa-comment-dots text-slate-200"></i>
                    </div>
                    <div class="space-y-6">
                        @forelse($stats['derniers_feedbacks'] as $feedback)
                            <div class="relative pl-6 border-l-2 border-indigo-100">
                                <div class="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-indigo-500"></div>
                                <p class="text-xs font-black text-slate-400 uppercase mb-1">{{ $feedback->date_visite->diffForHumans() }}</p>
                                <h6 class="text-sm font-bold text-slate-900 mb-2">{{ $feedback->bien->titre }}</h6>
                                <div class="p-3 bg-indigo-50 rounded-xl rounded-tl-none italic text-xs text-indigo-900">
                                    "{{ $feedback->feedback_agent }}"
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-slate-400">
                                <p class="text-xs font-bold italic">En attente de premiers retours après visites.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Documents --}}
                <div class="premium-card p-6 bg-white">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-black text-slate-900 text-sm uppercase tracking-widest">Centre Documentaire</h3>
                        <i class="fa-solid fa-folder-open text-slate-200"></i>
                    </div>
                    <div class="space-y-3">
                        @forelse($stats['mes_documents'] as $doc)
                            <a href="{{ Storage::url($doc->path) }}" target="_blank" class="flex items-center p-3 hover:bg-slate-50 rounded-xl transition-all border border-transparent hover:border-slate-100">
                                <div class="h-10 w-10 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <div class="ms-3 flex-1">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $doc->titre }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $doc->created_at->format('d/m/Y') }}</p>
                                </div>
                                <i class="fa-solid fa-download text-slate-300 text-xs"></i>
                            </a>
                        @empty
                            <div class="py-8 text-center text-slate-400">
                                <p class="text-xs font-bold italic">Aucun document disponible pour le moment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
                {{-- Price Requests --}}
                <div class="premium-card p-6 bg-white">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-black text-slate-900 text-sm uppercase tracking-widest">Requêtes de Prix</h3>
                        <i class="fa-solid fa-tag text-slate-200"></i>
                    </div>
                    <div class="space-y-4">
                        @forelse($stats['mes_demandes_prix'] as $request)
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="flex justify-between items-start">
                                    <p class="text-[10px] font-black text-slate-400 uppercase">{{ $request->bien->titre }}</p>
                                    <span class="text-[9px] font-black px-2 py-0.5 rounded-md 
                                        {{ $request->statut == 'en_attente' ? 'bg-amber-100 text-amber-700' : ($request->statut == 'accepté' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700') }}">
                                        {{ $request->statut }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-xs font-bold text-slate-400 line-through">{{ number_format($request->old_price, 0, ',', ' ') }}</span>
                                    <i class="fa-solid fa-arrow-right text-[10px] text-slate-300"></i>
                                    <span class="text-xs font-black text-slate-900">{{ number_format($request->new_price, 0, ',', ' ') }} GNF</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-6 text-xs text-slate-400 italic">Aucune demande en cours.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- My Properties List for Owner --}}
            <div class="premium-card p-8 bg-white mb-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="font-black text-slate-900 text-sm uppercase tracking-widest">Gestion de mon Patrimoine</h3>
                    <span class="text-xs text-slate-400 font-bold">{{ $stats['biens_count'] }} propriétés gérées</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach(\App\Models\Bien::where('user_id', auth()->id())->latest()->get() as $bien)
                    <div class="p-4 rounded-[2rem] bg-slate-50 border border-slate-100 group">
                        <div class="h-32 rounded-2xl overflow-hidden mb-4 relative">
                            @if($bien->images->count() > 0)
                                <img src="{{ Storage::url($bien->images->first()->path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center"><i class="fa-solid fa-image text-slate-300"></i></div>
                            @endif
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 bg-white/90 backdrop-blur rounded-lg text-[9px] font-black uppercase shadow-sm">
                                    {{ $bien->statut }}
                                </span>
                            </div>
                        </div>
                        <h4 class="font-bold text-slate-900 text-sm mb-4 truncate">{{ $bien->titre }}</h4>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <form action="{{ route('biens.suspendre', $bien) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all
                                    {{ $bien->statut == 'suspendu' ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-slate-200 text-slate-700 hover:bg-slate-300' }}">
                                    {{ $bien->statut == 'suspendu' ? 'Réactiver' : 'Suspendre' }}
                                </button>
                            </form>
                            
                            <button onclick="document.getElementById('modal-price-{{ $bien->id }}').classList.remove('hidden')" 
                                class="w-full py-2 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-600 transition-all">
                                Nouveau Prix
                            </button>
                        </div>

                        {{-- Quick Price Modal --}}
                        <div id="modal-price-{{ $bien->id }}" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                            <div class="bg-white rounded-[2.5rem] p-8 max-w-md w-full shadow-2xl">
                                <h3 class="text-lg font-black text-slate-900 mb-2 uppercase">Modifier le Prix</h3>
                                <p class="text-xs text-slate-500 mb-6">Proposez un nouveau prix pour <strong>{{ $bien->titre }}</strong>. L'administrateur devra valider le changement.</p>
                                
                                <form action="{{ route('price-requests.store') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="bien_id" value="{{ $bien->id }}">
                                    <div>
                                        <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Prix Actuel : {{ number_format($bien->prix, 0, ',', ' ') }} GNF</label>
                                        <input type="number" name="new_price" required placeholder="Nouveau prix (GNF)" class="w-full bg-slate-50 border-slate-100 rounded-2xl text-sm font-bold p-4">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Motif de la demande</label>
                                        <textarea name="reason" placeholder="Ex: Promotion temporaire, alignement marché..." class="w-full bg-slate-50 border-slate-100 rounded-2xl text-sm font-bold p-4" rows="3"></textarea>
                                    </div>
                                    <div class="flex gap-3">
                                        <button type="button" onclick="document.getElementById('modal-price-{{ $bien->id }}').classList.add('hidden')" class="flex-1 py-4 text-xs font-black uppercase text-slate-400">Annuler</button>
                                        <button type="submit" class="flex-1 btn-premium py-4">Envoyer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endrole

            @role('agent')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                {{-- Tasks Section --}}
                <div class="premium-card p-8 bg-white">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-black text-slate-900 text-sm uppercase tracking-widest">Ma To-Do List</h3>
                        <span class="px-2 py-1 bg-slate-100 text-slate-500 text-[10px] font-black rounded-md">{{ $stats['mes_taches']->count() }} tâches</span>
                    </div>
                    
                    <form action="{{ route('tasks.store') }}" method="POST" class="mb-6 flex gap-2">
                        @csrf
                        <input type="text" name="title" required placeholder="Nouvelle tâche..." class="flex-1 bg-slate-50 border-slate-100 rounded-xl text-xs font-bold text-slate-700 focus:ring-indigo-500">
                        <button type="submit" class="p-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-sm">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </form>

                    <div class="space-y-3">
                        @forelse($stats['mes_taches'] as $task)
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl group border border-transparent hover:border-slate-200 transition-all">
                                <div class="flex items-center">
                                    <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-5 h-5 rounded-md border-2 border-slate-300 flex items-center justify-center hover:border-indigo-500 transition">
                                            <i class="fa-solid fa-check text-[10px] text-indigo-600 opacity-0 group-hover:opacity-30"></i>
                                        </button>
                                    </form>
                                    <span class="ms-4 text-xs font-bold text-slate-700">{{ $task->title }}</span>
                                </div>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-slate-300 hover:text-rose-500 transition opacity-0 group-hover:opacity-100">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-center py-6 text-xs text-slate-400 italic">Aucune tâche en cours. Félicitations !</p>
                        @endforelse
                    </div>
                </div>

                {{-- Feedback Needed Section --}}
                <div class="premium-card p-8 bg-white">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-black text-slate-900 text-sm uppercase tracking-widest">Visites à clôturer</h3>
                        <i class="fa-solid fa-comment-dots text-indigo-600"></i>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($stats['visites_sans_feedback'] as $visite)
                            <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100/50">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="text-xs font-black text-slate-900">{{ $visite->bien?->titre ?? __('Propriété supprimée') }}</p>
                                        <p class="text-[10px] text-slate-500">{{ $visite->client?->name ?? __('Client inconnu') }} • {{ $visite->date_visite->format('d/m/Y') }}</p>
                                    </div>
                                    <a href="{{ route('visites.index') }}" class="text-[10px] font-black text-indigo-600 uppercase hover:underline">Saisir feedback</a>
                                </div>
                                <div class="flex gap-2">
                                    <a href="https://wa.me/{{ $visite->client?->phone ?? '' }}?text=Bonjour {{ $visite->client?->name ?? __('Client') }}, que pensez-vous de la visite du bien {{ $visite->bien?->titre ?? __('cette propriété') }} ?" target="_blank" 
                                        class="flex-1 py-2 bg-emerald-100 text-emerald-700 rounded-lg text-[9px] font-black text-center uppercase hover:bg-emerald-200 transition">
                                        <i class="fa-brands fa-whatsapp mr-1"></i> Relancer Client
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-6 text-xs text-slate-400 italic">Tous les feedbacks sont à jour.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @endrole

            @role('admin')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                {{-- Leaderboard Agents --}}
                <div class="lg:col-span-2 premium-card p-6 bg-white">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-black text-slate-900 text-sm uppercase tracking-widest">Performance des Agents</h3>
                        <i class="fa-solid fa-trophy text-amber-400"></i>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left border-b border-slate-50">
                                    <th class="pb-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Agent</th>
                                    <th class="pb-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Ventes</th>
                                    <th class="pb-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Volume (GNF)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($stats['agent_leaderboard'] as $agent)
                                    <tr class="group">
                                        <td class="py-4">
                                            <div class="flex items-center">
                                                <img src="https://i.pravatar.cc/100?u={{ $agent->email }}" class="h-8 w-8 rounded-full mr-3 border-2 border-white shadow-sm">
                                                <span class="text-sm font-bold text-slate-700">{{ $agent->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 text-center">
                                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-xs font-black">
                                                {{ $agent->transactions_as_agent_count }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-right">
                                            <span class="text-sm font-black text-slate-900">{{ number_format($agent->transactions_as_agent_sum_montant ?? 0, 0, ',', ' ') }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Export & Reports --}}
                <div class="premium-card p-6 bg-slate-50 border-dashed border-2 border-slate-200 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-4">
                        <i class="fa-solid fa-file-pdf text-3xl text-rose-500"></i>
                    </div>
                    <h4 class="text-lg font-black text-slate-900 mb-2 uppercase tracking-tighter">Reporting Financier</h4>
                    <p class="text-xs text-slate-500 mb-6 px-4">Générez un rapport complet de toutes les transactions au format PDF professionnel.</p>
                    <a href="{{ route('admin.transactions.export') }}" class="btn-premium py-3 px-8 w-full">
                        <i class="fa-solid fa-file-pdf mr-2"></i> Exporter en PDF
                    </a>
                </div>
            </div>
            @endrole

            {{-- Actions Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Quick Actions --}}
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('biens.index') }}" class="premium-card p-6 flex items-start group">
                        <div class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                            <i class="fa-solid fa-magnifying-glass-chart text-2xl"></i>
                        </div>
                        <div class="ms-4">
                            <h4 class="font-bold text-slate-900 dark:text-white">{{ __('Explorer le catalogue') }}</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">{{ __('Accédez à toutes les annonces immobilières.') }}</p>
                        </div>
                    </a>

                    <a href="{{ route('visites.index') }}" class="premium-card p-6 flex items-start group">
                        <div class="p-4 bg-rose-50 text-rose-600 rounded-2xl group-hover:bg-rose-600 group-hover:text-white transition-colors duration-300">
                            <i class="fa-solid fa-calendar-check text-2xl"></i>
                        </div>
                        <div class="ms-4">
                            <h4 class="font-bold text-slate-900 dark:text-white">{{ __('Gérer les visites') }}</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">{{ __('Consultez vos rendez-vous et plannings.') }}</p>
                        </div>
                    </a>

                    @can('users.manage')
                    <a href="{{ route('admin.users') }}" class="premium-card p-6 flex items-start group">
                        <div class="p-4 bg-slate-900 text-white rounded-2xl group-hover:bg-indigo-600 transition-colors duration-300">
                            <i class="fa-solid fa-users-gear text-2xl"></i>
                        </div>
                        <div class="ms-4">
                            <h4 class="font-bold text-slate-900 dark:text-white">{{ __('Administration') }}</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">{{ __('Gérer les utilisateurs et les rôles.') }}</p>
                        </div>
                    </a>
                    @endcan

                    @can('users.manage')
                    <a href="{{ route('admin.biens.pending') }}" class="premium-card p-6 flex items-start group relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 w-12 h-12 bg-amber-500/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="p-4 bg-amber-50 text-amber-600 rounded-2xl group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                            <i class="fa-solid fa-layer-group text-2xl"></i>
                        </div>
                        <div class="ms-4">
                            <div class="flex items-center">
                                <h4 class="font-bold text-slate-900 dark:text-white">{{ __('Validation Groupée') }}</h4>
                                <span class="ms-2 px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-black rounded-md">{{ \App\Models\Bien::where('statut', 'en_attente')->count() }}</span>
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">{{ __('Traiter les nouveaux biens en attente.') }}</p>
                        </div>
                    </a>
                    @endcan

                    @can('users.manage')
                    <a href="{{ route('admin.broadcast') }}" class="premium-card p-6 flex items-start group relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 w-12 h-12 bg-indigo-500/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                            <i class="fa-solid fa-paper-plane text-2xl"></i>
                        </div>
                        <div class="ms-4">
                            <h4 class="font-bold text-slate-900 dark:text-white">{{ __('Diffusion de Message') }}</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 dark:text-slate-500 mt-1">{{ __('Envoyer une annonce à tous les utilisateurs.') }}</p>
                        </div>
                    </a>
                    @endcan
                </div>

            </div>

            {{-- Premium Help & Contact Section --}}
            <div class="mt-16 bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100 relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-[0.03] -mr-10 -mt-10">
                    <i class="fa-solid fa-headset text-[15rem]"></i>
                </div>
                
                <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-4 uppercase tracking-tighter">Besoin d'aide ou d'un conseil ?</h3>
                        <p class="text-slate-500 dark:text-slate-400 dark:text-slate-500 font-medium leading-relaxed mb-8">
                            Notre équipe d'experts est à votre disposition pour vous accompagner dans toutes vos démarches immobilières. Que vous soyez propriétaire ou futur acquéreur, nous sommes là pour vous.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="#" class="btn-premium py-3 px-8">
                                <i class="fa-solid fa-book-open mr-2"></i> Guide d'utilisation
                            </a>
                            <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'josephbangoura0204@gmail.com') }}" class="px-8 py-3 bg-slate-100 text-slate-900 dark:text-white font-black rounded-2xl hover:bg-slate-200 transition-all text-xs uppercase tracking-widest">
                                <i class="fa-solid fa-envelope mr-2"></i> Écrire au support
                            </a>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100">
                        <h4 class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-6">Contact Direct Agence</h4>
                        <div class="space-y-6">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Téléphone</p>
                                    <p class="font-black text-slate-900 dark:text-white">{{ \App\Models\Setting::get('agency_phone', '+224 000 00 00 00') }}</p>
                                </div>
                            </div>
                            <a href="https://wa.me/224625997903?text=Bonjour ImmoPro, j'aimerais avoir des informations concernant vos services immobiliers." target="_blank" class="flex items-center group">
                                <div class="h-10 w-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mr-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">WhatsApp</p>
                                    <p class="font-black text-slate-900 dark:text-white group-hover:text-emerald-600 transition-colors">Disponible 24h/7j</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] pb-8">
                &copy; {{ date('Y') }} {{ \App\Models\Setting::get('agency_name', 'ImmoPro') }} — Plateforme Immobilière Certifiée
            </div>

        </div>
    </div>
</x-app-layout>
