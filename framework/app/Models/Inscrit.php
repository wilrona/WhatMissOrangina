<?php
namespace App\Models;

use TypeRocket\Models\WPPost;

class Inscrit extends WPPost
{
    protected $postType = 'inscrit';

    protected $fillable = [
        'codeins',
        'nom',
        'prenom',
        'datenais',
        'lieu',
        'nationalite',
        'post_content',
        'adresse',
        'email',
        'position',
        'profession',
        'diplome',
        'compte',
        'signe',
        'enfant',
        'taille',
        'casier',
        'phone',
        'year_participe'
    ];

}
