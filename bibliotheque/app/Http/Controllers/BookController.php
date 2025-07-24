<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%$search%")
                    ->orWhere('auteur', 'like', "%$search%")
                    ->orWhere('categorie', 'like', "%$search%")
                    ->orWhere('annee', 'like', "%$search%");
            });
        }

        $books = $query->with('user')->latest()->get();

        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        // VALIDATION DES CHAMPS ICI
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'categorie' => 'nullable|string|max:100',
            'annee' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'description' => 'nullable|string|max:1000',
        ], [
            // Messages d'erreur personnalisés en français
            'titre.required' => 'Le titre est obligatoire.',
            'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'auteur.required' => 'L\'auteur est obligatoire.',
            'auteur.max' => 'L\'auteur ne peut pas dépasser 255 caractères.',
            'categorie.max' => 'La catégorie ne peut pas dépasser 100 caractères.',
            'annee.integer' => 'L\'année doit être un nombre entier.',
            'annee.min' => 'L\'année doit être supérieure à 1000.',
            'annee.max' => 'L\'année ne peut pas être dans le futur.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
        ]);

        $validated['user_id'] = Auth::id();

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Livre ajouté avec succès !');
    }

    public function edit(Book $book)
    {
        $this->authorize('update', $book);
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        // VALIDATION DES CHAMPS ICI AUSSI
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'categorie' => 'nullable|string|max:100',
            'annee' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'description' => 'nullable|string|max:1000',
        ], [
            // Messages d'erreur personnalisés en français
            'titre.required' => 'Le titre est obligatoire.',
            'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'auteur.required' => 'L\'auteur est obligatoire.',
            'auteur.max' => 'L\'auteur ne peut pas dépasser 255 caractères.',
            'categorie.max' => 'La catégorie ne peut pas dépasser 100 caractères.',
            'annee.integer' => 'L\'année doit être un nombre entier.',
            'annee.min' => 'L\'année doit être supérieure à 1000.',
            'annee.max' => 'L\'année ne peut pas être dans le futur.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
        ]);

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Livre modifié avec succès.');
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Livre supprimé avec succès.');
    }
}
