<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-white leading-tight">
                {{ __('Journal d\'Audit') }}
            </h2>
            <div class="text-[10px] font-black text-white uppercase tracking-widest bg-white/10 px-4 py-2 rounded-xl backdrop-blur-sm border border-white/10">
                {{ __('Sécurité & Traçabilité') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Filters Form --}}
            <div class="premium-card bg-white p-6 mb-8">
                <form action="{{ route('admin.logs') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Utilisateur</label>
                        <select name="user_id" class="w-full bg-slate-50 border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Tous les utilisateurs</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Action</label>
                        <input type="text" name="event" value="{{ request('event') }}" placeholder="Ex: created, updated..." class="w-full bg-slate-50 border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="md:col-span-1 grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Du</label>
                            <input type="date" name="from" value="{{ request('from') }}" class="w-full bg-slate-50 border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Au</label>
                            <input type="date" name="to" value="{{ request('to') }}" class="w-full bg-slate-50 border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="btn-premium flex-1 py-3">
                            <i class="fa-solid fa-filter mr-2"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.logs') }}" class="px-4 py-3 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-colors flex items-center justify-center">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>
                </form>
            </div>

            <div class="glass-card overflow-hidden rounded-[2rem]">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-white">
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest">{{ __('Date') }}</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest">{{ __('Utilisateur') }}</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest">{{ __('Action') }}</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest">{{ __('Modèle') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($activities as $activity)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap text-[10px] font-bold text-slate-500">
                                        {{ $activity->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-[8px] font-black mr-2">
                                                {{ substr($activity->causer->name ?? 'SYS', 0, 1) }}
                                            </div>
                                            <span class="text-xs font-black text-slate-800">{{ $activity->causer->name ?? 'Système' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-tighter 
                                            {{ $activity->description === 'created' ? 'bg-emerald-50 text-emerald-600' : 
                                               ($activity->description === 'updated' ? 'bg-indigo-50 text-indigo-600' : 'bg-rose-50 text-rose-600') }}">
                                            {{ $activity->description }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs font-bold text-slate-600">
                                        {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-6 bg-slate-50 border-t border-slate-100">
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
