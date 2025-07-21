<?php

namespace App\Http\Controllers;

use App\Models\Favori;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriController extends Controller
{
    /**
     * Afficher tous les favoris de l'utilisateur connecté
     */
    public function index()
    {
        $favoris = Favori::where('user_id', Auth::id())
                         ->with('livre')
                         ->get();
        
        return view('favoris.index', compact('favoris'));
    }

    /**
     * Ajouter un livre aux favoris
     */
    public function store(Request $request)
    {
        $request->validate([
            'livre_id' => 'required|exists:books,id'
        ]);

        // Vérifier si le livre n'est pas déjà en favori
        $existingFavori = Favori::where('user_id', Auth::id())
                                ->where('livre_id', $request->livre_id)
                                ->first();

        if ($existingFavori) {
            return redirect()->back()->with('error', 'Ce livre est déjà dans vos favoris.');
        }

        Favori::create([
            'user_id' => Auth::id(),
            'livre_id' => $request->livre_id
        ]);

        return redirect()->back()->with('success', 'Livre ajouté aux favoris avec succès.');
    }

    /**
     * Retirer un livre des favoris
     */
    public function destroy($livre_id)
    {
        $favori = Favori::where('user_id', Auth::id())
                        ->where('livre_id', $livre_id)
                        ->first();

        if (!$favori) {
            return redirect()->back()->with('error', 'Ce livre n\'est pas dans vos favoris.');
        }

        $favori->delete();

        return redirect()->back()->with('success', 'Livre retiré des favoris avec succès.');
    }

    /**
     * Toggle favori (ajouter/retirer)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'livre_id' => 'required|exists:books,id'
        ]);

        $favori = Favori::where('user_id', Auth::id())
                        ->where('livre_id', $request->livre_id)
                        ->first();

        if ($favori) {
            // Si existe, on le supprime
            $favori->delete();
            $message = 'Livre retiré des favoris.';
        } else {
            // Si n'existe pas, on l'ajoute
            Favori::create([
                'user_id' => Auth::id(),
                'livre_id' => $request->livre_id
            ]);
            $message = 'Livre ajouté aux favoris.';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Vérifier si un livre est en favori (pour affichage conditionnel)
     */
    public function isFavorite($livre_id)
    {
        return Favori::where('user_id', Auth::id())
                     ->where('livre_id', $livre_id)
                     ->exists();
    }
}