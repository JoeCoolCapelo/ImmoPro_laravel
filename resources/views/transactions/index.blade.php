<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-white leading-tight">
                {{ __('Historique des Transactions') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Search Bar --}}
            <div class="mb-8">
                <form action="{{ route('transactions.index') }}" method="GET" class="flex gap-4">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Rechercher par matricule (ex: 42) ou titre du bien..." 
                            class="block w-full pl-12 pr-4 py-4 bg-white border-none rounded-[1.5rem] shadow-sm text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <button type="submit" class="px-8 py-4 bg-slate-900 text-white rounded-[1.5rem] text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-lg">
                        Filtrer
                    </button>
                    @if(request('search'))
                        <a href="{{ route('transactions.index') }}" class="px-6 py-4 bg-white text-slate-500 rounded-[1.5rem] text-xs font-black uppercase tracking-widest hover:bg-slate-100 transition-all flex items-center">
                            Effacer
                        </a>
                    @endif
                </form>
            </div>
            
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm">
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if($transactions->isEmpty())
                <div class="premium-card p-20 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-file-invoice-dollar text-4xl text-slate-200"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">{{ __('Aucune transaction') }}</h3>
                    <p class="text-slate-500 mt-2">Le registre des ventes et locations est vide pour le moment.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($transactions as $transaction)
                        <div class="premium-card overflow-hidden flex flex-col group relative {{ $transaction->is_archived ? 'opacity-75 grayscale-[0.5]' : '' }}">
                            {{-- Archived Badge --}}
                            @if($transaction->is_archived)
                                <div class="absolute top-0 right-0 bg-slate-800 text-white text-[8px] font-black uppercase px-3 py-1 rounded-bl-xl z-10 tracking-widest">
                                    Archivée
                                </div>
                            @endif

                            <div class="p-6 flex-1">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-lg {{ $transaction->type === 'vente' ? 'bg-indigo-600 text-white' : 'bg-emerald-500 text-white' }}">
                                        @if($transaction->type === 'vente')
                                            <i class="fa-solid fa-hand-holding-dollar"></i>
                                        @else
                                            <i class="fa-solid fa-key"></i>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">#TR-{{ str_pad($transaction->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        <p class="text-xs font-bold text-slate-500">{{ $transaction->date_transaction->format('d/m/Y') }}</p>
                                    </div>
                                </div>

                                <h4 class="text-base font-black text-slate-900 mb-2 line-clamp-1 group-hover:text-indigo-600 transition-colors">
                                    @if($transaction->bien)
                                        <a href="{{ route('biens.show', $transaction->bien) }}">{{ $transaction->bien->titre }}</a>
                                    @else
                                        <span class="text-rose-500 italic text-sm">{{ __('Propriété supprimée') }}</span>
                                    @endif
                                </h4>

                                <div class="space-y-2 mb-6">
                                    <div class="flex items-center text-xs text-slate-500">
                                        <i class="fa-solid fa-user-tie w-5 text-indigo-400"></i>
                                        <span class="font-bold">{{ $transaction->agent?->name ?? __('Agent inconnu') }}</span>
                                    </div>
                                    <div class="flex items-center text-xs text-slate-500">
                                        <i class="fa-solid fa-user w-5 text-indigo-400"></i>
                                        <span class="font-bold">{{ $transaction->client?->name ?? __('Client inconnu') }}</span>
                                    </div>
                                    
                                    {{-- Status des signatures --}}
                                    <div class="flex items-center text-[9px] mt-3 pt-3 border-t border-slate-100">
                                        <div class="flex-1 flex flex-col">
                                            <span class="text-slate-400 uppercase tracking-widest font-black mb-1">Accord Formel</span>
                                            <div class="flex items-center gap-3">
                                                <span class="font-bold {{ $transaction->client_signed ? 'text-emerald-500' : 'text-slate-300' }}">
                                                    <i class="fa-solid {{ $transaction->client_signed ? 'fa-check-double' : 'fa-clock' }} mr-1"></i> Client
                                                </span>
                                                <span class="text-slate-200">|</span>
                                                <span class="font-bold {{ $transaction->owner_signed ? 'text-emerald-500' : 'text-slate-300' }}">
                                                    <i class="fa-solid {{ $transaction->owner_signed ? 'fa-check-double' : 'fa-clock' }} mr-1"></i> Proprio
                                                </span>
                                                <span class="text-slate-200">|</span>
                                                <span class="font-bold {{ $transaction->agency_signed ? 'text-indigo-500' : 'text-slate-300' }}">
                                                    <i class="fa-solid {{ $transaction->agency_signed ? 'fa-check-double' : 'fa-clock' }} mr-1"></i> Agence
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3 bg-slate-50 rounded-2xl">
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Montant</p>
                                            <p class="text-lg font-black text-slate-900">{{ number_format($transaction->montant, 0, ',', ' ') }} <small class="text-[10px]">GNF</small></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1">Commission</p>
                                            <p class="text-sm font-black text-emerald-600">{{ number_format($transaction->commission_montant, 0, ',', ' ') }} <small class="text-[8px]">GNF</small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-slate-50/50 border-t border-slate-50 flex items-center justify-between">
                                <a href="{{ route('transactions.show', $transaction) }}" class="text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 transition-colors">
                                    Détails & Documents
                                </a>

                                <div class="flex items-center space-x-2">
                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('agent'))
                                        @if($transaction->type !== 'location')
                                            <form action="{{ route('transactions.archive', $transaction) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="p-2 {{ $transaction->is_archived ? 'text-slate-400 hover:text-indigo-600' : 'text-slate-400 hover:text-rose-500' }} transition-colors" title="{{ $transaction->is_archived ? 'Désarchiver' : 'Archiver' }}">
                                                    <i class="fa-solid {{ $transaction->is_archived ? 'fa-box-open' : 'fa-box-archive' }}"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    <a href="{{ route('transactions.pdf', $transaction) }}" target="_blank" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-all">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-10">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
