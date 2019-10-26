<?php

namespace App\Http\Middleware;

use \TypeRocket\Http\Middleware\Middleware;

class Whatsapp extends Middleware
{

    public function handle()
    {
        $request = $this->request;
        $response = $this->response;

//	    $currentUser = wp_get_current_user();
//
//        if($currentUser->ID == 0){
//
//	        $this->response->setError( 'auth', false );
//            $this->response->flashNow( "Vous n'avez pas les droits d'accès à cette page. <a href='".get_the_permalink(tr_options_field('pc_options.lien_page_candidat'))."'>Connectez-vous</a>.", 'error' );
//	        $this->response->exitAny(401);
//        }

        // Do stuff before controller is called

        $this->next->handle();

        // Do stuff after controller is called
    }
}