<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'auteur',
        'categorie',
        'annee',
        'description',
    ];

    /**
     * Relation avec les commentaires
     */
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'livre_id');
    }

    /**
     * Relation avec les favoris
     */
    public function favoris()
    {
        return $this->hasMany(Favori::class, 'livre_id');
    }

    /**
     * Relation many-to-many avec les utilisateurs qui ont mis ce livre en favori
     */
    public function utilisateursFavoris()
    {
        return $this->belongsToMany(User::class, 'favoris', 'livre_id', 'user_id');
    }

    /**
     * Vérifier si le livre est en favori pour un utilisateur donné
     */
    public function isFavoriteFor($userId)
    {
        return $this->favoris()->where('user_id', $userId)->exists();
    }
}
