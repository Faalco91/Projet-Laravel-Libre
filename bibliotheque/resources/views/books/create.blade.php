@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Ajouter un nouveau livre</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('books.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" name="titre" class="form-control" value="{{ old('titre') }}" required>
            </div>

            <div class="mb-3">
                <label for="auteur" class="form-label">Auteur</label>
                <input type="text" name="auteur" class="form-control" value="{{ old('auteur') }}" required>
            </div>

            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie</label>
                <input type="text" name="categorie" class="form-control" value="{{ old('categorie') }}">
            </div>

            <div class="mb-3">
                <label for="annee" class="form-label">Année</label>
                <input type="number" name="annee" class="form-control" value="{{ old('annee') }}" min="1000" max="{{ date('Y') + 1 }}">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="{{ route('books.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
