<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadingStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id', 
        'status',
        'order',
        'date_debut',
        'date_fin',
        'progression',
        'notes'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date'
    ];

    const STATUS_LABELS = [
        'a_lire' => 'À lire',
        'en_cours' => 'En cours',
        'termine' => 'Terminé',
        'abandonne' => 'Abandonné'
    ];

    const STATUS_COLORS = [
        'a_lire' => 'bg-blue-100 border-blue-300',
        'en_cours' => 'bg-yellow-100 border-yellow-300',
        'termine' => 'bg-green-100 border-green-300',
        'abandonne' => 'bg-red-100 border-red-300'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le livre
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    /**
     * Obtenir la couleur du statut
     */
    public function getStatusColorAttribute()
    {
        return self::STATUS_COLORS[$this->status] ?? 'bg-gray-100 border-gray-300';
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope pour ordonner par position
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}