<?php

//$home = (int) get_option('page_on_front');

$boxUne = tr_meta_box('Traitement du slider');
$boxUne->addScreen('page'); // updated
$boxUne->setCallback(function () {
    $form = tr_form();
    echo $form->text('slider')->setLabel('Shorcode d\'un slider');
});


$bloc_infos = tr_meta_box('Quelques reglements');
$bloc_infos->addScreen('page');
$bloc_infos->setCallback(function (){

    $form = tr_form();

    $concept1 = function() use ($form) {

        echo $form->image('img_concept1')->setLabel('Image de fond')->setSetting('button', 'Image de fond');
        echo $form->text('titre_concept1')->setLabel('Titre');
        echo $form->editor('desc_concept1')->setLabel('Contenu Texte');
    };

    $concept2 = function() use ($form){

        echo $form->text('titre_concept2')->setLabel('Titre');

        echo $form->repeater('list_concept')->setFields([
            $form->editor('text')->setLabel('Description d\'une ligne')
        ])->setLabel('')->setSetting('button', 'Ajouter une ligne');

    };

    $condition = function () use ($form){

        echo $form->text('titre_condition')->setLabel('Titre');

        echo $form->repeater('list_condition')->setFields([
            $form->editor('text')->setLabel('Description d\'une ligne')
        ])->setLabel('')->setSetting('button', 'Ajouter une ligne');
    };

    tr_tabs()
        ->addTab( 'Concept (Block Droite)', $concept1 )
        ->addTab( 'Concept (Block Gauche)', $concept2 )
        ->addTab( 'Condition de participation', $condition )
        ->render();

});

$bloc_lieux = tr_meta_box('Lieux de selection');
$bloc_lieux->addScreen('page');
$bloc_lieux->setCallback(function(){
    $form = tr_form();

    echo $form->text('titre_selection')->setLabel('Titre du block');
    echo $form->editor('infos_selection')->setLabel('Information complementaire');

    echo $form->repeater('list_lieu')->setFields([
        $form->search('search')->setPostType('lieu_de_phase')->setLabel('')
    ])->setLabel('');

});


$bloc_selection = tr_meta_box('Selection des candidats');
$bloc_selection->addScreen('page');
$bloc_selection->setCallback(function(){
    $form = tr_form();

    echo $form->text('titre_selectionc')->setLabel('Titre du block');
    echo $form->editor('infos_selectionc')->setLabel('Information complementaire');

    echo $form->repeater('list_selection')->setFields([

        $form->search('selection')->setPostType('selection'),

    ])->setLabel('');

});


add_action('admin_head', function () use ($boxUne, $bloc_infos, $bloc_lieux, $bloc_selection) {
    if (get_page_template_slug(get_the_ID()) === 'home.php') :
        remove_post_type_support('page', 'editor');
    else :
        remove_meta_box($boxUne->getId(), 'page', 'normal');
        remove_meta_box($bloc_infos->getId(), 'page', 'normal');
        remove_meta_box($bloc_lieux->getId(), 'page', 'normal');
        remove_meta_box($bloc_selection->getId(), 'page', 'normal');
    endif;
});
