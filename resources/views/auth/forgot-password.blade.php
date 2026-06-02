<x-guest-layout>
    <div class="mb-6 text-sm text-slate-300 text-center leading-relaxed reveal">
        {{ __('Mot de passe oublié ? Pas de problème. Indiquez-nous votre adresse e-mail et nous vous enverrons un lien de réinitialisation qui vous permettra d\'en choisir un nouveau.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div class="reveal delay-1">
            <x-input-label for="email" :value="__('Email')" class="font-bold text-white/80 text-xs mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-envelope text-white/40 text-xs"></i>
                </div>
                <x-text-input id="email" class="block w-full pl-9 pr-3 py-2.5 bg-white/5 border border-white/10 focus:bg-white/10 focus:border-white/40 focus:ring-0 transition-all rounded-xl text-white placeholder-white/30 text-sm shadow-inner" type="email" name="email" :value="old('email')" required autofocus />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-400 text-xs" />
        </div>

        <div class="pt-2 reveal delay-2">
            <button type="submit" class="w-full bg-white text-slate-900 hover:bg-slate-200 font-black py-2.5 px-4 rounded-xl shadow-xl hover:-translate-y-0.5 transition-all duration-300 text-sm uppercase tracking-widest">
                {{ __('Envoyer le lien de réinitialisation') }}
            </button>
        </div>
    </form>
</x-guest-layout>
