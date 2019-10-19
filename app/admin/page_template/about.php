<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 01/05/2018
 * Time: 01:42
 */


$box1 = tr_meta_box('Presentation');
$box1->addScreen('page'); // updated
$box1->setCallback(function () {
    $form = tr_form();

    echo $form->image('image_presentation')->setLabel('Image de presentation');

    echo $form->editor('text_presentation')->setLabel('Texte de presentation');
});


$box2 = tr_meta_box('Prodedure');
$box2->addScreen('page'); // updated
$box2->setCallback(function () {
    $form = tr_form();

    echo $form->text('title_procedure')->setLabel('Titre');
    echo $form->text('note_bien')->setLabel('Information');

    $listing = $form->repeater('homeserviceinfo')->setFields([
        $form->text('icon')->setLabel('classe de l\'icone')->setHelp('Ajouter les elements d\'icon de fontawesome. Les informations sont sur https://fontawesome.com. Exemple : fa-home fas'),
        $form->text('icontext')->setLabel('Message')
    ])->setLabel('Liste');

    echo $listing;
});

add_action('admin_head', function () use ($box1, $box2) {
    if (get_page_template_slug(get_the_ID()) === 'about.php') :
        remove_post_type_support('page', 'editor');
    else :
        remove_meta_box($box1->getId(), 'page', 'normal');
        remove_meta_box($box2->getId(), 'page', 'normal');
    endif;
});
