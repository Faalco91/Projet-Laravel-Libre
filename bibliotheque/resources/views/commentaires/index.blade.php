<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Mes Commentaires</h2>
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
                    ← Retour au dashboard
                </a>
            </div>

            <h3 class="text-lg font-medium mb-4">Mes commentaires ({{ $commentaires->count() }})</h3>

            @forelse($commentaires as $commentaire)
                <div class="bg-white border rounded-lg p-4 mb-4 shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-bold text-lg">
                                <a href="{{ route('livre.show', $commentaire->livre->id) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $commentaire->livre->titre }}
                                </a>
                            </h4>
                            <p class="text-gray-600 text-sm">
                                Par {{ $commentaire->livre->auteur }} • 
                                Commenté le {{ $commentaire->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('commentaires.destroy', $commentaire->id) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    style="background-color: #ef4444; color: white; padding: 6px 12px; border-radius: 4px; border: none; cursor: pointer; font-size: 14px;"
                                    onclick="return confirm('Supprimer ce commentaire ?')">
                                🗑️ Supprimer
                            </button>
                        </form>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <p class="text-gray-700">"{{ $commentaire->texte }}"</p>
                    </div>
                </div>
            @empty
                <div class="bg-gray-100 border rounded-lg p-8 text-center">
                    <div class="text-4xl text-gray-400 mb-4">💬</div>
                    <p class="text-gray-600 mb-4">Vous n'avez encore écrit aucun commentaire.</p>
                    <a href="{{ route('dashboard') }}" style="background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                        Parcourir les livres
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>