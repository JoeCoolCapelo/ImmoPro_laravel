<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter un nouveau bien') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('biens.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Titre -->
                            <div class="col-span-2">
                                <x-input-label for="titre" :value="__('Titre de l\'annonce')" />
                                <x-text-input id="titre" name="titre" type="text" class="mt-1 block w-full" :value="old('titre')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('titre')" />
                            </div>

                            <!-- Type -->
                            <div>
                                <x-input-label for="type" :value="__('Type de bien')" />
                                <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="maison" {{ old('type') == 'maison' ? 'selected' : '' }}>Maison</option>
                                    <option value="appartement" {{ old('type') == 'appartement' ? 'selected' : '' }}>Appartement</option>
                                    <option value="terrain" {{ old('type') == 'terrain' ? 'selected' : '' }}>Terrain</option>
                                    <option value="local" {{ old('type') == 'local' ? 'selected' : '' }}>Local commercial</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('type')" />
                            </div>

                            <!-- Nature -->
                            <div>
                                <x-input-label for="nature" :value="__('Nature de la transaction')" />
                                <select id="nature" name="nature" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="vente" {{ old('nature') == 'vente' ? 'selected' : '' }}>Vente</option>
                                    <option value="location" {{ old('nature') == 'location' ? 'selected' : '' }}>Location</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('nature')" />
                            </div>

                            <!-- Prix -->
                            <div>
                                <x-input-label for="prix" :value="__('Prix (GNF)')" />
                                <x-text-input id="prix" name="prix" type="number" class="mt-1 block w-full" :value="old('prix')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('prix')" />
                            </div>

                            <!-- Surface -->
                            <div>
                                <x-input-label for="surface" :value="__('Surface (m²)')" />
                                <x-text-input id="surface" name="surface" type="number" step="0.01" class="mt-1 block w-full" :value="old('surface')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('surface')" />
                            </div>

                            <!-- NB Pièces -->
                            <div>
                                <x-input-label for="nb_pieces" :value="__('Nombre de pièces')" />
                                <x-text-input id="nb_pieces" name="nb_pieces" type="number" class="mt-1 block w-full" :value="old('nb_pieces')" />
                                <x-input-error class="mt-2" :messages="$errors->get('nb_pieces')" />
                            </div>

                            <!-- Ville -->
                            <div>
                                <x-input-label for="ville" :value="__('Ville')" />
                                <x-text-input id="ville" name="ville" type="text" class="mt-1 block w-full" :value="old('ville')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('ville')" />
                            </div>

                            <!-- Adresse -->
                            <div class="col-span-2">
                                <x-input-label for="adresse" :value="__('Adresse complète')" />
                                <x-text-input id="adresse" name="adresse" type="text" class="mt-1 block w-full" :value="old('adresse')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('adresse')" />
                            </div>

                            <!-- Description -->
                            <div class="col-span-2">
                                <x-input-label for="description" :value="__('Description détaillée')" />
                                <textarea id="description" name="description" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <!-- Géolocalisation (Map Picker) -->
                            <div class="col-span-2">
                                <x-input-label :value="__('Localisation sur la carte')" />
                                <div id="map-picker" class="mt-2 h-[300px] w-full rounded-2xl border border-slate-200 overflow-hidden shadow-inner"></div>
                                <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">{{ __('Cliquez sur la carte pour définir l\'emplacement exact du bien.') }}</p>
                                
                                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                                <x-input-error class="mt-2" :messages="$errors->get('latitude')" />

                                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Default coordinates: Conakry, Guinea
                                        const lat = {{ old('latitude') ?? 9.6412 }}; 
                                        const lng = {{ old('longitude') ?? -13.5784 }};
                                        
                                        const map = L.map('map-picker').setView([lat, lng], 13);
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

                            <!-- Images -->
                            <div class="col-span-2">
                                <x-input-label for="images" :value="__('Photos du bien')" />
                                <input id="images" name="images[]" type="file" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                <p class="mt-1 text-sm text-gray-500 italic">Sélectionnez plusieurs fichiers pour créer une galerie.</p>
                                <x-input-error class="mt-2" :messages="$errors->get('images')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Enregistrer le bien') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
