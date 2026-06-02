<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-white leading-tight">
                {{ __('Gestion des Utilisateurs') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" class="btn-premium flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Nouvel Utilisateur') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm">
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-xl shadow-sm">
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Filtre --}}
            <div class="premium-card p-6 mb-6">
                <form action="{{ route('admin.users') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
                    <div class="flex-1">
                        <label for="search" class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Recherche</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass text-slate-400 dark:text-slate-500"></i>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="pl-10 block w-full rounded-xl border-slate-200 focus:border-slate-900 focus:ring focus:ring-slate-900 focus:ring-opacity-20 transition-colors text-sm"
                                placeholder="Nom ou adresse email...">
                        </div>
                    </div>
                    <div class="w-full md:w-56">
                        <label for="role" class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Rôle</label>
                        <select name="role" id="role" class="block w-full rounded-xl border-slate-200 focus:border-slate-900 focus:ring focus:ring-slate-900 focus:ring-opacity-20 transition-colors text-sm">
                            <option value="">Tous les rôles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3 mt-4 md:mt-0 w-full md:w-auto shrink-0">
                        <button type="submit" class="btn-premium flex items-center justify-center px-5">
                            <i class="fa-solid fa-filter mr-2"></i> Filtrer
                        </button>
                        <button type="submit" name="export" value="csv" class="px-5 py-2.5 rounded-2xl font-bold text-slate-700 dark:text-slate-300 bg-white border border-slate-200 hover:bg-slate-50 transition-all flex items-center">
                            <i class="fa-solid fa-file-csv mr-2 text-emerald-600"></i> CSV
                        </button>
                        <button type="submit" name="export" value="pdf" class="px-5 py-2.5 rounded-2xl font-bold text-white bg-rose-600 hover:bg-rose-700 transition-all flex items-center">
                            <i class="fa-solid fa-file-pdf mr-2"></i> PDF
                        </button>
                    </div>
                </form>
            </div>

            {{-- Onglets de vue --}}
            <div class="flex items-center gap-2 mb-6">
                <button id="tab-table" onclick="switchView('table')"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-2xl font-bold text-sm transition-all duration-200 bg-slate-900 text-white shadow-md">
                    <i class="fa-solid fa-table-list"></i> Tableau
                </button>
                <button id="tab-cards" onclick="switchView('cards')"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-2xl font-bold text-sm transition-all duration-200 bg-white text-slate-500 dark:text-slate-400 dark:text-slate-500 border border-slate-200 hover:bg-slate-50">
                    <i class="fa-solid fa-id-card"></i> Cartes
                </button>
                <span class="ml-auto text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                    {{ $users->total() }} utilisateur(s)
                </span>
            </div>

            {{-- VUE TABLEAU --}}
            <div id="view-table">
                <div class="premium-card overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Utilisateur</th>
                                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Rôle</th>
                                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Email vérifié</th>
                                    <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Inscription</th>
                                    <th class="px-6 py-3 text-right text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-50">
                                @foreach($users as $user)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-9 w-9 rounded-xl overflow-hidden bg-slate-900 flex items-center justify-center text-white font-black text-sm shadow-sm shrink-0">
                                                @if($user->photo_url)
                                                    <img src="{{ Storage::url($user->photo_url) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                @endif
                                            </div>
                                            <div class="ms-3">
                                                <div class="text-xs font-black text-slate-900 dark:text-white">{{ $user->name }}</div>
                                                <div class="text-[10px] font-medium text-slate-400 dark:text-slate-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 bg-slate-100 text-slate-700 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest rounded-md border border-slate-200">
                                            {{ $user->roles->first()?->name ?? 'Aucun' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->email_verified_at)
                                            <span class="flex items-center text-[10px] font-bold text-emerald-600">
                                                <i class="fa-solid fa-circle-check mr-1"></i> Vérifié
                                            </span>
                                        @else
                                            <span class="flex items-center text-[10px] font-bold text-amber-500">
                                                <i class="fa-solid fa-clock mr-1"></i> En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-500 dark:text-slate-400 dark:text-slate-500">
                                        {{ $user->created_at->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                        <div class="flex justify-end items-center space-x-2">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="p-2 text-slate-400 dark:text-slate-500 hover:text-slate-900 dark:text-white hover:bg-slate-100 rounded-xl transition-all" title="Modifier">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Supprimer {{ $user->name }} ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-400 dark:text-slate-500 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all" title="Supprimer">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-50">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>

            {{-- VUE CARTES --}}
            <div id="view-cards" class="hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($users as $user)
                    @php
                        $roleColors = [
                            'admin'        => 'from-slate-900 to-slate-700',
                            'agent'        => 'from-slate-700 to-slate-500',
                            'proprietaire' => 'from-slate-600 to-slate-400',
                            'client'       => 'from-slate-500 to-slate-300',
                        ];
                        $roleName = $user->roles->first()?->name ?? 'client';
                        $gradient = $roleColors[$roleName] ?? 'from-slate-700 to-slate-500';
                    @endphp
                    <div class="premium-card p-0 flex flex-col" style="overflow: visible;">
                        {{-- Bannière rôle --}}
                        <div class="h-20 bg-gradient-to-br {{ $gradient }} relative shrink-0 rounded-t-3xl overflow-hidden">
                            <div class="absolute inset-0 flex items-center justify-center opacity-10">
                                <i class="fa-solid fa-user text-8xl text-white"></i>
                            </div>
                            <div class="absolute top-3 right-3">
                                <span class="px-2.5 py-1 bg-white/20 backdrop-blur-sm text-white text-[9px] font-black uppercase tracking-widest rounded-full border border-white/30">
                                    {{ ucfirst($roleName) }}
                                </span>
                            </div>
                        </div>

                        <div class="px-5 pb-5 flex flex-col flex-1 -mt-7 relative z-10">
                            {{-- Avatar --}}
                            <div class="h-14 w-14 rounded-2xl overflow-hidden bg-slate-900 flex items-center justify-center text-white font-black text-xl shadow-xl border-3 border-white mb-3 ring-2 ring-white">
                                @if($user->photo_url)
                                    <img src="{{ Storage::url($user->photo_url) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                            </div>

                            <h3 class="font-black text-slate-900 dark:text-white text-base leading-tight mb-0.5">{{ $user->name }}</h3>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium mb-4 truncate">{{ $user->email }}</p>

                            {{-- Détails --}}
                            <div class="space-y-2.5 mb-5 flex-1">
                                <div class="flex items-center text-xs">
                                    <i class="fa-solid fa-calendar-plus w-4 text-slate-400 dark:text-slate-500 mr-2.5 shrink-0 text-[11px]"></i>
                                    <span class="text-slate-500 dark:text-slate-400 dark:text-slate-500 font-semibold">Inscrit le</span>
                                    <span class="ml-auto font-black text-slate-900 dark:text-white">{{ $user->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center text-xs">
                                    <i class="fa-solid fa-shield-halved w-4 text-slate-400 dark:text-slate-500 mr-2.5 shrink-0 text-[11px]"></i>
                                    <span class="text-slate-500 dark:text-slate-400 dark:text-slate-500 font-semibold">Email</span>
                                    @if($user->email_verified_at)
                                        <span class="ml-auto text-emerald-600 font-black flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i> Vérifié
                                        </span>
                                    @else
                                        <span class="ml-auto text-amber-500 font-black flex items-center gap-1">
                                            <i class="fa-solid fa-hourglass-half text-[10px]"></i> Non vérifié
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center text-xs">
                                    <i class="fa-solid fa-fingerprint w-4 text-slate-400 dark:text-slate-500 mr-2.5 shrink-0 text-[11px]"></i>
                                    <span class="text-slate-500 dark:text-slate-400 dark:text-slate-500 font-semibold">ID Système</span>
                                    <span class="ml-auto font-black text-slate-400 dark:text-slate-500">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="flex items-center text-xs">
                                    <i class="fa-solid fa-clock-rotate-left w-4 text-slate-400 dark:text-slate-500 mr-2.5 shrink-0 text-[11px]"></i>
                                    <span class="text-slate-500 dark:text-slate-400 dark:text-slate-500 font-semibold">Dernière modif.</span>
                                    <span class="ml-auto font-black text-slate-700 dark:text-slate-300">{{ $user->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 pt-4 border-t border-slate-100">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="flex-1 text-center py-2.5 text-xs font-black text-white bg-slate-900 hover:bg-slate-700 rounded-xl transition-all">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Modifier
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                    onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="py-2.5 px-3 text-xs font-black text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-all">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>

    <script>
        // Restaurer la vue préférée
        switchView(localStorage.getItem('usersView') || 'table', false);

        function switchView(view, save = true) {
            const tableEl  = document.getElementById('view-table');
            const cardsEl  = document.getElementById('view-cards');
            const tabTable = document.getElementById('tab-table');
            const tabCards = document.getElementById('tab-cards');

            const activeClass   = 'flex items-center gap-2 px-5 py-2.5 rounded-2xl font-bold text-sm transition-all duration-200 bg-slate-900 text-white shadow-md';
            const inactiveClass = 'flex items-center gap-2 px-5 py-2.5 rounded-2xl font-bold text-sm transition-all duration-200 bg-white text-slate-500 dark:text-slate-400 dark:text-slate-500 border border-slate-200 hover:bg-slate-50';

            if (view === 'table') {
                tableEl.classList.remove('hidden');
                cardsEl.classList.add('hidden');
                tabTable.className = activeClass;
                tabCards.className = inactiveClass;
            } else {
                tableEl.classList.add('hidden');
                cardsEl.classList.remove('hidden');
                tabCards.className = activeClass;
                tabTable.className = inactiveClass;
            }

            if (save) localStorage.setItem('usersView', view);
        }
    </script>
</x-app-layout>
