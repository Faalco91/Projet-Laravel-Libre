<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $book->titre }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Navigation -->
            <div class="mb-6 flex flex-wrap gap-2">
                <a href="{{ route('books.index') }}" style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    ← Retour à la liste
                </a>
                <a href="{{ route('dashboard') }}" style="background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    🏠 Tableau de bord
                </a>
                @can('update', $book)
                    <a href="{{ route('books.edit', $book->id) }}" style="background-color: #f59e0b; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                        ✏️ Modifier ce livre
                    </a>
                @endcan
            </div>

            <!-- Détails du livre -->
            <div class="bg-white border rounded-lg p-6 mb-6 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold mb-4">{{ $book->titre }}</h1>
                        <div class="space-y-2 text-gray-600">
                            <p><strong>Auteur:</strong> {{ $book->auteur }}</p>
                            <p><strong>Catégorie:</strong> {{ $book->categorie ?? 'Non spécifiée' }}</p>
                            @if($book->annee)
                                <p><strong>Année de publication:</strong> {{ $book->annee }}</p>
                            @endif
                            @if($book->user_id)
                                <p class="text-sm"><strong>Ajouté par:</strong> Utilisateur #{{ $book->user_id }}</p>
                            @endif
                            <p class="text-sm"><strong>Date d'ajout:</strong> {{ $book->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    
                    <!-- Actions rapides -->
                    <div class="ml-6 text-right">
                        <!-- Bouton favori -->
                        <form method="POST" action="{{ route('favoris.toggle') }}" class="mb-3">
                            @csrf
                            <input type="hidden" name="livre_id" value="{{ $book->id }}">
                            <button type="submit" style="background-color: #eab308; color: white; padding: 8px 16px; border-radius: 4px; border: none; cursor: pointer; font-weight: 500;">
                                @if($book->favoris->count() > 0)
                                    ⭐ Retirer des favoris
                                @else
                                    ☆ Ajouter aux favoris
                                @endif
                            </button>
                        </form>
                        
                        @can('delete', $book)
                            <form method="POST" action="{{ route('books.destroy', $book->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        style="background-color: #ef4444; color: white; padding: 6px 12px; border-radius: 4px; border: none; cursor: pointer; font-size: 14px;"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
                
                <!-- Description -->
                @if($book->description)
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-medium mb-2">Description</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $book->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Section commentaires -->
            <div class="bg-white border rounded-lg p-6 shadow-sm">
                <h3 class="text-xl font-bold mb-4">Commentaires ({{ $book->commentaires->count() }})</h3>

                <!-- Formulaire ajouter commentaire -->
                <div class="mb-6 p-4 bg-gray-50 rounded">
                    <h4 class="font-medium mb-3">💬 Ajouter un commentaire</h4>
                    <form method="POST" action="{{ route('commentaires.store') }}">
                        @csrf
                        <input type="hidden" name="livre_id" value="{{ $book->id }}">
                        <div class="mb-3">
                            <textarea name="texte" 
                                      rows="3" 
                                      placeholder="Partagez votre opinion sur ce livre..." 
                                      style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; resize: vertical;" 
                                      required>{{ old('texte') }}</textarea>
                            @error('texte')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" 
                                style="background-color: #10b981; color: white; padding: 8px 16px; border-radius: 4px; border: none; cursor: pointer;">
                            📝 Publier le commentaire
                        </button>
                    </form>
                </div>

                <!-- Liste des commentaires -->
                @if($book->commentaires->count() > 0)
                    <div class="space-y-4">
                        @foreach($book->commentaires->sortByDesc('created_at') as $commentaire)
                            <div class="border-l-4 border-green-200 bg-green-50 p-4 rounded-r-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="font-medium text-green-800">
                                            {{ $commentaire->user->name ?? 'Utilisateur #' . $commentaire->user_id }}
                                        </p>
                                        <p class="text-xs text-green-600">
                                            {{ $commentaire->created_at->format('d/m/Y à H:i') }}
                                        </p>
                                    </div>
                                    @if($commentaire->user_id == Auth::id())
                                        <form method="POST" action="{{ route('commentaires.destroy', $commentaire->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    style="background-color: #ef4444; color: white; padding: 4px 8px; border-radius: 4px; border: none; cursor: pointer; font-size: 12px;"
                                                    onclick="return confirm('Supprimer ce commentaire ?')">
                                                🗑️ Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <p class="text-green-700">"{{ $commentaire->texte }}"</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <div class="text-4xl mb-3">💭</div>
                        <p>Aucun commentaire pour le moment.</p>
                        <p class="text-sm">Soyez le premier à donner votre avis !</p>
                    </div>
                @endif
            </div>

            <!-- Statistiques du livre -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-800 mb-2">📊 Statistiques</h4>
                <div class="grid grid-cols-2 gap-4 text-sm text-blue-700">
                    <div>
                        <p><strong>Nombre de favoris:</strong> {{ $book->favoris()->count() }}</p>
                        <p><strong>Nombre de commentaires:</strong> {{ $book->commentaires->count() }}</p>
                    </div>
                    <div>
                        <p><strong>Ajouté le:</strong> {{ $book->created_at->format('d/m/Y') }}</p>
                        @if($book->updated_at != $book->created_at)
                            <p><strong>Modifié le:</strong> {{ $book->updated_at->format('d/m/Y') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>