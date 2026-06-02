<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-white leading-tight">
                {{ __('Gestion des Documents') }}
            </h2>
            @can('create', App\Models\Document::class)
                <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="btn-premium flex items-center">
                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i>
                    {{ __('Ajouter un document') }}
                </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Stats / Info --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="premium-card p-6 bg-white border-l-4 border-indigo-500">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Documents</p>
                    <p class="text-3xl font-black text-slate-900">{{ $documents->total() }}</p>
                </div>
            </div>

            {{-- Table --}}
            <div class="premium-card bg-white overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Document</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Type</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Lié à</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Ajouté par</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($documents as $doc)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg mr-3">
                                                @php
                                                    $ext = pathinfo($doc->path, PATHINFO_EXTENSION);
                                                    $icon = match($ext) {
                                                        'pdf' => 'fa-file-pdf',
                                                        'jpg', 'jpeg', 'png' => 'fa-file-image',
                                                        'doc', 'docx' => 'fa-file-word',
                                                        default => 'fa-file'
                                                    };
                                                @endphp
                                                <i class="fa-solid {{ $icon }} text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-sm">{{ $doc->titre }}</p>
                                                <p class="text-[10px] text-slate-400 font-medium">{{ $doc->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-black uppercase text-slate-500">
                                        <span class="px-2 py-1 bg-slate-100 rounded-md">{{ $doc->type }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($doc->bien)
                                            <a href="{{ route('biens.show', $doc->bien) }}" class="text-xs font-bold text-indigo-600 hover:underline">
                                                <i class="fa-solid fa-house mr-1"></i> {{ $doc->bien->titre }}
                                            </a>
                                        @elseif($doc->transaction)
                                            <span class="text-xs font-bold text-emerald-600">
                                                <i class="fa-solid fa-handshake mr-1"></i> Trans #{{ $doc->transaction->id }}
                                            </span>
                                        @else
                                            <span class="text-xs font-medium text-slate-400">Aucun lien</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-6 w-6 rounded-full bg-slate-100 flex items-center justify-center mr-2 text-[10px] font-black text-slate-500">
                                                {{ substr($doc->user->name, 0, 1) }}
                                            </div>
                                            <span class="text-xs font-bold text-slate-600">{{ $doc->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('documents.download', $doc) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Télécharger">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                            @can('delete', $doc)
                                                <form action="{{ route('documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Supprimer ce document ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Supprimer">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <i class="fa-solid fa-folder-open text-4xl text-slate-200 mb-4"></i>
                                        <p class="text-slate-400 font-medium">Aucun document trouvé.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Upload Modal --}}
    <div id="uploadModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('uploadModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-8 pt-8 pb-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Ajouter un document</h3>
                            <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Titre du document</label>
                                <input type="text" name="titre" required class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-bold text-sm" placeholder="ex: Contrat de bail - Villa Moderne">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Type</label>
                                <select name="type" required class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-bold text-sm">
                                    <option value="contrat">Contrat</option>
                                    <option value="bail">Bail</option>
                                    <option value="identité">Pièce d'identité</option>
                                    <option value="reçu">Reçu de paiement</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Lier à un bien (Optionnel)</label>
                                <select name="bien_id" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-bold text-sm">
                                    <option value="">-- Aucun --</option>
                                    @foreach(App\Models\Bien::all() as $bien)
                                        <option value="{{ $bien->id }}">{{ $bien->titre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Fichier (PDF, Image, Word - Max 10Mo)</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-indigo-400 transition-colors cursor-pointer relative">
                                    <div class="space-y-1 text-center">
                                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-400 mb-2"></i>
                                        <div class="flex text-sm text-slate-600">
                                            <span class="font-black text-indigo-600">Téléverser un fichier</span>
                                        </div>
                                    </div>
                                    <input type="file" name="file" required class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-8 py-6 flex flex-row-reverse">
                        <button type="submit" class="btn-premium ml-3">Enregistrer</button>
                        <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="px-6 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-black rounded-xl hover:bg-slate-50 transition-colors uppercase tracking-widest">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
