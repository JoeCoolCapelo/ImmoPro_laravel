<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-white leading-tight">
                {{ __('Rapports & Statistiques') }}
            </h2>
            <div class="flex space-x-3">
                <button onclick="window.print()" class="p-2.5 bg-white text-slate-600 rounded-xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm flex items-center">
                    <i class="fa-solid fa-print mr-2"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ __('Imprimer') }}</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                @foreach($natureStats as $stat)
                <div class="premium-card p-6 bg-white border-l-4 {{ $stat->type === 'vente' ? 'border-indigo-500' : 'border-emerald-500' }}">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Volume {{ ucfirst($stat->type) }}s</p>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($stat->total_volume, 0, ',', ' ') }} <small class="text-xs">GNF</small></p>
                    <p class="text-xs font-bold text-slate-400 mt-2">{{ $stat->count }} Transaction(s)</p>
                </div>
                @endforeach
                
                <div class="premium-card p-6 bg-white border-l-4 border-amber-500">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Commission Totale</p>
                    <p class="text-2xl font-black text-slate-900">{{ number_format($agentPerformance->sum('total_commissions'), 0, ',', ' ') }} <small class="text-xs">GNF</small></p>
                    <p class="text-xs font-bold text-slate-400 mt-2">Revenu Net Agence</p>
                </div>

                <div class="premium-card p-6 bg-white border-l-4 border-slate-900">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Biens</p>
                    <p class="text-2xl font-black text-slate-900">{{ $propertyDistribution->sum('total') }}</p>
                    <p class="text-xs font-bold text-slate-400 mt-2">En catalogue</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                {{-- Chart: Performance Mensuelle --}}
                <div class="lg:col-span-2 premium-card p-8 bg-white">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest mb-8 flex items-center">
                        <i class="fa-solid fa-chart-line mr-3 text-indigo-600"></i>
                        Évolution du Chiffre d'Affaires
                    </h3>
                    <div class="h-[350px]">
                        <canvas id="monthlyVolumeChart"></canvas>
                    </div>
                </div>

                {{-- Chart: Répartition Biens --}}
                <div class="premium-card p-8 bg-white">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest mb-8 flex items-center">
                        <i class="fa-solid fa-chart-pie mr-3 text-emerald-600"></i>
                        Types de Biens
                    </h3>
                    <div class="h-[350px] flex items-center justify-center">
                        <canvas id="propertyTypeChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Table: Agent Performance --}}
                <div class="premium-card bg-white overflow-hidden">
                    <div class="p-8 border-b border-slate-50">
                        <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest flex items-center">
                            <i class="fa-solid fa-trophy mr-3 text-amber-500"></i>
                            Performance des Agents
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Agent</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Transactions</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Commissions Générées</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($agentPerformance as $agent)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 flex items-center">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs mr-3">
                                            {{ substr($agent->name, 0, 1) }}
                                        </div>
                                        <span class="font-bold text-slate-800 text-sm">{{ $agent->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-600 text-sm">{{ $agent->sales_count }}</td>
                                    <td class="px-6 py-4 text-right font-black text-emerald-600 text-sm">{{ number_format($agent->total_commissions ?? 0, 0, ',', ' ') }} GNF</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table: Nature Distribution --}}
                <div class="premium-card bg-white overflow-hidden">
                    <div class="p-8 border-b border-slate-50">
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center">
                            <i class="fa-solid fa-handshake mr-3 text-indigo-500"></i>
                            Volume par Nature
                        </h3>
                    </div>
                    <div class="p-8 space-y-6">
                        @foreach($natureStats as $stat)
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-xs font-black uppercase tracking-widest text-slate-500">{{ ucfirst($stat->type) }}s</span>
                                <span class="text-xs font-black text-slate-900">{{ number_format($stat->total_volume, 0, ',', ' ') }} GNF</span>
                            </div>
                            <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full {{ $stat->type === 'vente' ? 'bg-indigo-500' : 'bg-emerald-500' }}" style="width: {{ ($stat->total_volume / $natureStats->sum('total_volume')) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Monthly Volume
            new Chart(document.getElementById('monthlyVolumeChart'), {
                type: 'line',
                data: {
                    labels: @json($monthlyVolume->pluck('month')),
                    datasets: [{
                        label: 'Volume de Transaction',
                        data: @json($monthlyVolume->pluck('volume')),
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 4
                    }, {
                        label: 'Commissions Agence',
                        data: @json($monthlyVolume->pluck('commissions')),
                        borderColor: '#10b981',
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    scales: { y: { beginAtZero: true, grid: { display: false } } }
                }
            });

            // Chart 2: Property Types
            new Chart(document.getElementById('propertyTypeChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($propertyDistribution->pluck('type')),
                    datasets: [{
                        data: @json($propertyDistribution->pluck('total')),
                        backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#6366f1']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } },
                    cutout: '70%'
                }
            });
        });
    </script>
</x-app-layout>
