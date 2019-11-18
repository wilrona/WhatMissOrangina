<?php

use TypeRocket\Elements\Fields\Date;

$post_type = tr_post_type('Participant', 'Participants');

$post_type->setId('participant');
$post_type->setIcon('user-tie');
$post_type->setArgument('supports', ['title'] );
$post_type->removeArgument('revisions');
$post_type->setTitlePlaceholder('Nom complet ou Ticket de télephone');
$post_type->setAdminOnly();
$post_type->removeColumn('date');

$post_type->addColumn('telephone', false, 'Télephone',  null, 'number');
$post_type->addColumn('last_activity', false, 'Dern. Activité',  function ($value){

    if($value):
    $time = \DateTime::createFromFormat('Y-m-d H:i:s', $value);

    $newformat = $time->format('d-m-Y H:i:s');

    echo $newformat;

    else:
        echo '';
    endif;

}, 'number');
$post_type->addColumn('date_save', false, 'Date Enr.', function($value){

    if($value):
    $time = \DateTime::createFromFormat('Y-m-d H:i:s', $value);

    $newformat = $time->format('d-m-Y');

    echo $newformat;

    else:

        echo '';
    endif;
});

$box1 = tr_meta_box('info_participant')->setLabel('Information participant');
$box1->setCallback(function (){
    $form = tr_form();

    echo $form->text('nom')->setLabel('Nom du participant');
    echo $form->text('prenom')->setLabel('Prénom du participant');
    echo $form->text('telephone')->setLabel('Téléphone du participant <span class="uk-text-danger">*</span>');
    echo $form->text('last_activity')->setLabel('Derniére Activité')->setAttributes(['disabled' => true]);

    echo $form->select('active_vote')->setLabel('Suspendu de vote ?')->setOptions([

        'Non' => 0,
        'Oui' => 1

    ]);

    echo $form->hidden('post_status_old')->setAttribute('value', tr_posts_field('post_status'));
    echo $form->hidden('post_title_old')->setAttribute('value', tr_posts_field('post_title'));
});

$box1->apply($post_type);

add_filter( 'bulk_actions-edit-participant', 'participant_bulk_actions' );
function participant_bulk_actions( $actions ){
    unset( $actions[ 'trash' ] );
    return $actions;
}


//add_action('wp_trash_post', 'prevent_participant_deletion');
function prevent_participant_deletion($postid){
    $post = get_post($postid);
    if ($post->post_type == 'participant') {
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

add_filter('post_row_actions','participant_action_row', 10, 2);

function participant_action_row($actions, $post){
    //check for your post type
    if ($post->post_type =="participant"){
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


//add_action('admin_notices', 'participant_admin_notice');

function participant_admin_notice(){
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


//add_filter('views_edit-participant','export_participant_filter');

function export_participant_filter($views){
    $url = tr_redirect()->toHome('/inscrit/export/?s='.$_GET['s'].'&slug='.$_GET['slug'].'&slug-year='.$_GET['slug-year'])->url;
    $views['import'] = '<a href="'.$url.'" class="primary" target="_blank">Exporter le tableau</a>';
    return $views;
}
