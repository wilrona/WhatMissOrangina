<?php
namespace App\Models;

use TypeRocket\Models\Model;

class Parrain extends Model
{
    protected $resource = 'miss_parrain';

    protected $fillable = [
        'email',
        'idcandidat',
        'parrain'
    ];

}
