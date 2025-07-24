<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Bibliothèque</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-100 p-4 rounded-lg">
                    <h4 class="font-bold text-blue-800">Total livres</h4>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total_livres'] }}</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-lg">
                    <h4 class="font-bold text-yellow-800">Mes favoris</h4>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['mes_favoris'] }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-lg">
                    <h4 class="font-bold text-green-800">Mes commentaires</h4>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['mes_commentaires'] }}</p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="mb-6 space-x-2">
                <a href="{{ route('favoris.index') }}" style="background-color: #eab308; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    ⭐ Mes Favoris
                </a>
                <a href="{{ route('commentaires.index') }}" style="background-color: #10b981; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    💬 Mes Commentaires
                </a>
            </div>

            <h3 class="text-lg font-medium mb-4">Livres disponibles</h3>
            
            @forelse($livres as $livre)
                <div class="bg-white border rounded-lg p-4 mb-4 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-bold text-lg mb-2">
                                <a href="{{ route('livre.show', $livre->id) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $livre->titre }}
                                </a>
                            </h4>
                            <p class="text-gray-600 mb-1"><strong>Auteur:</strong> {{ $livre->auteur }}</p>
                            <p class="text-gray-600 mb-1"><strong>Catégorie:</strong> {{ $livre->categorie }} ({{ $livre->annee }})</p>
                            <p class="text-gray-700 mb-3">{{ Str::limit($livre->description, 100) }}</p>
                        </div>
                        <div class="ml-4 space-y-2">
                            <form method="POST" action="{{ route('favoris.toggle') }}" class="inline">
                                @csrf
                                <input type="hidden" name="livre_id" value="{{ $livre->id }}">
                                <button type="submit" style="background-color: #eab308; color: white; padding: 6px 12px; border-radius: 4px; font-size: 14px; border: none; cursor: pointer; width: 100%; margin-bottom: 5px;">
                                    @if($livre->favoris->count() > 0)
                                        ⭐ Retirer des favoris
                                    @else
                                        ☆ Ajouter aux favoris
                                    @endif
                                </button>
                            </form>
                            <a href="{{ route('livre.show', $livre->id) }}" style="background-color: #6b7280; color: white; padding: 6px 12px; border-radius: 4px; font-size: 14px; text-decoration: none; display: block; text-align: center;">
                                👁️ Voir détails & commentaires
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-100 border rounded-lg p-8 text-center">
                    <p class="text-gray-600">Aucun livre disponible.</p>
                    <p class="text-sm text-gray-400 mt-2">Lancez le seeder : <code>php artisan db:seed --class=BookSeeder</code></p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>