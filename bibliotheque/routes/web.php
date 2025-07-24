<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriController;
use App\Http\Controllers\CommentaireController;
use App\Models\Book;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $livres = Book::with(['favoris' => function($query) {
        $query->where('user_id', Auth::id());
    }])->limit(6)->get();
    
    // Statistiques
    $stats = [
        'total_livres' => Book::count(),
        'mes_favoris' => Auth::user()->favoris()->count(),
        'mes_commentaires' => Auth::user()->commentaires()->count(),
    ];
    
    return view('dashboard', compact('livres', 'stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes pour les favoris
    Route::get('/favoris', [FavoriController::class, 'index'])->name('favoris.index');
    Route::post('/favoris', [FavoriController::class, 'store'])->name('favoris.store');
    Route::delete('/favoris/{livre_id}', [FavoriController::class, 'destroy'])->name('favoris.destroy');
    Route::post('/favoris/toggle', [FavoriController::class, 'toggle'])->name('favoris.toggle');
    
    // Routes pour les commentaires
    Route::get('/mes-commentaires', [CommentaireController::class, 'index'])->name('commentaires.index');
    Route::post('/commentaires', [CommentaireController::class, 'store'])->name('commentaires.store');
    Route::delete('/commentaires/{id}', [CommentaireController::class, 'destroy'])->name('commentaires.destroy');
    
    // Route pour voir un livre avec commentaires
    Route::get('/livre/{id}', function($id) {
        $livre = Book::with(['commentaires.user', 'favoris' => function($q) {
            $q->where('user_id', Auth::id());
        }])->findOrFail($id);
        return view('livre.show', compact('livre'));
    })->name('livre.show');
});

Route::get('/test-route', function() {
    return 'Test OK';
});

require __DIR__.'/auth.php';