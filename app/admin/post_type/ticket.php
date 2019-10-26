<?php

use TypeRocket\Elements\Fields\Date;

$post_type = tr_post_type('Ticket', 'Tickets');

$post_type->setId('ticket');
$post_type->setIcon('ticket');
$post_type->setArgument('supports', ['title'] );
$post_type->removeArgument('revisions');
$post_type->setTitlePlaceholder('Numéro de serie');
$post_type->setAdminOnly();
$post_type->removeColumn('date');

$post_type->addColumn('used', null, 'Utilisé', null);

$post_type->addColumn('genered', null, 'Généré', null);

$post_type->addColumn('point', null, 'Valeur', null);


$box1 = tr_meta_box('info_serie')->setLabel('Information de la serie');
$box1->setCallback(function (){
    $form = tr_form();

    echo $form->text('serie')->setLabel('Numéro de serie')->setAttribute('disabled', true);
    echo $form->text('used')->setLabel('Numéro de serie utilisée ?')->setAttribute('disabled', true);
    echo $form->text('genered')->setLabel('Numéro de serie générée ?')->setAttribute('disabled', true);
    echo $form->text('point')->setLabel('Valeur du ticket')->setAttribute('disabled', true);

    echo $form->hidden('post_status_old')->setAttribute('value', tr_posts_field('post_status'));
    echo $form->hidden('post_title_old')->setAttribute('value', tr_posts_field('post_title'));

});

$box1->apply($post_type);


add_filter( 'bulk_actions-edit-ticket', 'ticket_bulk_actions' );
function ticket_bulk_actions( $actions ){
    unset( $actions[ 'trash' ] );
    return $actions;
}


add_action('wp_trash_post', 'prevent_ticket_deletion');
function prevent_ticket_deletion($postid){
    $post = get_post($postid);
    if ($post->post_type == 'ticket') {
        wp_die('Cette information ne peut être supprimée. <br><br> <a href="'.tr_redirect()->back()->url.'">Retour</a>');
    }
}

/**
 * @param $actions
 * @param $post
 * @return mixed
 *
 * Ajouter une action sur le listing
 */

add_filter('post_row_actions','ticket_action_row', 10, 2);

function ticket_action_row($actions, $post){
    //check for your post type
    if ($post->post_type == "ticket"){
        unset( $actions[ 'trash' ] );
//        $actions['print'] = '<a href="'.tr_redirect()->toHome('/inscrit/formulaire/'.tr_posts_field('codeins', $post->ID))->url.'" target="_blank">Imprimer</a>';
    }

    return $actions;
}

/**
 *
 * Afficher un message de notification
 *
 */


//add_action('admin_notices', 'ticket_admin_notice');

function ticket_admin_notice(){

    global $post_type;
    global $pagenow;

    if ( $post_type == 'participant' && $pagenow !== 'post.php' ) {

         $inscrits= tr_query()->table('wp_miss_inscrit')->findAll()->get();

         if($inscrits){

             echo '<div class="notice notice-warning" data-class="is-dismissible">
                 <p>Vous avez des données des candidates encore présentes dans la version ancienne du Site Miss Orangina. <br> Cliquez sur <a class="uk-text-success" href="'.tr_redirect()->toHome('/inscrit/import')->url.'">Importer les données</a> pour importer.</p>
            </div>';

         }

    }
}


/**
 * @param $views
 * @return mixed
 *
 * Ajouter un lien sur le tableau
 */


add_filter('views_edit-ticket','genered_ticket_filter');

function genered_ticket_filter($views){
    $views['genered'] = '<a href="'.tr_redirect()->toHome('/serie/generate/1')->url.'" class="primary">Générer des series</a>';
    $views['genered2'] = '<a href="'.tr_redirect()->toHome('/serie/generate/2')->url.'" class="primary">Séries 2 points</a>';
    $views['genered5'] = '<a href="'.tr_redirect()->toHome('/serie/generate/5')->url.'" class="primary">Séries 5 points</a>';
    return $views;
}
