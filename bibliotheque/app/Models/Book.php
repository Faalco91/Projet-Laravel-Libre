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
        'user_id',
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
     * Relation avec les statuts de lecture
     */
    public function readingStatuses()
    {
        return $this->hasMany(ReadingStatus::class);
    }

    /**
     * Relation many-to-many avec les utilisateurs qui ont mis ce livre en favori
     */
    public function utilisateursFavoris()
    {
        return $this->belongsToMany(User::class, 'favoris', 'livre_id', 'user_id');
    }

    /**
     * Relation avec l'utilisateur qui a ajouté le livre
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifier si le livre est en favori pour un utilisateur donné
     */
    public function isFavoriteFor($userId)
    {
        return $this->favoris()->where('user_id', $userId)->exists();
    }

    /**
     * Obtenir le statut de lecture pour un utilisateur donné
     */
    public function getReadingStatusFor($userId)
    {
        return $this->readingStatuses()->where('user_id', $userId)->first();
    }
}