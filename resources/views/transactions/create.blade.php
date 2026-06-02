<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Finaliser la transaction') }} : {{ $bien->titre }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card overflow-hidden rounded-[2rem]">
                <div class="p-10">
                    <form method="POST" action="{{ route('transactions.store') }}" class="space-y-8" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="bien_id" value="{{ $bien->id }}">
                        @if(isset($visiteId))
                            <input type="hidden" name="visite_id" value="{{ $visiteId }}">
                        @endif

                        <div class="flex items-center justify-between p-6 bg-indigo-50/50 rounded-3xl border border-indigo-100">
                            <div>
                                <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">{{ __('Bien concerné') }}</p>
                                <h4 class="text-indigo-900 font-black">{{ $bien->titre }}</h4>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">{{ __('Prix affiché') }}</p>
                                <p class="text-indigo-900 font-black text-xl">{{ number_format($bien->prix, 0, ',', ' ') }} <small>FCFA</small></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <x-input-label for="user_id" :value="__('Client (Acheteur/Locataire)')" class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2" />
                                <select id="user_id" name="user_id" class="mt-1 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-2xl shadow-sm text-sm font-bold p-3" required>
                                    <option value="">{{ __('-- Sélectionner un client --') }}</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" 
                                            {{ (old('user_id', $selectedClientId ?? '') == $client->id) ? 'selected' : '' }}>
                                            {{ $client->name }} ({{ $client->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                            </div>

                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <x-input-label for="montant" :value="__('Montant final (GNF)')" class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2" />
                                    <x-text-input id="montant" name="montant" type="number" class="mt-1 block w-full rounded-2xl border-slate-200 p-3 font-black text-indigo-600" :value="old('montant', $bien->prix)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('montant')" />
                                </div>
                                <div>
                                    <x-input-label for="commission_pourcentage" :value="__('Commission Agence (%)')" class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2" />
                                    <x-text-input id="commission_pourcentage" name="commission_pourcentage" type="number" step="0.01" class="mt-1 block w-full rounded-2xl border-slate-200 p-3 font-black text-emerald-600" :value="old('commission_pourcentage', 10.00)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('commission_pourcentage')" />
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <x-input-label for="date_transaction" :value="__('Date de l\'acte')" class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2" />
                                <x-text-input id="date_transaction" name="date_transaction" type="date" class="mt-1 block w-full rounded-2xl border-slate-200 p-3 font-bold" :value="old('date_transaction', date('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('date_transaction')" />
                            </div>

                            <div>
                                <x-input-label for="documents" :value="__('Documents joints (PDF, Images)')" class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2" />
                                <input id="documents" name="documents[]" type="file" multiple class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold">{{ __('Compromis, bail, justificatifs, etc. Max 5Mo par fichier.') }}</p>
                                <x-input-error class="mt-2" :messages="$errors->get('documents.*')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="commentaire" :value="__('Notes de la transaction')" class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2" />
                            <textarea id="commentaire" name="commentaire" rows="4" class="mt-1 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-3xl shadow-sm p-4 font-medium text-slate-600">{{ old('commentaire') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('commentaire')" />
                        </div>

                        <div class="flex items-center justify-end pt-6 border-t border-slate-50">
                            <a href="{{ route('biens.show', $bien) }}" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 mr-8 transition-colors">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit" class="bg-slate-900 text-white px-10 py-4 rounded-[1.5rem] text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-xl shadow-slate-200">
                                {{ __('Enregistrer et Finaliser') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
