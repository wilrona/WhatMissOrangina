<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 01/05/2018
 * Time: 09:51
 */


$projet = tr_post_type('Ressource', 'Ressources');

$projet->setIcon('books');
$projet->setArgument('supports', ['title']);

$box = tr_meta_box('fichier')->setLabel('Information complementaire');

$box->addPostType($projet->getId());


$box->setCallback(function () {
    $form = tr_form();
    echo $form->text('annee')->setType('number')->setLabel('Annee de publication du texte');
    echo $form->file('fichier')->setLabel('')->setSetting('type', 'application/pdf')->setSetting('button', 'Ajouter le fichier a telecharger');
});

$rub = tr_taxonomy('Rubrique');
$rub->setHierarchical();
$rub->apply($projet);
