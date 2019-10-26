<?php
namespace App\Models;

use TypeRocket\Models\WPPost;

class Candidat extends WPPost
{
    protected $postType = 'candidat';

    protected $fillable = [
        'nom',
        'prenom',
        'year_participe'
    ];

}
