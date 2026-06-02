<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-white leading-tight">
            {{ __('Validation Groupée des Biens') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                    <i class="fa-solid fa-circle-check mr-3"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.biens.bulk-validate') }}" method="POST" id="bulk-form">
                @csrf
                
                <div class="premium-card bg-white p-8 mb-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h3 class="text-lg font-black text-slate-900 uppercase tracking-tighter">Actions en masse</h3>
                            <p class="text-sm text-slate-500">Sélectionnez les biens à valider et choisissez l'agent responsable.</p>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <select name="agent_id" required class="pl-10 pr-10 py-3 bg-slate-50 border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none">
                                    <option value="">-- Choisir un agent --</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn-premium px-8 py-3 disabled:opacity-50 disabled:cursor-not-allowed" id="submit-btn" disabled>
                                <i class="fa-solid fa-check-double mr-2"></i> Valider la sélection
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="p-6 w-10">
                                    <input type="checkbox" id="select-all" class="rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                </th>
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Bien</th>
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Propriétaire</th>
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Localisation</th>
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Prix</th>
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($biens as $bien)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="p-6">
                                        <input type="checkbox" name="bien_ids[]" value="{{ $bien->id }}" class="bien-checkbox rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                    </td>
                                    <td class="p-6">
                                        <div class="flex items-center">
                                            <div class="h-12 w-16 rounded-xl bg-slate-100 overflow-hidden mr-4">
                                                @if($bien->images->count() > 0)
                                                    <img src="{{ Storage::url($bien->images->first()->path) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <i class="fa-solid fa-camera text-slate-300"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 group-hover:text-indigo-600 transition">{{ $bien->titre }}</p>
                                                <p class="text-[10px] text-slate-500 uppercase font-black">{{ $bien->nature }} • {{ $bien->type }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <div class="flex items-center">
                                            <img src="https://i.pravatar.cc/100?u={{ $bien->owner->email }}" class="h-8 w-8 rounded-full mr-3 border-2 border-slate-100">
                                            <span class="text-sm font-bold text-slate-700">{{ $bien->owner->name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <span class="text-sm text-slate-600">
                                            <i class="fa-solid fa-location-dot text-slate-300 mr-1"></i>
                                            {{ $bien->ville }}, {{ $bien->quartier }}
                                        </span>
                                    </td>
                                    <td class="p-6">
                                        <span class="text-sm font-black text-slate-900">{{ number_format($bien->prix, 0, ',', ' ') }} GNF</span>
                                    </td>
                                    <td class="p-6 text-right">
                                        <span class="text-[10px] font-black text-slate-400 uppercase">{{ $bien->created_at->translatedFormat('d M Y') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-20 text-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <i class="fa-solid fa-clipboard-check text-slate-200 text-3xl"></i>
                                        </div>
                                        <h4 class="text-lg font-black text-slate-400">Aucun bien en attente de validation</h4>
                                        <p class="text-sm text-slate-400 mt-2">Tous les biens soumis ont été traités.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">
                    {{ $biens->links() }}
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.bien-checkbox');
            const submitBtn = document.getElementById('submit-btn');

            function updateSubmitButton() {
                const checkedCount = document.querySelectorAll('.bien-checkbox:checked').length;
                submitBtn.disabled = checkedCount === 0;
            }

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
                updateSubmitButton();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateSubmitButton);
            });
        });
    </script>
</x-app-layout>
