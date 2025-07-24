@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">✏️ Modifier le livre</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('books.update', $book) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre', $book->titre) }}" required>
            </div>

            <div class="mb-3">
                <label for="auteur" class="form-label">Auteur</label>
                <input type="text" name="auteur" id="auteur" class="form-control" value="{{ old('auteur', $book->auteur) }}" required>
            </div>

            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie</label>
                <input type="text" name="categorie" id="categorie" class="form-control" value="{{ old('categorie', $book->categorie) }}">
            </div>

            <div class="mb-3">
                <label for="annee" class="form-label">Année</label>
                <input type="number" name="annee" id="annee" class="form-control" value="{{ old('annee', $book->annee) }}" min="1000" max="{{ date('Y') + 1 }}">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', $book->description) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="{{ route('books.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
