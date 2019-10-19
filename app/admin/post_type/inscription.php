<?php

$post_type = tr_post_type('Inscrit', 'Inscrits');

$post_type->setIcon('users');
$post_type->setArgument('supports', ['title', 'thumbnail'] );
$post_type->removeArgument('revisions');
$post_type->setTitlePlaceholder('Nom et prenom');
$post_type->setAdminOnly();

$post_type->removeColumn('date');

//$post_type->addColumn('title', true, 'Nom du candidat', null, 'string');
$post_type->addColumn('codeins', true, 'Code candidat', null, 'string');
$post_type->addColumn('datenais', true, 'Age', function ($value){


//    //date in mm/dd/yyyy format; or it can be in other formats as well
//    $birthDate = "12/17/1983";
//    //explode the date to get month, day and year
//    $birthDate = explode("-", $value);
//    //get age from date or birthdate
//    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
//        ? ((date("Y") - $birthDate[2]) - 1)
//        : (date("Y") - $birthDate[2]));

    list($jour, $mois, $annee) = preg_split('[/]', $value);
    $today['mois'] = date('n');
    $today['jour'] = date('j');
    $today['annee'] = date('Y');
    $annees = $today['annee'] - $annee;
//    if ($today['mois'] <= $mois) {
//        if ($mois == $today['mois']) {
//            if ($jour > $today['jour'])
//                $annees--;
//        }
//        else
//            $annees--;
//    }

    echo $annees . ' ans';
//    echo $value;
}, 'string');
//
$post_type->addColumn('position', true, 'Ville',  null, 'string');
////$post_type->addColumn('email', true, 'Email');
////$post_type->addColumn('phone', true, 'Telephone');
$post_type->addColumn('year_participe', false, 'Annee part.',  null, 'number');


$box1 = tr_meta_box('Presentation')->setLabel('Information personnelle');
$box1->setCallback(function (){
    $form = tr_form();

    if(get_the_ID()){
        echo $form->text('codeins')->setLabel('Code du candidat')->setAttribute('disabled', true);
    }

    echo $form->text('nom')->setLabel('Nom du candidat <span class="uk-text-danger">*</span>');
    echo $form->text('prenom')->setLabel('Prénom du candidat <span class="uk-text-danger">*</span>');
    echo $form->date('dateNais')->setLabel('Date de naissance <span class="uk-text-danger">*</span>')->setHelp('Le candidat doit avoir plus de 18 ans');
    echo $form->text('lieu')->setLabel('Lieu de naissance');
    echo $form->text('nationalite')->setLabel('Nationnalite');
    echo $form->editor('post_content')->setLabel('Description du candidat');

    echo $form->hidden('post_status_old')->setAttribute('value', tr_posts_field('post_status'));
    echo $form->hidden('post_title_old')->setAttribute('value', tr_posts_field('post_title'));
});

$box1->apply($post_type);

$box2 = tr_meta_box('adresse')->setLabel('Localisation du candidat');
$box2->setCallback(function (){
    $form = tr_form();

    $email = $form->text('email')->setLabel('Adresse Email <span class="uk-text-danger">*</span>');
    if(get_the_ID()){
        echo $email->setAttribute('disabled', true);
        echo $form->hidden('email');
    }else{
        echo $email->setHelp('l\'adresse email restera ne sera plus modifiable après enregistrement. Pensez à enregistrer une adresse email correct.');
    }

    echo $form->text('phone')->setLabel('Téléphone portable');

    $option_ville = tr_options_field('options.insc_ville') ? tr_options_field('options.insc_ville') : [];
    $villas = [
        'Selection de la ville' => ''
    ];
    foreach ($option_ville as $ville):
        if($ville['active']):
            $villas[$ville['ville']] = strtoupper($ville['ville']);
        endif;
    endforeach;

    echo $form->select('position')->setOptions($villas)->setLabel('Selectionner une ville <span class="uk-text-danger">*</span>');

    echo $form->editor('adresse')->setLabel('Adresse personnelle');
});

$box2->apply($post_type);


