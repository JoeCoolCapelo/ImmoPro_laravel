<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6 reveal">
        <h2 class="text-xl font-extrabold text-white tracking-tight uppercase">Connexion</h2>
        <p class="text-xs text-slate-300 mt-1 font-medium">Connectez-vous à votre espace</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div class="reveal delay-1">
            <x-input-label for="email" :value="__('Adresse Email')" class="font-bold text-white/80 text-xs mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-envelope text-white/40 text-xs"></i>
                </div>
                <x-text-input id="email" class="block w-full pl-9 pr-3 py-2.5 bg-white/5 border border-white/10 focus:bg-white/10 focus:border-white/40 focus:ring-0 transition-all rounded-xl text-white placeholder-white/30 text-sm shadow-inner" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="exemple@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <!-- Password -->
        <div class="reveal delay-2">
            <x-input-label for="password" :value="__('Mot de passe')" class="font-bold text-white/80 text-xs mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-white/40 text-xs"></i>
                </div>
                <x-text-input id="password" class="block w-full pl-9 pr-3 py-2.5 bg-white/5 border border-white/10 focus:bg-white/10 focus:border-white/40 focus:ring-0 transition-all rounded-xl text-white placeholder-white/30 text-sm shadow-inner"
                                type="password"
                                name="password"
                                required autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-400" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-1 reveal delay-3">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" checked class="rounded border-white/20 bg-white/5 text-slate-900 shadow-sm focus:ring-white/20 w-3.5 h-3.5 cursor-pointer transition-colors" name="remember">
                <span class="ms-2 text-xs font-medium text-slate-400 group-hover:text-white transition-colors">Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-[11px] font-bold text-white hover:text-slate-300 transition-all" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <div class="pt-2 reveal delay-4">
            <button type="submit" class="w-full bg-white text-slate-900 hover:bg-slate-200 font-black py-2.5 px-4 rounded-xl shadow-xl hover:-translate-y-0.5 transition-all duration-300 text-sm uppercase tracking-widest">
                Se connecter
            </button>
        </div>
        
        @if (Route::has('register'))
            <p class="text-center text-xs font-medium text-slate-400 mt-4">
                Pas encore de compte ? 
                <a href="{{ route('register') }}" class="font-black text-white hover:text-slate-300 hover:underline underline-offset-2 transition-all">S'inscrire</a>
            </p>
        @endif
    </form>
</x-guest-layout>
