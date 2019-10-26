<?php

use TypeRocket\Elements\Fields\Date;

$post_type = tr_post_type('Phase', 'Phases');

$post_type->setId('phase');
$post_type->setIcon('ticket');
$post_type->setArgument('supports', ['title'] );
$post_type->removeArgument('revisions');
$post_type->setAdminOnly();
$post_type->removeColumn('date');

$post_type->addColumn('statut', null, 'Statut', function ($value){
    echo $value === 'active' ? '<span class="uk-label uk-label-success">Active</span>' : '<span class="uk-label uk-label-danger">Desactive</span>';
});

$box1 = tr_meta_box('info_phase')->setLabel('Information de la phase');
$box1->setCallback(function (){
    $form = tr_form();

    $status = [
        'Selection du status' => '',
        'Activer' => 'active',
        'Désactiver' => 'desactive'
    ];

    echo $form->select('statut')->setOptions($status)->setLabel('Etat d\'activation <span class="uk-text-danger">*</span>');

    echo $form->hidden('post_status_old')->setAttribute('value', tr_posts_field('post_status'));
    echo $form->hidden('post_title_old')->setAttribute('value', tr_posts_field('post_title'));

});

$box1->apply($post_type);

$box2 = tr_meta_box('phase_candidat')->setLabel('Liste des candidats');
$box2->setCallback(function (){
    $form = tr_form();

    echo $form->repeater('list_candidats')->setFields([

        $form->search('candidat')->setPostType('candidat'),
        $form->text('codevote')->setLabel('Code de vote')->setHelp('Code de vote pour les votes par appels, sms ou message. Exemple : DLA_01')

    ])->setLabel('Liste des candidats de la phase <span class="uk-text-danger">*</span> <hr/>');

});

$box2->apply($post_type);


add_filter( 'bulk_actions-edit-phase', 'phase_bulk_actions' );
function phase_bulk_actions( $actions ){
    unset( $actions[ 'trash' ] );
    return $actions;
}


//add_action('wp_trash_post', 'prevent_phase_deletion');
function prevent_phase_deletion($postid){
    $post = get_post($postid);
    if ($post->post_type == 'phase') {
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

add_filter('post_row_actions','phase_action_row', 10, 2);

function phase_action_row($actions, $post){
    //check for your post type
    if ($post->post_type == "phase"){
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


//add_action('admin_notices', 'phase_admin_notice');

function phase_admin_notice(){

    global $post_type;
    global $pagenow;

    if ( $post_type === 'phase' && $pagenow !== 'post.php' ) {

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


//add_filter('views_edit-phase','genered_phase_filter');

function genered_phase_filter($views){
    $views['genered'] = '<a href="'.tr_redirect()->toHome('/serie/generate/1')->url.'" class="primary">Générer des series</a>';
    $views['genered2'] = '<a href="'.tr_redirect()->toHome('/serie/generate/2')->url.'" class="primary">Séries 2 points</a>';
    $views['genered5'] = '<a href="'.tr_redirect()->toHome('/serie/generate/5')->url.'" class="primary">Séries 5 points</a>';
    return $views;
}
