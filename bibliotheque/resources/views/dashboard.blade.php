@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <h1 class="display-5 fw-bold text-primary">Bienvenue {{ Auth::user()->name }} 👋</h1>
    <p class="lead mt-3">Bienvenue dans votre bibliothèque personnelle.</p>
    
    <a href="{{ route('books.index') }}" class="btn btn-outline-primary btn-lg mt-4">
        📚 Voir la liste des livres
    </a>
</div>
@endsection
