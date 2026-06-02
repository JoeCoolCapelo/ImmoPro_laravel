<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-white leading-tight">
                {{ __('Mes Notifications') }}
            </h2>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('notifications.readAll') }}">
                    @csrf
                    <button type="submit" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                        {{ __('Tout marquer comme lu') }}
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-4">
                @forelse($notifications as $notification)
                    <div class="relative group">
                        @if(!$notification->read_at)
                            <form id="form-read-{{ $notification->id }}" method="POST" action="{{ route('notifications.read', $notification->id) }}" class="hidden">
                                @csrf @method('PATCH')
                            </form>
                        @endif

                        <div 
                            @if(!$notification->read_at) onclick="document.getElementById('form-read-{{ $notification->id }}').submit()" @else onclick="window.location='{{ $notification->data['url'] ?? '#' }}'" @endif
                            class="premium-card p-6 flex items-start space-x-4 cursor-pointer {{ $notification->read_at ? 'opacity-50' : 'border-l-4 border-indigo-500 shadow-xl' }} transition-all hover:scale-[1.01] hover:bg-slate-50">
                            
                            <div class="p-3 rounded-xl {{ $notification->read_at ? 'bg-slate-200 text-slate-500' : 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' }}">
                                @if(($notification->data['type'] ?? '') === 'bien_soumis')
                                    <i class="fa-solid fa-house-circle-check text-xl"></i>
                                @elseif(($notification->data['type'] ?? '') === 'visite_demandee')
                                    <i class="fa-solid fa-calendar-plus text-xl"></i>
                                @elseif(($notification->data['type'] ?? '') === 'transaction_confirmee')
                                    <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                                @else
                                    <i class="fa-solid fa-bell text-xl"></i>
                                @endif
                            </div>

                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-black text-slate-900 {{ !$notification->read_at ? 'text-indigo-900' : '' }}">{{ $notification->data['message'] ?? 'Nouvelle notification' }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1 flex items-center uppercase tracking-widest">
                                            <i class="fa-solid fa-clock mr-1.5 text-indigo-500"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if(!$notification->read_at)
                                        <div class="h-2 w-2 bg-indigo-600 rounded-full animate-pulse shadow-[0_0_10px_rgba(79,70,229,0.5)]"></div>
                                    @endif
                                </div>
                                
                                @if(isset($notification->data['url']))
                                    <div class="mt-3 inline-flex items-center text-sm font-black {{ !$notification->read_at ? 'text-indigo-600' : 'text-slate-400' }} group">
                                        {{ __('Voir les détails') }}
                                        <i class="fa-solid fa-arrow-right-long ml-2 transition-transform group-hover:translate-x-1"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 glass-card rounded-3xl">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-bell-slash text-3xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500 font-black text-lg">{{ __('Vous n\'avez aucune notification.') }}</p>
                    </div>
                @endforelse

                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
