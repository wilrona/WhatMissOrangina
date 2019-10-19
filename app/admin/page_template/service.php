<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 04/05/2018
 * Time: 21:12
 */


$boxPos = tr_meta_box('Liste des services');
$boxPos->addScreen('page'); // updated
$boxPos->setCallback(function () {
    $form = tr_form();

    $repeater = $form->repeater('boxservices')->setFields([
        $form->text('title_section')->setLabel('Section du servie')->setHelp('Exemple : B1 ou A1'),
        $form->text('titre_service')->setLabel('Titre du service'),
        $form->editor('descservice')->setLabel('Description du service')
    ])->setLabel('Ajouter les services');

    echo $repeater;
});

$boxPro = tr_meta_box('Comment proceder');
$boxPro->addScreen('page'); // updated
$boxPro->setCallback(function () {
    $form = tr_form();
    echo $form->text('title_procedure')->setLabel('Titre')->setHelp('Titre pour le bouton qui affichera le texte');
    echo $form->editor('text_procedure')->setLabel('Description de la procedure');
});

$boxPay = tr_meta_box('Comment payer');
$boxPay->addScreen('page'); // updated
$boxPay->setCallback(function () {
    $form = tr_form();
    echo $form->text('title_payer')->setLabel('Titre')->setHelp('Titre pour le bouton qui affichera le texte');
    echo $form->editor('text_payer')->setLabel('Description du paiement');
});

$boxVille = tr_meta_box('Ville');
$boxVille->addScreen('page'); // updated
$boxVille->setCallback(function () {
    $form = tr_form();
    echo $form->text('title_ville')->setLabel('Titre')->setHelp('Titre pour le bouton qui affichera le texte');
    echo $form->text('text_ville')->setLabel('Ville')->setHelp('Separer par une virgule');
});


add_action('admin_head', function () use ($boxPos, $boxPro, $boxPay, $boxVille) {
    if (get_page_template_slug(get_the_ID()) !== 'service.php') :
        remove_meta_box($boxPos->getId(), 'page', 'normal');
        remove_meta_box($boxPro->getId(), 'page', 'normal');
        remove_meta_box($boxPay->getId(), 'page', 'normal');
        remove_meta_box($boxVille->getId(), 'page', 'normal');
    endif;
});
