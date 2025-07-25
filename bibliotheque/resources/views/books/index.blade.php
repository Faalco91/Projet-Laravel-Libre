<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Gestion des livres</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Navigation -->
            <div class="mb-6 flex flex-wrap gap-2">
                <a href="{{ route('dashboard') }}" style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    ← Retour au tableau de bord
                </a>
                <a href="{{ route('books.create') }}" style="background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    ➕ Ajouter un livre
                </a>
                <a href="{{ route('favoris.index') }}" style="background-color: #eab308; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    ⭐ Mes Favoris
                </a>
                <a href="{{ route('commentaires.index') }}" style="background-color: #10b981; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    💬 Mes Commentaires
                </a>
            </div>

            <!-- Barre de recherche -->
            <div class="mb-6">
                <form method="GET" action="{{ route('books.index') }}" class="flex gap-2">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Rechercher par titre, auteur ou catégorie..." 
                           style="flex: 1; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
                    <button type="submit" style="background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 4px; border: none; cursor: pointer;">
                        🔍 Rechercher
                    </button>
                    @if(request('search'))
                        <a href="{{ route('books.index') }}" style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                            ✖️ Effacer
                        </a>
                    @endif
                </form>
            </div>

            <h3 class="text-lg font-medium mb-4">
                Tous les livres ({{ $books->count() }})
                @if(request('search'))
                    - Résultats pour "{{ request('search') }}"
                @endif
            </h3>
            
            @forelse($books as $book)
                <div class="bg-white border rounded-lg p-4 mb-4 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-bold text-lg mb-2">
                                <a href="{{ route('books.show', $book->id) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $book->titre }}
                                </a>
                            </h4>
                            <p class="text-gray-600 mb-1"><strong>Auteur:</strong> {{ $book->auteur }}</p>
                            <p class="text-gray-600 mb-1">
                                <strong>Catégorie:</strong> {{ $book->categorie ?? 'Non spécifiée' }}
                                @if($book->annee)
                                    ({{ $book->annee }})
                                @endif
                            </p>
                            @if($book->description)
                                <p class="text-gray-700 mb-3">{{ Str::limit($book->description, 150) }}</p>
                            @endif
                            @if($book->user_id)
                                <p class="text-xs text-gray-500">Ajouté par: Utilisateur #{{ $book->user_id }}</p>
                            @endif
                        </div>
                        
                        <div class="ml-4 space-y-2 min-w-[200px]">
                            <!-- Bouton favori -->
                            <form method="POST" action="{{ route('favoris.toggle') }}" class="inline w-full">
                                @csrf
                                <input type="hidden" name="livre_id" value="{{ $book->id }}">
                                <button type="submit" style="background-color: #eab308; color: white; padding: 6px 12px; border-radius: 4px; font-size: 14px; border: none; cursor: pointer; width: 100%; margin-bottom: 5px;">
                                    @if($book->favoris->count() > 0)
                                        ⭐ Retirer des favoris
                                    @else
                                        ☆ Ajouter aux favoris
                                    @endif
                                </button>
                            </form>
                            
                            <!-- Boutons d'action -->
                            <div class="flex gap-1">
                                <a href="{{ route('books.show', $book->id) }}" style="background-color: #6b7280; color: white; padding: 6px 8px; border-radius: 4px; font-size: 12px; text-decoration: none; flex: 1; text-align: center;">
                                    👁️ Voir
                                </a>
                                
                                @can('update', $book)
                                    <a href="{{ route('books.edit', $book->id) }}" style="background-color: #f59e0b; color: white; padding: 6px 8px; border-radius: 4px; font-size: 12px; text-decoration: none; flex: 1; text-align: center;">
                                        ✏️ Modifier
                                    </a>
                                @endcan
                                
                                @can('delete', $book)
                                    <form method="POST" action="{{ route('books.destroy', $book->id) }}" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                style="background-color: #ef4444; color: white; padding: 6px 8px; border-radius: 4px; font-size: 12px; border: none; cursor: pointer; width: 100%;"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')">
                                            🗑️ Supprimer
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-100 border rounded-lg p-8 text-center">
                    @if(request('search'))
                        <p class="text-gray-600 mb-4">Aucun livre trouvé pour "{{ request('search') }}".</p>
                        <a href="{{ route('books.index') }}" style="background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                            Voir tous les livres
                        </a>
                    @else
                        <p class="text-gray-600 mb-4">Aucun livre disponible.</p>
                        <div class="space-x-4">
                            <a href="{{ route('books.create') }}" style="background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                                Ajouter le premier livre
                            </a>
                            <p class="text-sm text-gray-400 mt-2">Ou lancez le seeder : <code>php artisan db:seed --class=BookSeeder</code></p>
                        </div>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>