<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    /**
     * Ajouter un commentaire à un livre
     */
    public function store(Request $request)
    {
        $request->validate([
            'livre_id' => 'required|exists:books,id',
            'texte' => 'required|string|min:5|max:500'
        ]);

        Commentaire::create([
            'user_id' => Auth::id(),
            'livre_id' => $request->livre_id,
            'texte' => $request->texte
        ]);

        return redirect()->back()->with('success', 'Commentaire ajouté avec succès.');
    }

    /**
     * Supprimer un commentaire (seulement le sien)
     */
    public function destroy($id)
    {
        $commentaire = Commentaire::where('id', $id)
                                  ->where('user_id', Auth::id())
                                  ->firstOrFail();

        $commentaire->delete();

        return redirect()->back()->with('success', 'Commentaire supprimé.');
    }

    /**
     * Afficher mes commentaires
     */
    public function index()
    {
        $commentaires = Commentaire::where('user_id', Auth::id())
                                  ->with('livre')
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        return view('commentaires.index', compact('commentaires'));
    }
}