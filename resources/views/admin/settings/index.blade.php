<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-xl text-white leading-tight">
                {{ __('Paramètres du Système') }}
            </h2>
            <div class="text-[10px] font-black text-white uppercase tracking-widest bg-white/10 px-4 py-2 rounded-xl backdrop-blur-sm border border-white/10">
                {{ __('Configuration Globale') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm">
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-8 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-xl shadow-sm">
                    <ul class="list-disc list-inside font-bold text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- Section Logo --}}
                <div class="premium-card p-8 mb-8">
                    <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center">
                        <div class="w-8 h-8 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center mr-3">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        Logo de l'agence
                    </h3>
                    
                    <div class="flex items-center space-x-8">
                        {{-- Current Logo Preview --}}
                        <div class="shrink-0">
                            @if(!empty($settings['agency_logo'] ?? null))
                                <img src="{{ Storage::url($settings['agency_logo']) }}" alt="Logo" class="h-20 w-20 rounded-2xl object-cover border-2 border-slate-100 shadow-sm">
                            @else
                                <div class="h-20 w-20 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-300">
                                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <x-input-label for="agency_logo" value="Téléverser un nouveau logo" />
                            <input id="agency_logo" type="file" name="agency_logo" accept="image/*" class="block mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all" />
                            <p class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Formats acceptés : JPG, PNG, SVG — Taille max : 2 Mo</p>
                        </div>
                    </div>
                </div>

                {{-- Section Général --}}
                <div class="premium-card p-8 mb-8">
                    <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mr-3">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        Général
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="agency_name" value="Nom de l'agence" />
                            <x-text-input id="agency_name" class="block mt-1 w-full" type="text" name="agency_name" value="{{ old('agency_name', $settings['agency_name']) }}" required />
                        </div>
                        
                        <div>
                            <x-input-label for="agency_director" value="Nom du Directeur Général" />
                            <x-text-input id="agency_director" class="block mt-1 w-full" type="text" name="agency_director" value="{{ old('agency_director', $settings['agency_director'] ?? '') }}" required />
                        </div>

                        <div>
                            <x-input-label for="currency" value="Devise par défaut" />
                            <select id="currency" name="currency" class="block mt-1 w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="GNF" {{ old('currency', $settings['currency']) === 'GNF' ? 'selected' : '' }}>Franc Guinéen (GNF)</option>
                                <option value="FCFA" {{ old('currency', $settings['currency']) === 'FCFA' ? 'selected' : '' }}>Franc CFA (FCFA)</option>
                                <option value="EUR" {{ old('currency', $settings['currency']) === 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                <option value="USD" {{ old('currency', $settings['currency']) === 'USD' ? 'selected' : '' }}>Dollar ($)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Section Contact --}}
                <div class="premium-card p-8 mb-8">
                    <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mr-3">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        Coordonnées
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="contact_email" value="Email officiel" />
                            <x-text-input id="contact_email" class="block mt-1 w-full" type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" required />
                        </div>
                        
                        <div>
                            <x-input-label for="contact_phone" value="Téléphone principal" />
                            <x-text-input id="contact_phone" class="block mt-1 w-full" type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}" required />
                        </div>
                    </div>
                </div>

                {{-- Section Finances --}}
                <div class="premium-card p-8 mb-8">
                    <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center">
                        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center mr-3">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        Finances & Commissions
                    </h3>
                    
                    <div>
                        <x-input-label for="agent_commission" value="Commission des agents par défaut (%)" />
                        <x-text-input id="agent_commission" class="block mt-1 w-full md:w-1/2" type="number" step="0.1" min="0" max="100" name="agent_commission" value="{{ old('agent_commission', $settings['agent_commission']) }}" required />
                        <p class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Le pourcentage retenu par l'agent sur chaque transaction</p>
                    </div>
                </div>



                {{-- Section Maintenance & Outils Système --}}
                <div class="premium-card p-8 mb-8 border-l-4 border-indigo-500">
                    <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mr-3">
                            <i class="fa-solid fa-server text-sm"></i>
                        </div>
                        Maintenance & Outils Système
                    </h3>
                    
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div>
                            <p class="font-black text-slate-800 text-sm">Système d'Alerte Loyers</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Exécute manuellement la commande de notification des loyers arrivant à échéance (J-3).</p>
                        </div>
                        <a href="{{ route('admin.trigger-rent-reminders') }}" class="px-4 py-2 bg-slate-900 text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition shadow-sm" onclick="return confirm('Voulez-vous déclencher l\'envoi manuel des rappels de loyer ?')">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Déclencher
                        </a>
                    </div>
                </div>

                {{-- Section Équipe --}}
                <div class="premium-card p-8 mb-8">
                    <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mr-3">
                            <i class="fa-solid fa-users text-sm"></i>
                        </div>
                        Équipe de Direction (Membres Clés)
                    </h3>
                    
                    <div class="space-y-8">
                        {{-- Member 1 --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="md:col-span-3 text-[10px] font-black text-slate-400 uppercase tracking-widest flex justify-between">
                                <span>Membre 1 (ex: Directeur)</span>
                                @if($settings['team_member_1_photo']) <span class="text-emerald-500"><i class="fa-solid fa-check-circle"></i> Photo en ligne</span> @endif
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                @if($settings['team_member_1_photo'])
                                    <img src="{{ Storage::url($settings['team_member_1_photo']) }}" class="h-20 w-20 rounded-2xl object-cover shadow-sm mb-2">
                                @else
                                    <div class="h-20 w-20 rounded-2xl bg-slate-200 flex items-center justify-center text-slate-400 mb-2"><i class="fa-solid fa-user text-2xl"></i></div>
                                @endif
                                <input type="file" name="team_member_1_photo" class="text-[9px] w-full">
                            </div>
                            <div>
                                <x-input-label for="team_member_1_name" value="Nom Complet" />
                                <x-text-input id="team_member_1_name" class="block mt-1 w-full" type="text" name="team_member_1_name" value="{{ old('team_member_1_name', $settings['team_member_1_name']) }}" />
                            </div>
                            <div>
                                <x-input-label for="team_member_1_role" value="Poste / Titre" />
                                <x-text-input id="team_member_1_role" class="block mt-1 w-full" type="text" name="team_member_1_role" value="{{ old('team_member_1_role', $settings['team_member_1_role']) }}" />
                            </div>
                        </div>

                        {{-- Member 2 --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="md:col-span-3 text-[10px] font-black text-slate-400 uppercase tracking-widest flex justify-between">
                                <span>Membre 2 (ex: Comptable)</span>
                                @if($settings['team_member_2_photo']) <span class="text-emerald-500"><i class="fa-solid fa-check-circle"></i> Photo en ligne</span> @endif
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                @if($settings['team_member_2_photo'])
                                    <img src="{{ Storage::url($settings['team_member_2_photo']) }}" class="h-20 w-20 rounded-2xl object-cover shadow-sm mb-2">
                                @else
                                    <div class="h-20 w-20 rounded-2xl bg-slate-200 flex items-center justify-center text-slate-400 mb-2"><i class="fa-solid fa-user text-2xl"></i></div>
                                @endif
                                <input type="file" name="team_member_2_photo" class="text-[9px] w-full">
                            </div>
                            <div>
                                <x-input-label for="team_member_2_name" value="Nom Complet" />
                                <x-text-input id="team_member_2_name" class="block mt-1 w-full" type="text" name="team_member_2_name" value="{{ old('team_member_2_name', $settings['team_member_2_name']) }}" />
                            </div>
                            <div>
                                <x-input-label for="team_member_2_role" value="Poste / Titre" />
                                <x-text-input id="team_member_2_role" class="block mt-1 w-full" type="text" name="team_member_2_role" value="{{ old('team_member_2_role', $settings['team_member_2_role']) }}" />
                            </div>
                        </div>

                        {{-- Member 3 --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="md:col-span-3 text-[10px] font-black text-slate-400 uppercase tracking-widest flex justify-between">
                                <span>Membre 3 (ex: Manager)</span>
                                @if($settings['team_member_3_photo']) <span class="text-emerald-500"><i class="fa-solid fa-check-circle"></i> Photo en ligne</span> @endif
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                @if($settings['team_member_3_photo'])
                                    <img src="{{ Storage::url($settings['team_member_3_photo']) }}" class="h-20 w-20 rounded-2xl object-cover shadow-sm mb-2">
                                @else
                                    <div class="h-20 w-20 rounded-2xl bg-slate-200 flex items-center justify-center text-slate-400 mb-2"><i class="fa-solid fa-user text-2xl"></i></div>
                                @endif
                                <input type="file" name="team_member_3_photo" class="text-[9px] w-full">
                            </div>
                            <div>
                                <x-input-label for="team_member_3_name" value="Nom Complet" />
                                <x-text-input id="team_member_3_name" class="block mt-1 w-full" type="text" name="team_member_3_name" value="{{ old('team_member_3_name', $settings['team_member_3_name']) }}" />
                            </div>
                            <div>
                                <x-input-label for="team_member_3_role" value="Poste / Titre" />
                                <x-text-input id="team_member_3_role" class="block mt-1 w-full" type="text" name="team_member_3_role" value="{{ old('team_member_3_role', $settings['team_member_3_role']) }}" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-premium">
                        {{ __('Sauvegarder les paramètres') }}
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</x-app-layout>
