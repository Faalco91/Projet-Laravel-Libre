<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation avec les commentaires
     */
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    /**
     * Relation avec les favoris
     */
    public function favoris()
    {
        return $this->hasMany(Favori::class);
    }

    /**
     * Relation avec les livres ajoutés
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Relation avec les statuts de lecture
     */
    public function readingStatuses()
    {
        return $this->hasMany(ReadingStatus::class);
    }

    /**
     * Relation many-to-many avec les livres favoris
     */
    public function livresFavoris()
    {
        return $this->belongsToMany(Book::class, 'favoris', 'user_id', 'livre_id');
    }

    /**
     * Obtenir les livres par statut de lecture
     */
    public function getBooksByReadingStatus($status)
    {
        return $this->readingStatuses()
                   ->where('status', $status)
                   ->with('book')
                   ->ordered()
                   ->get();
    }

    /**
     * Statistiques de lecture
     */
    public function getReadingStats()
    {
        return [
            'total' => $this->readingStatuses()->count(),
            'a_lire' => $this->readingStatuses()->byStatus('a_lire')->count(),
            'en_cours' => $this->readingStatuses()->byStatus('en_cours')->count(),
            'termine' => $this->readingStatuses()->byStatus('termine')->count(),
            'abandonne' => $this->readingStatuses()->byStatus('abandonne')->count(),
        ];
    }
}