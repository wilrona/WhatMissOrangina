<?php
namespace App\Models;

use TypeRocket\Models\Model;

class Vote extends Model
{
    protected $resource = 'miss_vote';

    protected $fillable = [
        'idphase',
        'idetape',
        'idparticipant',
        'idserie',
        'point',
        'type_vote'
    ];

}
