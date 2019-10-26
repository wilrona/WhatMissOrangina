<?php
namespace App\Models;

use TypeRocket\Models\WPPost;

class Participant extends WPPost
{
    protected $postType = 'participant';

    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'last_activity',
        'date_save'

    ];

}
