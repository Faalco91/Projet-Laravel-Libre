<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $livre->titre }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Lien retour -->
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    ← Retour au catalogue
                </a>
            </div>

            <!-- Détails du livre -->
            <div class="bg-white border rounded-lg p-6 mb-6 shadow-sm">
                <h1 class="text-2xl font-bold mb-4">{{ $livre->titre }}</h1>
                <p class="text-gray-600 mb-2"><strong>Auteur:</strong> {{ $livre->auteur }}</p>
                <p class="text-gray-600 mb-2"><strong>Catégorie:</strong> {{ $livre->categorie }}</p>
                <p class="text-gray-600 mb-4"><strong>Année:</strong> {{ $livre->annee }}</p>
                <p class="text-gray-700 mb-4">{{ $livre->description }}</p>
                
                <!-- Bouton favori -->
                <form method="POST" action="{{ route('favoris.toggle') }}" class="inline">
                    @csrf
                    <input type="hidden" name="livre_id" value="{{ $livre->id }}">
                    <button type="submit" style="background-color: #eab308; color: white; padding: 8px 16px; border-radius: 4px; border: none; cursor: pointer;">
                        @if($livre->favoris->count() > 0)
                            ⭐ Retirer des favoris
                        @else
                            ☆ Ajouter aux favoris
                        @endif
                    </button>
                </form>
            </div>

            <!-- Section commentaires -->
            <div class="bg-white border rounded-lg p-6 shadow-sm">
                <h3 class="text-xl font-bold mb-4">Commentaires ({{ $livre->commentaires->count() }})</h3>

                <!-- Formulaire ajouter commentaire -->
                <div class="mb-6 p-4 bg-gray-50 rounded">
                    <h4 class="font-medium mb-3">Ajouter un commentaire</h4>
                    <form method="POST" action="{{ route('commentaires.store') }}">
                        @csrf
                        <input type="hidden" name="livre_id" value="{{ $livre->id }}">
                        <div class="mb-3">
                            <textarea name="texte" rows="3" placeholder="Votre commentaire..." 
                                      style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" 
                                      required>{{ old('texte') }}</textarea>
                            @error('texte')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" style="background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 4px; border: none; cursor: pointer;">
                            💬 Publier commentaire
                        </button>
                    </form>
                </div>

                <!-- Liste des commentaires -->
                @forelse($livre->commentaires as $commentaire)
                    <div class="border-b pb-4 mb-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <strong class="text-gray-800">{{ $commentaire->user->name }}</strong>
                                <span class="text-gray-500 text-sm ml-2">{{ $commentaire->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            @if($commentaire->user_id == Auth::id())
                                <form method="POST" action="{{ route('commentaires.destroy', $commentaire->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="background-color: #ef4444; color: white; padding: 4px 8px; border-radius: 4px; border: none; cursor: pointer; font-size: 12px;"
                                            onclick="return confirm('Supprimer ce commentaire ?')">
                                        🗑️
                                    </button>
                                </form>
                            @endif
                        </div>
                        <p class="text-gray-700">{{ $commentaire->texte }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Aucun commentaire pour ce livre. Soyez le premier à commenter !</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>