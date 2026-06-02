<x-guest-layout>
    <div class="text-center mb-6 reveal">
        <h2 class="text-xl font-extrabold text-white tracking-tight uppercase">Inscription</h2>
        <p class="text-xs text-slate-300 mt-1 font-medium">Créez votre compte gratuitement</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Nom -->
        <div class="reveal delay-1">
            <x-input-label for="name" :value="__('Nom complet')" class="font-bold text-white/80 text-xs mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-user text-white/40 text-xs"></i>
                </div>
                <x-text-input id="name" class="block w-full pl-9 pr-3 py-2.5 bg-white/5 border border-white/10 focus:bg-white/10 focus:border-white/40 focus:ring-0 transition-all rounded-xl text-white placeholder-white/30 text-sm shadow-inner" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <!-- Email -->
        <div class="reveal delay-2">
            <x-input-label for="email" :value="__('Adresse e-mail')" class="font-bold text-white/80 text-xs mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-envelope text-white/40 text-xs"></i>
                </div>
                <x-text-input id="email" class="block w-full pl-9 pr-3 py-2.5 bg-white/5 border border-white/10 focus:bg-white/10 focus:border-white/40 focus:ring-0 transition-all rounded-xl text-white placeholder-white/30 text-sm shadow-inner" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="exemple@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <!-- Rôle -->
        <div class="reveal delay-3">
            <x-input-label for="role" :value="__('Je suis un(e)')" class="font-bold text-white/80 text-xs mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-id-badge text-white/40 text-xs"></i>
                </div>
                <select id="role" name="role" required
                    class="block w-full pl-9 pr-3 py-2.5 bg-white/5 border border-white/10 focus:bg-white/10 focus:border-white/40 focus:ring-0 transition-all rounded-xl text-white placeholder-white/30 text-sm shadow-inner">
                    <option value="" class="bg-slate-900">-- Choisir un rôle --</option>
                    <option value="proprietaire" {{ old('role') === 'proprietaire' ? 'selected' : '' }} class="bg-slate-900">Propriétaire (vendre/louer)</option>
                    <option value="client" {{ old('role') === 'client' ? 'selected' : '' }} class="bg-slate-900">Client (rechercher un bien)</option>
                </select>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <!-- Mot de passe -->
        <div class="reveal delay-4">
            <x-input-label for="password" :value="__('Mot de passe')" class="font-bold text-white/80 text-xs mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-white/40 text-xs"></i>
                </div>
                <x-text-input id="password" class="block w-full pl-9 pr-3 py-2.5 bg-white/5 border border-white/10 focus:bg-white/10 focus:border-white/40 focus:ring-0 transition-all rounded-xl text-white placeholder-white/30 text-sm shadow-inner"
                                type="password"
                                name="password"
                                required autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <!-- Confirmation du mot de passe -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="font-bold text-white/80 text-xs mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-white/40 text-xs"></i>
                </div>
                <x-text-input id="password_confirmation" class="block w-full pl-9 pr-3 py-2.5 bg-white/5 border border-white/10 focus:bg-white/10 focus:border-white/40 focus:ring-0 transition-all rounded-xl text-white placeholder-white/30 text-sm shadow-inner"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <div class="pt-2 reveal delay-[0.5s]">
            <button type="submit" class="w-full bg-white text-slate-900 hover:bg-slate-200 font-black py-2.5 px-4 rounded-xl shadow-xl hover:-translate-y-0.5 transition-all duration-300 text-sm uppercase tracking-widest">
                S'inscrire
            </button>
        </div>
        
        <div class="flex items-center justify-center mt-6">
            <a class="text-sm text-slate-400 hover:text-white transition-all duration-200" href="{{ route('login') }}">
                {{ __('Déjà inscrit ?') }} <span class="font-black text-white underline underline-offset-4 ml-1">{{ __('Se connecter') }}</span>
            </a>
        </div>
    </form>
</x-guest-layout>
