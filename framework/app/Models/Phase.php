<?php
namespace App\Models;

use TypeRocket\Models\WPPost;

class Phase extends WPPost
{
    protected $postType = 'phase';

    protected $fillable = [
        'statut'
    ];

}
