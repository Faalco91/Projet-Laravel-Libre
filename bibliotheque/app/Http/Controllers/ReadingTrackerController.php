<?php

namespace App\Http\Controllers;

use App\Models\ReadingStatus;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReadingTrackerController extends Controller
{
    /**
     * Afficher la page de suivi des lectures
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Récupérer tous les statuts de lecture de l'utilisateur
        $readingStatuses = ReadingStatus::with('book')
                                      ->where('user_id', $userId)
                                      ->ordered()
                                      ->get()
                                      ->groupBy('status');

        // Initialiser les colonnes vides si nécessaire
        $columns = [
            'a_lire' => $readingStatuses->get('a_lire', collect()),
            'en_cours' => $readingStatuses->get('en_cours', collect()),
            'termine' => $readingStatuses->get('termine', collect()),
            'abandonne' => $readingStatuses->get('abandonne', collect())
        ];

        // Récupérer les livres non encore ajoutés au suivi
        $booksNotTracked = Book::whereNotIn('id', function($query) use ($userId) {
            $query->select('book_id')
                  ->from('reading_statuses')
                  ->where('user_id', $userId);
        })->get();

        return view('reading-tracker.index', compact('columns', 'booksNotTracked'));
    }

    /**
     * Ajouter un livre au suivi de lecture
     */
    public function addBook(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'status' => 'required|in:a_lire,en_cours,termine,abandonne'
        ]);

        $userId = Auth::id();
        
        // Vérifier si le livre n'est pas déjà en suivi
        $existing = ReadingStatus::where('user_id', $userId)
                                 ->where('book_id', $request->book_id)
                                 ->first();
        
        if ($existing) {
            return response()->json(['error' => 'Ce livre est déjà en suivi'], 400);
        }

        // Calculer l'ordre pour ce statut
        $maxOrder = ReadingStatus::where('user_id', $userId)
                                 ->where('status', $request->status)
                                 ->max('order') ?? 0;

        $readingStatus = ReadingStatus::create([
            'user_id' => $userId,
            'book_id' => $request->book_id,
            'status' => $request->status,
            'order' => $maxOrder + 1,
            'date_debut' => $request->status === 'en_cours' ? now() : null,
            'date_fin' => $request->status === 'termine' ? now() : null,
            'progression' => $request->status === 'termine' ? 100 : 0
        ]);

        $readingStatus->load('book');

        return response()->json([
            'success' => true,
            'reading_status' => $readingStatus
        ]);
    }

    /**
     * Mettre à jour le statut d'un livre (drag & drop)
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'reading_status_id' => 'required|exists:reading_statuses,id',
            'new_status' => 'required|in:a_lire,en_cours,termine,abandonne',
            'new_order' => 'required|integer|min:0'
        ]);

        $userId = Auth::id();
        
        $readingStatus = ReadingStatus::where('id', $request->reading_status_id)
                                    ->where('user_id', $userId)
                                    ->firstOrFail();

        $oldStatus = $readingStatus->status;
        $newStatus = $request->new_status;

        // Mettre à jour les dates automatiquement
        $updates = [
            'status' => $newStatus,
            'order' => $request->new_order
        ];

        if ($newStatus === 'en_cours' && $oldStatus !== 'en_cours') {
            $updates['date_debut'] = now();
            if ($readingStatus->progression == 0) {
                $updates['progression'] = 1;
            }
        }

        if ($newStatus === 'termine' && $oldStatus !== 'termine') {
            $updates['date_fin'] = now();
            $updates['progression'] = 100;
        }

        if ($newStatus === 'a_lire') {
            $updates['date_debut'] = null;
            $updates['date_fin'] = null;
            $updates['progression'] = 0;
        }

        if ($newStatus === 'abandonne' && $oldStatus !== 'abandonne') {
            $updates['date_fin'] = now();
        }

        $readingStatus->update($updates);

        // Réorganiser les ordres dans la nouvelle colonne
        $this->reorderColumn($userId, $newStatus);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès'
        ]);
    }

    /**
     * Mettre à jour la progression d'un livre
     */
    public function updateProgress(Request $request)
    {
        $request->validate([
            'reading_status_id' => 'required|exists:reading_statuses,id',
            'progression' => 'required|integer|min:0|max:100'
        ]);

        $userId = Auth::id();
        
        $readingStatus = ReadingStatus::where('id', $request->reading_status_id)
                                    ->where('user_id', $userId)
                                    ->firstOrFail();

        $updates = ['progression' => $request->progression];

        // Si progression = 100%, marquer comme terminé
        if ($request->progression == 100 && $readingStatus->status !== 'termine') {
            $updates['status'] = 'termine';
            $updates['date_fin'] = now();
        }

        // Si progression > 0 et statut = à lire, passer en cours
        if ($request->progression > 0 && $readingStatus->status === 'a_lire') {
            $updates['status'] = 'en_cours';
            $updates['date_debut'] = now();
        }

        $readingStatus->update($updates);

        return response()->json([
            'success' => true,
            'message' => 'Progression mise à jour'
        ]);
    }

    /**
     * Supprimer un livre du suivi
     */
    public function removeBook(Request $request)
    {
        $request->validate([
            'reading_status_id' => 'required|exists:reading_statuses,id'
        ]);

        $userId = Auth::id();
        
        $readingStatus = ReadingStatus::where('id', $request->reading_status_id)
                                    ->where('user_id', $userId)
                                    ->firstOrFail();

        $readingStatus->delete();

        return response()->json([
            'success' => true,
            'message' => 'Livre retiré du suivi'
        ]);
    }

    /**
     * Réorganiser les ordres dans une colonne
     */
    private function reorderColumn($userId, $status)
    {
        $items = ReadingStatus::where('user_id', $userId)
                             ->where('status', $status)
                             ->orderBy('order')
                             ->get();

        foreach ($items as $index => $item) {
            $item->update(['order' => $index + 1]);
        }
    }

    /**
     * API pour récupérer les statistiques
     */
    public function stats()
    {
        $userId = Auth::id();
        
        $stats = [
            'total' => ReadingStatus::where('user_id', $userId)->count(),
            'a_lire' => ReadingStatus::where('user_id', $userId)->byStatus('a_lire')->count(),
            'en_cours' => ReadingStatus::where('user_id', $userId)->byStatus('en_cours')->count(),
            'termine' => ReadingStatus::where('user_id', $userId)->byStatus('termine')->count(),
            'abandonne' => ReadingStatus::where('user_id', $userId)->byStatus('abandonne')->count(),
        ];

        return response()->json($stats);
    }
}