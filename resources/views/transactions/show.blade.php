<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('transactions.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-slate-100 hover:bg-slate-50 transition-colors">
                    <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h2 class="font-black text-2xl text-slate-800 leading-tight">
                    {{ __('Détails de la Transaction') }} #{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}
                </h2>
            </div>
            <span class="status-badge {{ $transaction->type === 'vente' ? 'bg-indigo-50 text-indigo-600' : 'bg-emerald-50 text-emerald-600' }}">
                {{ strtoupper($transaction->type) }}
            </span>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm">
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                {{-- Main Info --}}
                <div class="lg:col-span-2 space-y-8">
                    <div class="premium-card p-10">
                        <div class="flex justify-between items-start border-b border-slate-50 pb-8 mb-8">
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">{{ __('Montant de la transaction') }}</p>
                                <h3 class="text-4xl font-black text-indigo-600">
                                    {{ number_format($transaction->montant, 0, ',', ' ') }} <small class="text-sm">GNF</small>
                                </h3>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">{{ __('Date de l\'acte') }}</p>
                                <p class="text-lg font-black text-slate-800">{{ \Carbon\Carbon::parse($transaction->date_transaction)->format('d F Y') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-10">
                            <div>
                                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">{{ __('Bien Immobilier') }}</h4>
                                @if($transaction->bien)
                                    <a href="{{ route('biens.show', $transaction->bien) }}" class="flex items-center p-4 bg-slate-50 rounded-2xl hover:bg-slate-100 transition-colors border border-slate-100">
                                        <div class="h-12 w-12 rounded-xl overflow-hidden mr-4">
                                            @if($transaction->bien->images->where('is_main', true)->first())
                                                <img src="{{ Storage::url($transaction->bien->images->where('is_main', true)->first()->path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400 text-[8px]">NO IMG</div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-black text-slate-800 text-sm">{{ $transaction->bien->titre }}</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $transaction->bien->ville }}</p>
                                        </div>
                                    </a>
                                @else
                                    <div class="flex items-center p-4 bg-rose-50 rounded-2xl border border-rose-100">
                                        <div class="h-12 w-12 bg-rose-100 rounded-xl flex items-center justify-center text-rose-500">
                                            <i class="fa-solid fa-house-circle-xmark"></i>
                                        </div>
                                        <div class="ms-4">
                                            <p class="font-black text-rose-800 text-sm">{{ __('Propriété supprimée') }}</p>
                                            <p class="text-[10px] text-rose-400 font-bold uppercase">{{ __('Non disponible') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">{{ __('Agent Responsable') }}</h4>
                                <div class="flex items-center p-4">
                                    <div class="h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-black text-xs">
                                        {{ substr($transaction->agent?->name ?? '?', 0, 1) }}
                                    </div>
                                    <div class="ms-3">
                                        <p class="font-black text-slate-800 text-sm">{{ $transaction->agent?->name ?? __('Agent inconnu') }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">{{ __('Agent ImmoPro') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">{{ __('Commentaires') }}</h4>
                            <div class="p-6 bg-slate-50 rounded-3xl text-slate-600 font-medium italic border border-slate-100">
                                {{ $transaction->commentaire ?? __('Aucun commentaire enregistré pour cette transaction.') }}
                            </div>
                        </div>
                    </div>

                    {{-- Accord Formel --}}
                    <div class="premium-card p-10 bg-white border-2 {{ ($transaction->client_signed && $transaction->owner_signed && $transaction->agency_signed) ? 'border-emerald-500' : 'border-amber-200' }}">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-black text-slate-900 flex items-center text-sm uppercase tracking-widest">
                                <i class="fa-solid fa-file-signature mr-3 text-indigo-600"></i> Accord Formel (Signature Numérique)
                            </h3>
                            @if($transaction->client_signed && $transaction->owner_signed && $transaction->agency_signed)
                                <span class="px-4 py-1.5 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-emerald-200">Accord Finalisé</span>
                            @else
                                <span class="px-4 py-1.5 bg-amber-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-amber-200">En attente de signature</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            {{-- Signature Client --}}
                            <div class="p-6 rounded-[2rem] {{ $transaction->client_signed ? 'bg-emerald-50 border border-emerald-100' : 'bg-slate-50 border border-slate-100' }}">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Le Client</p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-black text-slate-900 text-sm">{{ $transaction->client->name }}</p>
                                        @if($transaction->client_signed)
                                            <p class="text-[9px] font-bold text-emerald-600 uppercase mt-1">
                                                <i class="fa-solid fa-check-double mr-1"></i> Signé le {{ $transaction->client_signed_at?->format('d/m/Y à H:i') }}
                                            </p>
                                        @else
                                            <p class="text-[9px] font-bold text-slate-400 uppercase mt-1 italic">Signature en attente...</p>
                                        @endif
                                    </div>
                                    @if(auth()->id() === $transaction->user_id && !$transaction->client_signed)
                                        <button onclick="openSignatureModal('{{ route('transactions.sign', $transaction) }}')" class="px-4 py-2 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 transition shadow-lg">Signer</button>
                                    @elseif($transaction->client_signed)
                                        @if($transaction->client_signature_image)
                                            <img src="{{ $transaction->client_signature_image }}" alt="Signature Client" class="h-10 border border-slate-200 rounded bg-white">
                                        @else
                                            <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center text-emerald-500 shadow-sm border border-emerald-100">
                                                <i class="fa-solid fa-stamp text-lg"></i>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            {{-- Signature Propriétaire --}}
                            <div class="p-6 rounded-[2rem] {{ $transaction->owner_signed ? 'bg-emerald-50 border border-emerald-100' : 'bg-slate-50 border border-slate-100' }}">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Le Propriétaire</p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-black text-slate-900 text-sm">{{ $transaction->bien->owner->name ?? 'N/A' }}</p>
                                        @if($transaction->owner_signed)
                                            <p class="text-[9px] font-bold text-emerald-600 uppercase mt-1">
                                                <i class="fa-solid fa-check-double mr-1"></i> Signé le {{ $transaction->owner_signed_at?->format('d/m/Y à H:i') }}
                                            </p>
                                        @else
                                            <p class="text-[9px] font-bold text-slate-400 uppercase mt-1 italic">Signature en attente...</p>
                                        @endif
                                    </div>
                                    @if($transaction->bien && auth()->id() === $transaction->bien->user_id && !$transaction->owner_signed)
                                        <button onclick="openSignatureModal('{{ route('transactions.sign', $transaction) }}')" class="px-4 py-2 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 transition shadow-lg">Signer</button>
                                    @elseif($transaction->owner_signed)
                                        @if($transaction->owner_signature_image)
                                            <img src="{{ $transaction->owner_signature_image }}" alt="Signature Propriétaire" class="h-10 border border-slate-200 rounded bg-white">
                                        @else
                                            <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center text-emerald-500 shadow-sm border border-emerald-100">
                                                <i class="fa-solid fa-stamp text-lg"></i>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            {{-- Signature Agence --}}
                            <div class="p-6 rounded-[2rem] {{ $transaction->agency_signed ? 'bg-indigo-50 border border-indigo-100' : 'bg-slate-50 border border-slate-100' }}">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">L'Agence (Direction)</p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-black text-slate-900 text-sm">ImmoPro</p>
                                        @if($transaction->agency_signed)
                                            <p class="text-[9px] font-bold text-indigo-600 uppercase mt-1">
                                                <i class="fa-solid fa-check-double mr-1"></i> Signé le {{ $transaction->agency_signed_at?->format('d/m/Y à H:i') }}
                                            </p>
                                        @else
                                            <p class="text-[9px] font-bold text-slate-400 uppercase mt-1 italic">Signature en attente...</p>
                                        @endif
                                    </div>
                                    @if(auth()->user()->hasRole('admin') && !$transaction->agency_signed)
                                        <button onclick="openSignatureModal('{{ route('transactions.sign', $transaction) }}')" class="px-4 py-2 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 transition shadow-lg">Signer</button>
                                    @elseif($transaction->agency_signed)
                                        @if($transaction->agency_signature_image)
                                            <img src="{{ $transaction->agency_signature_image }}" alt="Signature Agence" class="h-10 border border-slate-200 rounded bg-white">
                                        @else
                                            <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center text-indigo-500 shadow-sm border border-indigo-100">
                                                <i class="fa-solid fa-stamp text-lg"></i>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-50">
                            <p class="text-[9px] text-slate-400 italic text-center uppercase tracking-widest">
                                En cliquant sur "Signer", vous validez numériquement les termes du contrat.
                                @if($transaction->signature_ip)
                                    (Identifiant Digital : {{ $transaction->signature_ip }})
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Documents Section --}}
                    <div class="premium-card p-10">
                        <h3 class="text-xl font-black text-slate-900 mb-8 flex items-center">
                            <svg class="h-6 w-6 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ __('Documents Contractuels') }}
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($transaction->documents as $doc)
                                <div class="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl hover:shadow-md transition-shadow">
                                    <div class="flex items-center overflow-hidden">
                                        <div class="p-2 bg-indigo-50 rounded-xl mr-3 text-indigo-500">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-sm font-black text-slate-800 truncate">{{ $doc->titre }}</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase">{{ __('Ajouté le') }} {{ $doc->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($doc->path) }}" target="_blank" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>
                                </div>
                            @empty
                                <div class="col-span-2 py-10 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                                    <p class="text-slate-400 font-bold text-sm">{{ __('Aucun document attaché à cette transaction.') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-8">
                    {{-- Client Info --}}
                    <div class="premium-card p-8">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">{{ __('Client Acquéreur / Locataire') }}</h4>
                        <div class="flex items-center mb-6">
                            <div class="h-14 w-14 bg-emerald-500 rounded-2xl flex items-center justify-center text-white text-xl font-black shadow-lg shadow-emerald-200">
                                {{ substr($transaction->client?->name ?? '?', 0, 1) }}
                            </div>
                            <div class="ms-4">
                                <p class="font-black text-slate-900 text-lg">{{ $transaction->client?->name ?? __('Client inconnu') }}</p>
                                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">{{ __('Client Vérifié') }}</p>
                            </div>
                        </div>
                        <div class="space-y-4 pt-6 border-t border-slate-50">
                            <div class="flex items-center text-sm">
                                <svg class="h-4 w-4 mr-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span class="font-bold text-slate-600">{{ $transaction->client?->email ?? __('Email non disponible') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Summary Box --}}
                    <div class="premium-card p-8 bg-slate-900 text-white">
                        <h4 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-6">{{ __('Récapitulatif') }}</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 text-xs">{{ __('Montant Brut') }}</span>
                                <span class="font-black text-lg">{{ number_format($transaction->montant, 0, ',', ' ') }} GNF</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 text-xs">{{ __('Commission') }} ({{ number_format($transaction->commission_pourcentage, 0) }}%)</span>
                                <span class="font-black text-emerald-400 text-sm">{{ number_format($transaction->commission_montant, 0, ',', ' ') }} GNF</span>
                            </div>
                            <div class="pt-6 border-t border-white/10 mt-6 space-y-3">
                                @if($transaction->client_signed && $transaction->owner_signed && $transaction->agency_signed)
                                    <a href="{{ route('transactions.contract.pdf', $transaction) }}" target="_blank" class="block w-full py-4 bg-indigo-500 text-white text-center rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-600 transition-colors shadow-lg shadow-indigo-500/30 mb-3">
                                        <i class="fa-solid fa-file-contract mr-2"></i>
                                        {{ __('Télécharger le Contrat') }}
                                    </a>
                                @endif
                                <a href="{{ route('transactions.pdf', $transaction) }}" target="_blank" class="block w-full py-4 bg-white text-slate-900 text-center rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-100 transition-colors shadow-lg">
                                    <i class="fa-solid fa-file-pdf mr-2"></i>
                                    {{ $transaction->type === 'location' ? __('Générer la Quittance') : __('Générer le Reçu') }}
                                </a>
                                @if($transaction->type === 'location' && !$transaction->is_archived)
                                    <a href="{{ route('paiements.index', $transaction) }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                                        <i class="fa-solid fa-money-bill-transfer"></i>
                                        Suivi des Loyers Mensuels
                                    </a>

                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('agent'))
                                        <form method="POST" action="{{ route('transactions.liberer', $transaction) }}" class="inline">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Confirmer que le locataire quitte les lieux ? Le bien redeviendra disponible.')" 
                                                class="flex items-center justify-center gap-2 px-6 py-3 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition shadow-sm">
                                                <i class="fa-solid fa-door-open"></i>
                                                Libérer le bien
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Signature --}}
    <div id="signatureModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4 transition-opacity duration-300 opacity-0">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="p-8 bg-slate-900 text-white flex justify-between items-center">
                <h3 class="font-black text-lg uppercase tracking-widest flex items-center"><i class="fa-solid fa-pen-nib mr-3 text-indigo-400"></i> Dessinez votre signature</h3>
                <button onclick="closeSignatureModal()" class="text-white/50 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <div class="p-8">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Zone de signature</p>
                <div class="border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50 relative overflow-hidden mb-4">
                    <canvas id="signatureCanvas" class="w-full h-48 cursor-crosshair touch-none"></canvas>
                </div>
                
                <form id="signatureForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="signature_data" id="signatureData">
                    
                    <div class="flex items-center justify-between mt-6">
                        <button type="button" onclick="clearSignature()" class="px-4 py-2 text-rose-500 hover:bg-rose-50 rounded-xl text-[10px] font-black uppercase tracking-widest transition">
                            <i class="fa-solid fa-eraser mr-2"></i> Effacer
                        </button>
                        <button type="button" onclick="saveSignature()" class="px-6 py-3 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            <i class="fa-solid fa-check mr-2"></i> Valider la signature
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('signatureCanvas');
        const ctx = canvas.getContext('2d');
        let isDrawing = false;

        // Configuration du canvas
        function resizeCanvas() {
            // Rendre le canvas net sur les écrans haute densité (Retina)
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width;
            canvas.height = rect.height;
            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#0f172a'; // slate-900
        }
        
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        // Événements souris
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        // Événements tactiles (téléphones/tablettes)
        canvas.addEventListener('touchstart', handleTouchStart, {passive: false});
        canvas.addEventListener('touchmove', handleTouchMove, {passive: false});
        canvas.addEventListener('touchend', stopDrawing);

        function startDrawing(e) {
            isDrawing = true;
            draw(e);
        }

        function draw(e) {
            if (!isDrawing) return;
            
            e.preventDefault(); // Empêcher le scroll sur mobile
            
            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            ctx.lineTo(x, y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, y);
        }

        function handleTouchStart(e) {
            if (e.touches.length > 0) {
                isDrawing = true;
                const rect = canvas.getBoundingClientRect();
                const touch = e.touches[0];
                ctx.beginPath();
                ctx.moveTo(touch.clientX - rect.left, touch.clientY - rect.top);
            }
        }

        function handleTouchMove(e) {
            if (!isDrawing) return;
            e.preventDefault();
            if (e.touches.length > 0) {
                const rect = canvas.getBoundingClientRect();
                const touch = e.touches[0];
                ctx.lineTo(touch.clientX - rect.left, touch.clientY - rect.top);
                ctx.stroke();
                ctx.beginPath();
                ctx.moveTo(touch.clientX - rect.left, touch.clientY - rect.top);
            }
        }

        function stopDrawing() {
            isDrawing = false;
            ctx.beginPath();
        }

        function clearSignature() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        function openSignatureModal(formAction) {
            const modal = document.getElementById('signatureModal');
            document.getElementById('signatureForm').action = formAction;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.firstElementChild.classList.remove('scale-95');
                resizeCanvas(); // Important: redimensionner quand le modal est visible
            }, 10);
        }

        function closeSignatureModal() {
            const modal = document.getElementById('signatureModal');
            modal.classList.add('opacity-0');
            modal.firstElementChild.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                clearSignature();
            }, 300);
        }

        function saveSignature() {
            // Vérifier si le canvas est vide (optionnel mais recommandé)
            const blank = document.createElement('canvas');
            blank.width = canvas.width;
            blank.height = canvas.height;
            if (canvas.toDataURL() === blank.toDataURL()) {
                alert('Veuillez dessiner votre signature avant de valider.');
                return;
            }

            // Récupérer l'image en base64
            const dataUrl = canvas.toDataURL('image/png');
            document.getElementById('signatureData').value = dataUrl;
            
            // Soumettre le formulaire
            document.getElementById('signatureForm').submit();
        }
    </script>
</x-app-layout>
