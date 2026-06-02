<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-white leading-tight">
            {{ __('Diffusion de Messages (Broadcast)') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                    <i class="fa-solid fa-paper-plane mr-3"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="premium-card bg-white p-10">
                <div class="mb-8">
                    <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">Nouvelle Annonce</h3>
                    <p class="text-sm text-slate-500">Envoyez un message par mail et notification à vos utilisateurs.</p>
                </div>

                <form action="{{ route('admin.broadcast.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Cible du message</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <label class="relative flex items-center justify-center p-3 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all peer-checked:bg-indigo-600">
                                    <input type="radio" name="target" value="all" checked class="hidden peer">
                                    <span class="text-xs font-bold text-slate-700 peer-checked:text-white">Tous</span>
                                </label>
                                <label class="relative flex items-center justify-center p-3 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all">
                                    <input type="radio" name="target" value="agents" class="hidden peer">
                                    <span class="text-xs font-bold text-slate-700 peer-checked:text-white">Agents</span>
                                </label>
                                <label class="relative flex items-center justify-center p-3 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all">
                                    <input type="radio" name="target" value="proprietaires" class="hidden peer">
                                    <span class="text-xs font-bold text-slate-700 peer-checked:text-white">Proprios</span>
                                </label>
                                <label class="relative flex items-center justify-center p-3 bg-slate-50 border border-slate-100 rounded-2xl cursor-pointer hover:bg-slate-100 transition-all">
                                    <input type="radio" name="target" value="clients" class="hidden peer">
                                    <span class="text-xs font-bold text-slate-700 peer-checked:text-white">Clients</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Sujet de l'email</label>
                            <input type="text" name="subject" required placeholder="Ex: Grande promotion d'été sur les villas..." 
                                class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 text-sm font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm">
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Message</label>
                            <textarea name="message" rows="8" required placeholder="Rédigez votre annonce ici..." 
                                class="w-full bg-slate-50 border-slate-200 rounded-2xl py-4 px-6 text-sm font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm"></textarea>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="btn-premium w-full py-4 text-sm flex items-center justify-center">
                                <i class="fa-solid fa-paper-plane mr-3 text-lg"></i> Envoyer la campagne de diffusion
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="mt-8 bg-amber-50 border border-amber-100 rounded-2xl p-6 flex items-start">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-1 mr-4"></i>
                <div class="text-xs text-amber-700 font-medium leading-relaxed">
                    <strong>Attention :</strong> Ce message sera envoyé par email à tous les utilisateurs sélectionnés. Assurez-vous du contenu avant de valider l'envoi. Cette action est irréversible.
                </div>
            </div>
        </div>
    </div>

    <style>
        input[type="radio"]:checked + span {
            color: #4f46e5;
            font-weight: 800;
        }
        input[type="radio"]:checked ~ span {
             /* Target standard radio styling if not using hidden peer pattern correctly */
        }
    </style>
</x-app-layout>
