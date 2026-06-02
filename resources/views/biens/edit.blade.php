<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier le bien') }} : {{ $bien->titre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('biens.update', $bien) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Titre --}}
                            <div class="md:col-span-2">
                                <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Titre') }} *</label>
                                <input type="text" name="titre" id="titre" value="{{ old('titre', $bien->titre) }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Type --}}
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Type') }} *</label>
                                <select name="type" id="type" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach(['appartement','maison','terrain','bureau','commerce'] as $t)
                                        <option value="{{ $t }}" {{ old('type', $bien->type) == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Nature --}}
                            <div>
                                <label for="nature" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nature') }} *</label>
                                <select name="nature" id="nature" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="vente" {{ old('nature', $bien->nature) == 'vente' ? 'selected' : '' }}>Vente</option>
                                    <option value="location" {{ old('nature', $bien->nature) == 'location' ? 'selected' : '' }}>Location</option>
                                </select>
                            </div>

                            {{-- Prix --}}
                            <div>
                                <label for="prix" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Prix (GNF)') }} *</label>
                                <input type="number" name="prix" id="prix" value="{{ old('prix', $bien->prix) }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Surface --}}
                            <div>
                                <label for="surface" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Surface (m²)') }} *</label>
                                <input type="number" name="surface" id="surface" value="{{ old('surface', $bien->surface) }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Nb pièces --}}
                            <div>
                                <label for="nb_pieces" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nombre de pièces') }}</label>
                                <input type="number" name="nb_pieces" id="nb_pieces" value="{{ old('nb_pieces', $bien->nb_pieces) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Ville --}}
                            <div>
                                <label for="ville" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Ville') }} *</label>
                                <input type="text" name="ville" id="ville" value="{{ old('ville', $bien->ville) }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Adresse --}}
                            <div class="md:col-span-2">
                                <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Adresse complète') }} *</label>
                                <input type="text" name="adresse" id="adresse" value="{{ old('adresse', $bien->adresse) }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Description --}}
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }} *</label>
                                <textarea name="description" id="description" rows="5" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $bien->description) }}</textarea>
                            </div>

                            {{-- Images existantes --}}
                            @if($bien->images->count() > 0)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Images actuelles') }}</label>
                                <div class="grid grid-cols-3 sm:grid-cols-4 gap-4">
                                    @foreach($bien->images as $image)
                                    <div class="relative group rounded-2xl overflow-hidden border border-slate-100 shadow-sm">
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="Image" class="w-full h-24 object-cover">
                                        
                                        @if($image->is_main)
                                            <span class="absolute top-1 left-1 bg-indigo-600 text-white text-[8px] font-black uppercase px-2 py-0.5 rounded-full shadow-lg">Principale</span>
                                        @endif

                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <button type="submit" form="delete-photo-{{ $image->id }}" onclick="return confirm('Supprimer cette photo ?')" class="bg-rose-500 text-white p-2 rounded-xl hover:bg-rose-600 transition shadow-lg">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Géolocalisation (Map Picker) --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Localisation sur la carte') }}</label>
                                <div id="map-picker" class="mt-2 h-[300px] w-full rounded-xl border border-gray-200 overflow-hidden shadow-sm"></div>
                                <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">{{ __('Cliquez sur la carte ou déplacez le marqueur pour ajuster l\'emplacement.') }}</p>
                                
                                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $bien->latitude) }}">
                                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $bien->longitude) }}">

                                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const lat = {{ old('latitude', $bien->latitude) ?? 9.6412 }}; 
                                        const lng = {{ old('longitude', $bien->longitude) ?? -13.5784 }};
                                        
                                        const map = L.map('map-picker').setView([lat, lng], 15);
                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                            attribution: '&copy; OpenStreetMap'
                                        }).addTo(map);

                                        let marker = L.marker([lat, lng], {draggable: true}).addTo(map);

                                        function updateCoords(lat, lng) {
                                            document.getElementById('latitude').value = lat.toFixed(7);
                                            document.getElementById('longitude').value = lng.toFixed(7);
                                        }

                                        map.on('click', function(e) {
                                            const {lat, lng} = e.latlng;
                                            marker.setLatLng([lat, lng]);
                                            updateCoords(lat, lng);
                                        });

                                        marker.on('dragend', function(e) {
                                            const {lat, lng} = e.target.getLatLng();
                                            updateCoords(lat, lng);
                                        });
                                    });
                                </script>
                            </div>

                            {{-- Nouvelles images --}}
                            <div class="md:col-span-2">
                                <label for="images" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Ajouter des images') }}</label>
                                <input type="file" name="images[]" id="images" multiple accept="image/*" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                                <p class="text-xs text-gray-500 mt-1">Formats acceptés : JPG, PNG. Max 2 Mo par image.</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                            <a href="{{ route('biens.show', $bien) }}" class="text-sm text-gray-600 hover:text-gray-900 transition">← Retour</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-8 rounded-lg transition">
                                {{ __('Enregistrer les modifications') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulaires de suppression d'images (hors du formulaire principal) --}}
    @foreach($bien->images as $image)
        <form id="delete-photo-{{ $image->id }}" action="{{ route('biens.images.destroy', $image) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
</x-app-layout>
