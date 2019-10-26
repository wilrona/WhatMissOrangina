<?php
namespace App\Models;

use TypeRocket\Models\WPPost;

class Ticket extends WPPost
{
    protected $postType = 'ticket';

    protected $fillable = [
        'serie',
        'used',
        'genered',
        'point'

    ];

}
