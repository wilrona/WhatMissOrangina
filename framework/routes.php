<?php
/*
|--------------------------------------------------------------------------
| TypeRocket Routes
|--------------------------------------------------------------------------
|
| Manage your web routes here.
|
*/


//tr_route()->post()->match('/inscrire')->do('inscription@Candidat');

/**
 *  Route de serie
 */
tr_route()->get()->match('/serie/generate/([^\/]+)',['point'])->do('generer@Ticket');

tr_route()->post()->match('/vote/anonyme')->do('anonyme_vote@Vote');

tr_route()->any()->match('/vote/webhook/')->do('bot@Whatsapp');
