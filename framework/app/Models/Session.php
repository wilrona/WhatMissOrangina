<?php
namespace App\Models;

use TypeRocket\Models\Model;

class Session extends Model
{
    protected $resource = 'miss_session';

    protected $fillable = [
        'idparticipant',
        'etape_list',
        'status'
    ];

}
