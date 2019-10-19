<?php


$post_type = tr_post_type('Lieu de phase', 'Lieux de phase');

$post_type->setIcon('location');
$post_type->setArgument('supports', ['title'] );
$post_type->setTitlePlaceholder('Inserer le nom de la phase. Exple : Casting');
$post_type->setAdminOnly();

$box = tr_meta_box('Lieux')->setLabel('Liste des lieux de rencontre de la phase');

$box->addPostType( $post_type->getId() );
$box->setCallback(function(){

    $form = tr_form();

    $option_ville = tr_options_field('options.insc_ville') ? tr_options_field('options.insc_ville') : [];
    $villas = [
        'Selection de la ville' => ''
    ];
    foreach ($option_ville as $ville):
        if($ville['active']):
            $villas[$ville['ville']] = strtoupper($ville['ville']);
        endif;
    endforeach;

    echo $form->repeater('list_lieux')->setFields([

        $form->select('ville')->setOptions($villas)->setLabel('Nom de la ville'),
        $form->image('image')->setLabel('Image d\'illustration')->setHelp('L\'image peut être celui du lieu ou de la ville'),
        $form->text('lieu')->setLabel('Nom du lieu')->setHelp('Lieu de la rencontre'),
        $form->date('date')->setLabel('Date de la rencontre')->setHelp('Pour une meilleur présentation. Insérer au format jour/mois/annee. Exemple 12/12/2019'),
        $form->text('heure')->setLabel('Heure de la rencontre')->setHelp('Pour une meilleur présentation. Insérer au format Heure:Minute. Exemple 12:30')
    ])->setLabel('');

});