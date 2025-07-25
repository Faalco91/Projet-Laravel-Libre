<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Ajouter un nouveau livre</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4">
            <!-- Navigation -->
            <div class="mb-6">
                <a href="{{ route('books.index') }}" style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    ← Retour à la liste des livres
                </a>
            </div>

            <!-- Formulaire -->
            <div class="bg-white border rounded-lg p-6 shadow-sm">
                <h3 class="text-lg font-medium mb-6">Informations du livre</h3>
                
                <form method="POST" action="{{ route('books.store') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Titre -->
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre du livre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="titre" 
                               name="titre" 
                               value="{{ old('titre') }}" 
                               required 
                               maxlength="255"
                               style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;"
                               placeholder="Ex: Le Petit Prince">
                        @error('titre')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Auteur -->
                    <div>
                        <label for="auteur" class="block text-sm font-medium text-gray-700 mb-2">
                            Auteur <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="auteur" 
                               name="auteur" 
                               value="{{ old('auteur') }}" 
                               required 
                               maxlength="255"
                               style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;"
                               placeholder="Ex: Antoine de Saint-Exupéry">
                        @error('auteur')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catégorie -->
                    <div>
                        <label for="categorie" class="block text-sm font-medium text-gray-700 mb-2">
                            Catégorie
                        </label>
                        <select id="categorie" 
                                name="categorie" 
                                style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;">
                            <option value="">Sélectionner une catégorie</option>
                            <option value="Roman" {{ old('categorie') == 'Roman' ? 'selected' : '' }}>Roman</option>
                            <option value="Science-fiction" {{ old('categorie') == 'Science-fiction' ? 'selected' : '' }}>Science-fiction</option>
                            <option value="Fantasy" {{ old('categorie') == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                            <option value="Classique" {{ old('categorie') == 'Classique' ? 'selected' : '' }}>Classique</option>
                            <option value="Policier" {{ old('categorie') == 'Policier' ? 'selected' : '' }}>Policier</option>
                            <option value="Biographie" {{ old('categorie') == 'Biographie' ? 'selected' : '' }}>Biographie</option>
                            <option value="Histoire" {{ old('categorie') == 'Histoire' ? 'selected' : '' }}>Histoire</option>
                            <option value="Essai" {{ old('categorie') == 'Essai' ? 'selected' : '' }}>Essai</option>
                            <option value="Autre" {{ old('categorie') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('categorie')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Année -->
                    <div>
                        <label for="annee" class="block text-sm font-medium text-gray-700 mb-2">
                            Année de publication
                        </label>
                        <input type="number" 
                               id="annee" 
                               name="annee" 
                               value="{{ old('annee') }}" 
                               min="1" 
                               max="{{ date('Y') }}"
                               style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;"
                               placeholder="Ex: 1943">
                        @error('annee')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4" 
                                  maxlength="1000"
                                  style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; resize: vertical;"
                                  placeholder="Résumé ou description du livre...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Maximum 1000 caractères</p>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit" 
                                style="background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 4px; border: none; cursor: pointer; font-weight: 500;">
                            💾 Ajouter le livre
                        </button>
                        <a href="{{ route('books.index') }}" 
                           style="background-color: #6b7280; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; display: inline-block; font-weight: 500;">
                            ❌ Annuler
                        </a>
                    </div>
                </form>
            </div>

            <!-- Aide -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-800 mb-2">💡 Conseils</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Les champs marqués d'un <span class="text-red-500">*</span> sont obligatoires</li>
                    <li>• Vérifiez l'orthographe du titre et de l'auteur</li>
                    <li>• Une bonne description aide les autres utilisateurs à découvrir le livre</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>