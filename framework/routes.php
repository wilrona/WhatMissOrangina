<?php
/*
|--------------------------------------------------------------------------
| TypeRocket Routes
|--------------------------------------------------------------------------
|
| Manage your web routes here.
|
*/

tr_route()->get()->match('/facebook/connect')->do('js_login_callback@Facebook');
tr_route()->get()->match('/facebook/vote/([^\/]+)/([^\/]+)/', ['idcandidat', 'idselection'])->do('vote_callback@Facebook');

tr_route()->post()->match('/inscrire')->do('inscription@Inscrit');
tr_route()->post()->match('/parrainage')->do('parrain@Inscrit');

tr_route()->get()->match('/vote/([^\/]+)/([^\/]+)/', ['idcandidat', 'idselection'])->do('vote@Inscrit');
tr_route()->get()->match('/inscrit/formulaire/([^\/]+)', ['codeins'])->do('fiche@Inscrit');
tr_route()->get()->match('/inscrit/resend/([^\/]+)', ['codeins'])->do('resend@Inscrit');
tr_route()->get()->match('/inscrit/import')->do('importer@Inscrit');
tr_route()->get()->match('/inscrit/export')->do('exporter@Inscrit');
