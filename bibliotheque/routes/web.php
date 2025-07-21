<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/favoris', [FavoriController::class, 'index'])->name('favoris.index');
Route::post('/favoris', [FavoriController::class, 'store'])->name('favoris.store');
Route::delete('/favoris/{livre_id}', [FavoriController::class, 'destroy'])->name('favoris.destroy');
Route::post('/favoris/toggle', [FavoriController::class, 'toggle'])->name('favoris.toggle');


Route::get('/test-route', function() {
    return 'Test OK';
});

require __DIR__.'/auth.php';