$box3 = tr_meta_box('professonnel')->setLabel('Niveau professionnel');
$box3->setPriority('low');
$box3->setContext('side');
$box3->setCallback(function(){
    $form = tr_form();

    echo $form->textarea('profession')->setLabel('Profession ou diplome en cours')->setAttribute('disabled', true);
    echo $form->textarea('diplome')->setLabel('Dernier diplome obtenue')->setAttribute('disabled', true);
    echo $form->textarea('compte')->setLabel('Quel est votre compte facebook ou twitter ?')->setAttribute('disabled', true);
    echo $form->textarea('participe')->setLabel('Avez-vous déjà participé à un concours de beauté ? Si oui, à quelle occasion')->setAttribute('disabled', true);
});
$box3->apply($post_type);


$box4 = tr_meta_box('personnel')->setLabel('Autres Informations');
$box4->setCallback(function(){
    $form = tr_form();

    echo $form->textarea('signe')->setLabel('Signe distinctif ?')->setAttribute('disabled', true);

    echo $form->text('enfant')->setLabel('Combien d’enfant (s) avez-vous ?')->setAttribute('disabled', true);

    echo $form->text('taille')->setLabel('Taille sans talons (en cm)')->setAttribute('disabled', true);

    echo $form->text('casier')->setLabel('Avez vous un casier judiciaire ?')->setAttribute('disabled', true);
});
$box4->apply($post_type);

$box5 = tr_meta_box('participation')->setLabel('Participation');
$box5->setPriority('high');
$box5->setContext('side');
$box5->setCallback(function(){
    $form = tr_form();

    echo $form->text('year_participe')->setLabel('Annee')->setAttributes(array('disabled' => true, 'value' => tr_options_field('options.ins_year')));
    echo $form->hidden('year_participe')->setAttributes(array('value' => tr_options_field('options.ins_year')));
});
$box5->apply($post_type);


add_filter( 'bulk_actions-edit-inscrit', 'inscrit_bulk_actions' );
function inscrit_bulk_actions( $actions ){
    unset( $actions[ 'trash' ] );
    return $actions;
}


add_action('wp_trash_post', 'prevent_inscrit_deletion');
function prevent_inscrit_deletion($postid){
    $post = get_post($postid);
    if ($post->post_type == 'inscrit') {
        wp_die('Cette information ne peut être supprimée. <br><br> <a href="'.tr_redirect()->back()->url.'">Retour</a>');
    }
}

function inscrit_action_row($actions, $post){
    //check for your post type
    if ($post->post_type =="inscrit"){
        unset( $actions[ 'trash' ] );
        $actions['print'] = '<a href="'.tr_redirect()->toHome('/inscrit/formulaire/'.tr_posts_field('codeins', $post->ID))->url.'" target="_blank">Imprimer</a>';
    }

    return $actions;
}

add_filter('post_row_actions','inscrit_action_row', 10, 2);

function inscrit_admin_notice(){
    global $post_type;
    global $pagenow;

    if ( $post_type == 'inscrit' && $pagenow !== 'post.php' ) {

         $inscrits= tr_query()->table('wp_miss_inscrit')->findAll()->get();

         if($inscrits){

             echo '<div class="notice notice-warning" data-class="is-dismissible">
                 <p>Vous avez des données des candidates encore présentes dans la version ancienne du Site Miss Orangina. <br> Cliquez sur <a class="uk-text-success" href="'.tr_redirect()->toHome('/inscrit/import')->url.'">Importer les données</a> pour importer.</p>
            </div>';

         }

    }
}
add_action('admin_notices', 'inscrit_admin_notice');


add_filter('views_edit-inscrit','export_inscrit_filter');

function export_inscrit_filter($views){
    $url = tr_redirect()->toHome('/inscrit/export/?s='.$_GET['s'].'&slug='.$_GET['slug'].'&slug-year='.$_GET['slug-year'])->url;
    $views['import'] = '<a href="'.$url.'" class="primary" target="_blank">Exporter le tableau</a>';
    return $views;
}
