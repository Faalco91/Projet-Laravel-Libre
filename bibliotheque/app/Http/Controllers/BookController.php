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
            $query->where('title', 'like', "%$search%")
                  ->orWhere('author', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%");
        }

        $books = $query->latest()->get();

        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Livre ajouté !');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Livre modifié.');
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Livre supprimé.');
    }
}
