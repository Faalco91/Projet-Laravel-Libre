<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Modifier le livre</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4">
            <!-- Navigation -->
            <div class="mb-6 flex gap-2">
                <a href="{{ route('books.index') }}" style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    ← Retour à la liste
                </a>
                <a href="{{ route('books.show', $book->id) }}" style="background-color: #10b981; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block;">
                    👁️ Voir le livre
                </a>
            </div>

            <!-- Formulaire -->
            <div class="bg-white border rounded-lg p-6 shadow-sm">
                <h3 class="text-lg font-medium mb-6">Modifier "{{ $book->titre }}"</h3>
                
                <form method="POST" action="{{ route('books.update', $book->id) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Titre -->
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre du livre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="titre" 
                               name="titre" 
                               value="{{ old('titre', $book->titre) }}" 
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
                               value="{{ old('auteur', $book->auteur) }}" 
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
                            <option value="Roman" {{ old('categorie', $book->categorie) == 'Roman' ? 'selected' : '' }}>Roman</option>
                            <option value="Science-fiction" {{ old('categorie', $book->categorie) == 'Science-fiction' ? 'selected' : '' }}>Science-fiction</option>
                            <option value="Fantasy" {{ old('categorie', $book->categorie) == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                            <option value="Classique" {{ old('categorie', $book->categorie) == 'Classique' ? 'selected' : '' }}>Classique</option>
                            <option value="Policier" {{ old('categorie', $book->categorie) == 'Policier' ? 'selected' : '' }}>Policier</option>
                            <option value="Biographie" {{ old('categorie', $book->categorie) == 'Biographie' ? 'selected' : '' }}>Biographie</option>
                            <option value="Histoire" {{ old('categorie', $book->categorie) == 'Histoire' ? 'selected' : '' }}>Histoire