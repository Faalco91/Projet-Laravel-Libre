<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Book::with(['favoris' => function($query) {
            $query->where('user_id', Auth::id());
        }]);

        if ($search = $request->input('search')) {
            $query->where('titre', 'like', "%$search%")
                  ->orWhere('auteur', 'like', "%$search%")
                  ->orWhere('categorie', 'like', "%$search%");
        }

        $books = $query->latest()->get();

        return view('books.index', compact('books'));
    }

    public function create()
    {
        $this->authorize('create', Book::class);
        return view('books.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Book::class);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'categorie' => 'nullable|string|max:100',
            'annee' => 'nullable|integer|min:1|max:' . date('Y'),
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Livre ajouté avec succès !');
    }

    public function show(Book $book)
    {
        $this->authorize('view', $book);
        
        $book->load(['commentaires.user', 'favoris' => function($q) {
            $q->where('user_id', Auth::id());
        }]);
        
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        // Si le livre n'a pas de user_id (livre existant), permettre la modification
        if ($book->user_id === null) {
            return view('books.edit', compact('book'));
        }
        
        $this->authorize('update', $book);
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        // Si le livre n'a pas de user_id (livre existant), permettre la modification
        if ($book->user_id !== null) {
            $this->authorize('update', $book);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'categorie' => 'nullable|string|max:100',
            'annee' => 'nullable|integer|min:1|max:' . date('Y'),
            'description' => 'nullable|string|max:1000',
        ]);

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Livre modifié avec succès !');
    }

    public function destroy(Book $book)
    {
        // Si le livre n'a pas de user_id (livre existant), permettre la suppression
        if ($book->user_id !== null) {
            $this->authorize('delete', $book);
        }
        
        // Supprimer les favoris et commentaires associés en toute sécurité
        if (method_exists($book, 'favoris')) {
            $book->favoris()->delete();
        }
        
        if (method_exists($book, 'commentaires')) {
            $book->commentaires()->delete();
        }
        
        // Supprimer aussi les statuts de lecture associés
        if (class_exists('\App\Models\ReadingStatus')) {
            \App\Models\ReadingStatus::where('book_id', $book->id)->delete();
        }
        
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Livre supprimé avec succès !');
    }
}