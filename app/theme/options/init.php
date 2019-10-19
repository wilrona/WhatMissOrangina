<?php
if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

// Setup Form
$form = tr_form()->useJson()->setGroup($this->getName());
?>

<h1>Theme Options</h1>
<div class="typerocket-container">
    <?php
    echo $form->open();

    $company_infos = function () use ($form) {

        echo '<h2 class="uk-padding-remove-bottom uk-text-center">Information de l\'entreprise</h2>';
        echo $form->text('online')->setLabel('Téléphone Online');
        echo '<hr />';

        echo $form->text('lien_facebook')->setLabel('Lien de la Facebook');
        echo $form->text('lien_youtube')->setLabel('Lien de la page Youtube');
        echo $form->text('lien_instagram')->setLabel('Lien de la page Instagram');


        echo '<hr />';

        echo $form->text('form_contact')->setLabel('Formulaire de contact du site')->setHelp('Utiliser contact form 7');

        echo '<hr />';

        echo $form->image('icon')->setLabel('Icone du site')->setHelp('Visible dans le coin de l\'onglet du navigateur')->setSetting('button', 'Inserer l\'icone');
        echo $form->image('logo')->setLabel('Logo du site web')->setSetting('button', 'Inserer le logo');
        echo $form->image('footer_image')->setLabel('Image du slogan du pied de page')->setSetting('button', 'Insérer l\'image slogan');
    };

    $param_facebook = function () use ($form){

        echo '<h2 class="uk-padding-remove-bottom uk-text-center">Plugins Page Facebook</h2>';

        echo $form->text('widget_appid')->setLabel('App ID');
        echo $form->text('widget_link')->setLabel('Lien de la page');
        echo $form->text('widget_height')->setLabel('Hauteur')->setHelp('min. 70');
        echo $form->text('widget_width')->setLabel('Largeur')->setHelp('entre min. 180 et max 500');

        echo '<div class="uk-grid uk-child-width-1-2">';
        echo '<div>';
        echo $form->toggle('widget_small-height')->setLabel('')->setText('Utiliser une en-tête plus petite')->setAttribute('value', 1);

        echo '</div>';

        echo '<div>';
        echo $form->toggle('widget_adapt-container_width')->setLabel('')->setText('Adapter à la largeur du plugin container')->setAttribute('value', 1);

        echo '</div>';

        echo '<div>';
        echo $form->toggle('widget_hide-cover')->setLabel('')->setText('Masquer la photo de couverture')->setAttribute('value', 1);

        echo '</div>';

        echo '<div>';
        echo $form->toggle('widget_show-facepile')->setLabel('')->setText('Afficher les visages des ami(e)s')->setAttribute('value', 1);

        echo '</div>';
        echo '</div>';

    };

    $param_inscription = function () use ($form) {


        echo '<h2 class="uk-padding-remove-bottom uk-text-center">Configuration inscription facebook</h2>';

        echo '<div class="uk-text-center uk-text-danger">Cette configuration sera le même si vous decidez d\'activer les votes facebook sur le site.</div>';

        echo '<div class="uk-grid uk-child-width-1-2">';

        echo '<div>';
        echo $form->text('facebook_appid')
            ->setLabel('Facebook AppId');

        echo '</div>';


        echo '<div>';
        echo $form->text('facebook_version')->setLabel('Facebook AppId version')->setAttribute('value', 'v2.8');

        echo '</div>';

        echo '</div>';

        echo $form->text('facebook_appsecret')->setLabel('Facebook App Secret')->setHelp('<span class="uk-text-danger">Cette information est utile pour la gestion des votes</span>');

        echo '<hr />';

        echo '<h2 class="uk-padding-remove uk-text-center">Parametre Inscription</h2>';

        echo '<div class="uk-grid uk-child-width-1-2">';

        echo '<div>';
        echo $form->text('ins_year')
            ->setType('number')
            ->setLabel('Annee des inscriptions')
            ->setAttribute('value', intval(date("Y")));

        echo '</div>';
        echo '<div>';
        echo $form->toggle('ins_active')->setLabel('')->setText('Activer les inscriptions')->setAttribute('value', 1);

        echo '</div>';
        echo '</div>';

        echo $form->text('ins_buttonMessage')
            ->setLabel('Message sur le bouton')
            ->setHelp('Ce Message se trouvera à l\'intérieur du bouton d\'inscription');

        echo $form->file('auth_parental')
            ->setLabel('Fichier d\'autorisation parental')
            ->setHelp('ce fichier sera envoyer pendant les inscriptions aux candidats. il doit être au format PDF.')
            ->setSetting('type', 'pdf')
            ->setSetting('button', 'Inserer le fichier');

        echo $form->repeater('insc_ville')->setFields([
            $form->text('ville')->setLabel('Nom de la ville'),
            $form->text('code')->setLabel('Code de la ville'),
            $form->toggle('active')->setText('Inscription dans cette ville ?')->setLabel('')
        ])->setLabel('Liste des lieux de rencontre')->setSetting('button', 'Ajouter une ligne');

    };

    $param_vote = function () use ($form){

        echo '<h2 class="uk-padding-remove-bottom uk-text-center">Gestion des votes</h2>';

        echo '<div class="uk-grid uk-child-width-1-2">';

        echo '<div>';

        echo $form->toggle('vote_active')->setLabel('Activer les votes par facebook')->setAttribute('value', 1);

        echo '</div>';
        echo '<div class="uk-text-danger">';
        echo 'Si vous activez les votes par facebook, vous devez au préalable avoir convenablement rempli les informations necessaires de la section <b> Parametre Inscription -> Configuration inscription facebook </b>';

        echo '</div>';
        echo '</div>';

//        echo '<hr />';
//
//        echo $form->text('vote_number')
//            ->setLabel('Numero de téléphone de vote')
//            ->setHelp('<span class="uk-text-warning">Ce numero de téléphone sera affiché si le vote par facebook n\'est pas activé.</span>');

    };

    $param_link = function () use ($form){

        echo '<h2 class="uk-padding-remove-bottom uk-text-center">Liens de redirection</h2>';

        echo $form->search('page_like')->setLabel('Lien vers la page Like page facebook')->setPostType('page');
//
        echo $form->search('page_reglement')->setLabel('Lien vers la page de règlement')->setPostType('page');

        echo $form->search('page_form')->setLabel('Lien vers la page du formulaire d\'inscription')->setPostType('page');

        echo $form->search('page_candidat_exist')->setLabel('Lien vers la page de candidat existant')->setPostType('page');

        echo $form->search('page_parrain')->setLabel('Lien vers la page du formulaire de parrainage')->setPostType('page');

        echo $form->search('page_end_inscription')->setLabel('Lien vers la page de confirmation de l\'inscription')->setPostType('page');

        echo $form->search('page_vote_confirm')->setLabel('Lien vers la page de confirmation de vote')->setPostType('page');

        echo $form->search('page_vote_exist')->setLabel('Lien vers la page de vote realisée')->setPostType('page');

    };




    // Save
    $save = $form->submit('Enregistrement');

    // Layout
    tr_tabs()->setSidebar($save)
        ->addTab('Information Entreprise', $company_infos)
        ->addTab('Plugin Page Facebook', $param_facebook)
        ->addTab('Parametre inscription', $param_inscription)
        ->addTab('Gestion des votes', $param_vote)
        ->addTab('Liens de redirection', $param_link)
        ->render('box');
    echo $form->close();
    ?>

</div>