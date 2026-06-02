<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-white leading-tight">
                {{ __('Entretien & Travaux') }}
            </h2>
            <button onclick="document.getElementById('addExpenseModal').classList.remove('hidden')" class="px-4 py-2 bg-white text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition shadow-lg">
                <i class="fa-solid fa-plus mr-2"></i> Ajouter une dépense
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm">
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Statistiques --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="premium-card p-6 bg-white">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Dépenses (Mois)</p>
                        <p class="text-3xl font-black text-slate-900">{{ number_format($expenses->where('date_expense', '>=', now()->startOfMonth())->sum('amount'), 0, ',', ' ') }} <small class="text-xs">GNF</small></p>
                    </div>

                    <div class="premium-card p-6 bg-slate-900 text-white relative overflow-hidden">
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full"></div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Impact sur Revenu</p>
                        <p class="text-xs text-slate-300">Les dépenses d'entretien sont déduites du montant reversé aux propriétaires lors du bilan.</p>
                    </div>
                </div>

                {{-- Liste des dépenses --}}
                <div class="lg:col-span-2">
                    <div class="premium-card overflow-hidden">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-100">
                                    <th class="text-left p-5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                                    <th class="text-left p-5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Bien</th>
                                    <th class="text-left p-5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Nature des Travaux</th>
                                    <th class="text-left p-5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Montant</th>
                                    <th class="text-right p-5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $expense)
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                                        <td class="p-5">
                                            <p class="text-xs font-bold text-slate-500">{{ $expense->date_expense->format('d/m/Y') }}</p>
                                        </td>
                                        <td class="p-5">
                                            <p class="text-sm font-black text-slate-900">{{ $expense->bien->titre }}</p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase">{{ $expense->bien->ville }}</p>
                                        </td>
                                        <td class="p-5">
                                            <p class="text-sm font-bold text-slate-700">{{ $expense->title }}</p>
                                            @if($expense->description)
                                                <p class="text-[10px] text-slate-400 italic">{{ Str::limit($expense->description, 40) }}</p>
                                            @endif
                                        </td>
                                        <td class="p-5">
                                            <p class="text-sm font-black text-rose-600">{{ number_format($expense->amount, 0, ',', ' ') }} GNF</p>
                                        </td>
                                        <td class="p-5 text-right">
                                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Supprimer cette dépense ?')">
                                                @csrf @method('DELETE')
                                                <button class="h-8 w-8 text-slate-300 hover:text-rose-500 transition-colors">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-20 text-center text-slate-400 text-sm">Aucune dépense enregistrée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="p-4 bg-slate-50 border-t border-slate-100">
                            {{ $expenses->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Ajout --}}
    <div id="addExpenseModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl animate-in zoom-in duration-300">
            <div class="p-8 bg-slate-900 text-white flex justify-between items-center">
                <h3 class="font-black text-lg uppercase tracking-widest">Nouvelle Dépense</h3>
                <button onclick="document.getElementById('addExpenseModal').classList.add('hidden')" class="text-white/50 hover:text-white">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form action="{{ route('expenses.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Bien concerné</label>
                    <select name="bien_id" required class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-600">
                        @foreach($biens as $bien)
                            <option value="{{ $bien->id }}">{{ $bien->titre }} ({{ $bien->owner->name ?? 'Sans propriétaire' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Titre des travaux</label>
                    <input type="text" name="title" required placeholder="Ex: Réparation plomberie cuisine" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-600">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Montant (GNF)</label>
                        <input type="number" name="amount" required class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-600">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Date</label>
                        <input type="date" name="date_expense" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-600">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Description (Optionnel)</label>
                    <textarea name="description" rows="3" class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-600"></textarea>
                </div>
                <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                    Enregistrer la dépense
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
