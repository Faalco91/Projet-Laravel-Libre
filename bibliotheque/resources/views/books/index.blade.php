@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">📚 Liste des livres</h2>
        <a href="{{ route('books.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter un livre
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="GET" action="{{ route('books.index') }}" class="input-group mb-4">
        <input type="text" name="search" class="form-control" placeholder="🔍 Rechercher un livre..." value="{{ request('search') }}">
        <button class="btn btn-outline-primary" type="submit">Rechercher</button>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle text-center shadow">
            <thead class="table-primary">
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Catégorie</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td class="fw-semibold">{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td><span class="badge bg-info text-dark">{{ $book->category }}</span></td>
                        <td class="text-start" style="max-width: 250px;">{{ Str::limit($book->description, 100) }}</td>
                        <td>
                            @can('update', $book)
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-warning">
                                    ✏️ Modifier
                                </a>
                            @endcan
                            @can('delete', $book)
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce livre ?')">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">📭 Aucun livre trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
