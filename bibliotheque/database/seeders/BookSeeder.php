<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run()
    {
        $books = [
            [
                'titre' => 'Le Petit Prince',
                'auteur' => 'Antoine de Saint-Exupéry',
                'categorie' => 'Roman',
                'annee' => 1943,
                'description' => 'Un conte poétique et philosophique.',
            ],
            [
                'titre' => '1984',
                'auteur' => 'George Orwell',
                'categorie' => 'Science-fiction',
                'annee' => 1949,
                'description' => 'Un roman d\'anticipation dystopique.',
            ],
            [
                'titre' => 'Les Misérables',
                'auteur' => 'Victor Hugo',
                'categorie' => 'Classique',
                'annee' => 1862,
                'description' => 'Lutte pour la justice et la rédemption.',
            ],
            [
                'titre' => 'L’Étranger',
                'auteur' => 'Albert Camus',
                'categorie' => 'Roman',
                'annee' => 1942,
                'description' => 'Roman emblématique de l’absurde.',
            ],
            [
                'titre' => 'Harry Potter à l\'École des Sorciers',
                'auteur' => 'J.K. Rowling',
                'categorie' => 'Fantasy',
                'annee' => 1997,
                'description' => 'Début de la saga du jeune sorcier.',
            ],
            // Game of Thrones - George R. R. Martin
            [
                'titre' => 'Le Trône de fer (A Game of Thrones)',
                'auteur' => 'George R. R. Martin',
                'categorie' => 'Fantasy',
                'annee' => 1996,
                'description' => 'Premier tome de la saga “Le Trône de fer”.',
            ],
            [
                'titre' => 'Le Donjon rouge (A Clash of Kings)',
                'auteur' => 'George R. R. Martin',
                'categorie' => 'Fantasy',
                'annee' => 1998,
                'description' => 'Deuxième tome de la saga “Le Trône de fer”.',
            ],
            [
                'titre' => 'La Bataille des rois (A Storm of Swords)',
                'auteur' => 'George R. R. Martin',
                'categorie' => 'Fantasy',
                'annee' => 2000,
                'description' => 'Troisième tome de la saga “Le Trône de fer”.',
            ],
            [
                'titre' => 'L’Ombre maléfique (A Feast for Crows)',
                'auteur' => 'George R. R. Martin',
                'categorie' => 'Fantasy',
                'annee' => 2005,
                'description' => 'Quatrième tome de la saga “Le Trône de fer”.',
            ],
            [
                'titre' => 'Le Bûcher d’un roi (A Dance with Dragons)',
                'auteur' => 'George R. R. Martin',
                'categorie' => 'Fantasy',
                'annee' => 2011,
                'description' => 'Cinquième tome de la saga “Le Trône de fer”.',
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
