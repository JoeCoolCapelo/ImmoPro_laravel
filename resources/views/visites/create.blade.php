<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Demander une visite') }} : {{ $bien->titre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('visites.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="bien_id" value="{{ $bien->id }}">

                        <div>
                            <x-input-label for="date_visite" :value="__('Date et Heure souhaitées')" />
                            <x-text-input id="date_visite" name="date_visite" type="datetime-local" class="mt-1 block w-full" :value="old('date_visite')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('date_visite')" />
                        </div>

                        <div>
                            <x-input-label for="commentaire" :value="__('Message (optionnel)')" />
                            <textarea id="commentaire" name="commentaire" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Ex: Je suis disponible uniquement le matin...">{{ old('commentaire') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('commentaire')" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Envoyer la demande') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
