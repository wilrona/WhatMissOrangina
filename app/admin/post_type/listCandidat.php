<?php
$post_type = tr_post_type('Selection', 'Selections');

$post_type->setIcon('woman');
$post_type->setArgument('supports', ['title'] );
$post_type->setAdminOnly();
$post_type->setTitlePlaceholder('Nom de la liste. Exple : Regional Douala');

$box2 = tr_meta_box('Liste des candidats')->setLabel('Liste des lieux de rencontre de la phase');

$box2->addPostType( $post_type->getId() );
$box2->setCallback(function(){

    $form = tr_form();

    echo $form->text('list_title')->setLabel('Titre de la selection');

    echo $form->repeater('list_candidats')->setFields([

        $form->search('candidat')->setPostType('inscrit'),
        $form->text('codevote')->setLabel('Code de vote')->setHelp('Code de vote pour les votes par appels, sms ou message. Exemple : DLA_01')

    ])->setLabel('');

});


add_filter( 'bulk_actions-edit-selection', 'selection_bulk_actions' );
function selection_bulk_actions( $actions ){
    unset( $actions[ 'trash' ] );
    return $actions;
}


function selection_action_row($actions, $post){
    //check for your post type
    if ($post->post_type =="selection"){

        unset( $actions[ 'trash' ] );

//        $actions['trash'] = '';
    }

    return $actions;
}

add_filter('post_row_actions','selection_action_row', 10, 2);


add_action('wp_trash_post', 'prevent_selection_deletion');
function prevent_selection_deletion($postid){
    $protected_post_id = 67586;
    $post = get_post($postid);
    if ($post->post_type == 'selection') {
        wp_die('Cette information ne peut être supprimée. <br><br> <a href="'.tr_redirect()->back()->url.'">Retour</a>');
    }
}