<?php
namespace App\Models;

use TypeRocket\Models\Model;

class Vote extends Model
{
    protected $resource = 'miss_vote';

    protected $fillable = [
        'idcandidat',
        'idfacebook',
        'year',
        'etape'
    ];

}
