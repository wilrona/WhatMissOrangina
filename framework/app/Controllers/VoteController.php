<?php
namespace App\Controllers;

use App\Models\Vote;
use TypeRocket\Controllers\Controller;

class VoteController extends Controller
{
    protected $modelClass = Vote::class;

}
