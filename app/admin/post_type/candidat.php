<?php

$post_type = tr_post_type('Candidat', 'Candidats');

$post_type->setId('candidat');
$post_type->setIcon('users');
$post_type->setArgument('supports', ['title', 'thumbnail'] );
$post_type->removeArgument('revisions');
$post_type->setTitlePlaceholder('Nom et prenom');
$post_type->setAdminOnly();

$post_type->removeColumn('date');

$post_type->addColumn('year_participe', false, 'Annee part.',  null, 'number');


$box1 = tr_meta_box('info_candidat')->setLabel('Information candidat');
$box1->setCallback(function (){
    $form = tr_form();

    echo $form->text('nom')->setLabel('Nom du candidat <span class="uk-text-danger">*</span>');
    echo $form->text('prenom')->setLabel('Prénom du candidat <span class="uk-text-danger">*</span>');

    echo $form->text('year_participe')->setLabel('Année de participation')->setAttributes(['value' => (string)date('Y')]);

    echo $form->hidden('post_status_old')->setAttribute('value', tr_posts_field('post_status'));
    echo $form->hidden('post_title_old')->setAttribute('value', tr_posts_field('post_title'));
});

$box1->apply($post_type);

add_filter( 'bulk_actions-edit-candidat', 'candidat_bulk_actions' );
function candidat_bulk_actions( $actions ){
    unset( $actions[ 'trash' ] );
    return $actions;
}


//add_action('wp_trash_post', 'prevent_candidat_deletion');
function prevent_candidat_deletion($postid){
    $post = get_post($postid);
    if ($post->post_type == 'candidat') {
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

add_filter('post_row_actions','candidat_action_row', 10, 2);

function candidat_action_row($actions, $post){
    //check for your post type
    if ($post->post_type =="candidat"){
        unset( $actions[ 'trash' ] );
//        $actions['print'] = '<a href="'.tr_redirect()->toHome('/candidat/formulaire/'.tr_posts_field('codeins', $post->ID))->url.'" target="_blank">Imprimer</a>';
    }

    return $actions;
}

/**
 *
 * Afficher un message de notification
 *
 */


//add_action('admin_notices', 'candidat_admin_notice');

function candidat_admin_notice(){
    global $post_type;
    global $pagenow;

    if ( $post_type == 'candidat' && $pagenow !== 'post.php' ) {

         $candidats= tr_query()->table('wp_miss_candidat')->findAll()->get();

         if($candidats){

             echo '<div class="notice notice-warning" data-class="is-dismissible">
                 <p>Vous avez des données des candidates encore présentes dans la version ancienne du Site Miss Orangina. <br> Cliquez sur <a class="uk-text-success" href="'.tr_redirect()->toHome('/candidat/import')->url.'">Importer les données</a> pour importer.</p>
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


//add_filter('views_edit-candidat','export_candidat_filter');

function export_candidat_filter($views){
    $url = tr_redirect()->toHome('/candidat/export/?s='.$_GET['s'].'&slug='.$_GET['slug'].'&slug-year='.$_GET['slug-year'])->url;
    $views['import'] = '<a href="'.$url.'" class="primary" target="_blank">Exporter le tableau</a>';
    return $views;
}
