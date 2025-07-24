<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Mes Favoris</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6">
                <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    ← Retour au catalogue
                </a>
            </div>

            <h3 class="text-lg font-medium mb-4">Mes livres favoris ({{ $favoris->count() }})</h3>
            
            @if($favoris->count() > 0)
                @foreach($favoris as $favori)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-bold text-lg mb-2">{{ $favori->livre->titre }}</h4>
                                <p class="text-gray-600 mb-1"><strong>Auteur:</strong> {{ $favori->livre->auteur }}</p>
                                <p class="text-gray-600 mb-1"><strong>Catégorie:</strong> {{ $favori->livre->categorie }} ({{ $favori->livre->annee }})</p>
                                <p class="text-gray-700 mb-2">{{ $favori->livre->description }}</p>
                                <p class="text-xs text-gray-500 mb-3">Ajouté le {{ $favori->created_at->format('d/m/Y') }}</p>
                            </div>
                            <span class="text-yellow-500 text-xl ml-4">⭐</span>
                        </div>
                        
                        <form method="POST" action="{{ route('favoris.destroy', $favori->livre->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600"
                                    onclick="return confirm('Retirer ce livre de vos favoris ?')">
                                🗑️ Retirer des favoris
                            </button>
                        </form>
                    </div>
                @endforeach
            @else
                <div class="bg-gray-100 border rounded-lg p-8 text-center">
                    <div class="text-4xl text-gray-400 mb-4">📚</div>
                    <p class="text-gray-600 mb-4">Aucun livre en favori.</p>
                    <a href="{{ route('dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Parcourir le catalogue
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>