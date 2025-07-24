<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">📚 Gestion des Livres</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Header avec bouton d'ajout -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium">Tous les livres ({{ $books->count() }})</h3>
                <button type="button"
                        onclick="document.getElementById('addBookModal').classList.remove('hidden')"
                        style="background-color: #10b981; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    ➕ Ajouter un livre
                </button>
            </div>

            <!-- Barre de recherche -->
            <form method="GET" action="{{ route('books.index') }}" class="mb-6">
                <input type="text"
                       name="search"
                       placeholder="🔍 Rechercher un livre..."
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </form>

            <!-- Liste des livres -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Auteur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Année</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($books as $book)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $book->titre }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $book->auteur }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $book->categorie ?? 'Non définie' }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $book->annee ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @can('update', $book)
                                    <button onclick="openEditModal({{ $book->id }})"
                                            style="background-color: #f59e0b; color: white; padding: 4px 8px; border-radius: 4px; border: none; cursor: pointer; font-size: 12px; margin-right: 4px;">
                                        ✏️ Modifier
                                    </button>
                                @endcan
                                @can('delete', $book)
                                    <form method="POST" action="{{ route('books.destroy', $book) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                style="background-color: #ef4444; color: white; padding: 4px 8px; border-radius: 4px; border: none; cursor: pointer; font-size: 12px;"
                                                onclick="return confirm('Supprimer ce livre ?')">
                                            🗑️ Supprimer
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                📭 Aucun livre trouvé.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Ajout -->
    <div id="addBookModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">➕ Ajouter un nouveau livre</h3>
                <form action="{{ route('books.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Titre *</label>
                        <input type="text" name="titre" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Auteur *</label>
                        <input type="text" name="auteur" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
                        <select name="categorie" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">-- Choisir --</option>
                            <option value="Roman">Roman</option>
                            <option value="Science-fiction">Science-fiction</option>
                            <option value="Fantastique">Fantastique</option>
                            <option value="Policier">Policier</option>
                            <option value="Thriller">Thriller</option>
                            <option value="Biographie">Biographie</option>
                            <option value="Histoire">Histoire</option>
                            <option value="Philosophie">Philosophie</option>
                            <option value="Poésie">Poésie</option>
                            <option value="Théâtre">Théâtre</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Année</label>
                        <input type="number" name="annee" class="w-full px-3 py-2 border border-gray-300 rounded-md" min="1000" max="{{ date('Y') + 1 }}">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button"
                                onclick="document.getElementById('addBookModal').classList.add('hidden')"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md">
                            Annuler
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modals Edition (un pour chaque livre) -->
    @foreach($books as $book)
        @can('update', $book)
            <div id="editBookModal{{ $book->id }}" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">✏️ Modifier le livre</h3>
                        <form action="{{ route('books.update', $book) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Titre *</label>
                                <input type="text" name="titre" value="{{ $book->titre }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Auteur *</label>
                                <input type="text" name="auteur" value="{{ $book->auteur }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
                                <select name="categorie" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    <option value="">-- Choisir --</option>
                                    <option value="Roman" {{ $book->categorie == 'Roman' ? 'selected' : '' }}>Roman</option>
                                    <option value="Science-fiction" {{ $book->categorie == 'Science-fiction' ? 'selected' : '' }}>Science-fiction</option>
                                    <option value="Fantastique" {{ $book->categorie == 'Fantastique' ? 'selected' : '' }}>Fantastique</option>
                                    <option value="Policier" {{ $book->categorie == 'Policier' ? 'selected' : '' }}>Policier</option>
                                    <option value="Thriller" {{ $book->categorie == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                                    <option value="Biographie" {{ $book->categorie == 'Biographie' ? 'selected' : '' }}>Biographie</option>
                                    <option value="Histoire" {{ $book->categorie == 'Histoire' ? 'selected' : '' }}>Histoire</option>
                                    <option value="Philosophie" {{ $book->categorie == 'Philosophie' ? 'selected' : '' }}>Philosophie</option>
                                    <option value="Poésie" {{ $book->categorie == 'Poésie' ? 'selected' : '' }}>Poésie</option>
                                    <option value="Théâtre" {{ $book->categorie == 'Théâtre' ? 'selected' : '' }}>Théâtre</option>
                                    <option value="Autre" {{ $book->categorie == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Année</label>
                                <input type="number" name="annee" value="{{ $book->annee }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" min="1000" max="{{ date('Y') + 1 }}">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ $book->description }}</textarea>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button"
                                        onclick="document.getElementById('editBookModal{{ $book->id }}').classList.add('hidden')"
                                        class="px-4 py-2 bg-gray-500 text-white rounded-md">
                                    Annuler
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-amber-500 text-white rounded-md">
                                    Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
    @endforeach

    <script>
        function openEditModal(id) {
            document.getElementById('editBookModal' + id).classList.remove('hidden');
        }
    </script>
</x-app-layout>
