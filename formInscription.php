<?php /* Template Name: Page Formulaire d'inscription */ ?>

<?php

use App\Controllers\FacebookController;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use TypeRocket\Http\Request;
use TypeRocket\Http\Response;

if(isset($_SESSION) && isset($_SESSION['token_fb'])) {

    $facebook = new FacebookController(new Request(), new Response() );

    $fb = $facebook->set_facebook();
    $fb->setDefaultAccessToken($_SESSION['token_fb']);

    try {
        $response = $fb->get('/me?locale=en_US&fields=id,name,email, first_name, last_name');
        $userNode = $response->getGraphUser();

//        if ($userNode->getField('gender') == "male") {
//            return tr_view('inscription/error');
//        }

    } catch (FacebookResponseException $e) {
        session_destroy();
        return tr_redirect()->back()->now();
    } catch (FacebookSDKException $e) {
        session_destroy();
        return tr_redirect()->back()->now();
    }

    $args = [
        'post_type' => 'inscrit',
        'meta_query' => array(
                array(
                        'key' => 'idfacebook',
                        'value' => $userNode->getField('id'),
                        'compare' => '='
                ),
                array(
                        'key' => 'year_participe',
                        'value' => tr_options_field('options.ins_year'),
                        'compare' => '='
                )
        )
    ];

    $candidat = query_posts($args);

    wp_reset_query();

    $idfacebook = $userNode->getField('id');

    $email = '';
    $first_name = '';
    $last_name = '';

    if($candidat){
        $email = tr_posts_field('email', $candidat[0]->ID);
        if($email != Null || !empty($email)){
            return tr_redirect()->toUrl(get_post_permalink(tr_options_field('options.page_candidat_exist')));
        }else{
            $email = $userNode->getField('email');
        }
    }else{
        $email = $userNode->getField('email');
        $first_name = $userNode->getField('first_name');
        $last_name = $userNode->getField('last_name');
    }

}
else{
    return tr_redirect()->back()->now();
}
?>

<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

    <div class="uk-position-relative" id="header">
        <?php get_template_part('partials/menu') ?>
        <div class="uk-position-relative uk-background-norepeat uk-background-cover uk-background-center-center uk-height-small" style="background-image: url('<?= get_the_post_thumbnail_url() ?>');">
            <div class="uk-position-cover uk-flex uk-flex-middle" style="background-color: rgba(0, 0, 0, 0.4);">
                <div class="uk-width-1-1 uk-padding uk-padding-remove-vertical">
                    <h1 class="font-flavour uk-h2 uk-text-white uk-margin-remove"><?= get_the_title() ?></h1>
                </div>
            </div>
        </div>
    </div>

<?php endwhile; ?>

    <div class="uk-section typerocket-container">
        <div class="uk-container uk-container-small uk-content">

            <?php

            flash('error-data-inscription');

            $form = tr_form('inscrit', 'inscription');

            $form->useUrl('post', '/inscrire');

            echo $form->open();

            $form->setRenderSetting('raw');
            $form->useOld();

            ?>

            <div class="uk-form-horizontal uk-margin">


                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_nom" style="font-size: 1.5rem;">Nom (<span class="uk-text-danger uk-text-small">*</span>)</label>
                    <div class="uk-form-controls">
                        <?= $form->text('nom')->setAttributes(['class' => 'uk-input', 'value' => $first_name]) ?>
                    </div>
                </div>
                <hr>

                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_prenom" style="font-size: 1.5rem;">Prénom (<span class="uk-text-danger uk-text-small">*</span>)</label>
                    <div class="uk-form-controls">
                        <?= $form->text('prenom')->setAttributes(['class' => 'uk-input', 'value' => $last_name]) ?>
                    </div>
                </div>
                <hr>
                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="datepickerbirth" style="font-size: 1.5rem;">Date de naissance (<span class="uk-text-danger uk-text-small">*</span>)</label>
                    <div class="uk-form-controls">
                        <?= $form->text('datenais')->setAttributes(['class' => 'uk-input', 'id' => 'datepickerbirth']) ?>
                        <span id="info-birth" class="uk-hidden"></span>
                    </div>
                </div>
                <hr>
                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_lieu" style="font-size: 1.5rem;">Lieu de naissance</label>
                    <div class="uk-form-controls">
                        <?= $form->text('lieu')->setAttribute('class', 'uk-input') ?>
                    </div>
                </div>
                <hr>

                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_nationalite" style="font-size: 1.5rem;">Nationalité</label>
                    <div class="uk-form-controls">
                        <?= $form->text('nationalite')->setAttribute('class', 'uk-input') ?>
                    </div>
                </div>
                <hr>

                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_adresse" style="font-size: 1.5rem;">Quartier de residence</label>
                    <div class="uk-form-controls">
                        <?= $form->text('adresse')->setAttribute('class', 'uk-input') ?>
                    </div>
                </div>
                <hr>

                <?php

                    $option_ville = tr_options_field('options.insc_ville') ? tr_options_field('options.insc_ville') : [];
                    $villas = [
                        'Selection de la ville' => ''
                    ];
                    foreach ($option_ville as $ville):
                        if($ville['active']):
                            $villas[$ville['ville']] = strtoupper($ville['ville']);
                        endif;
                    endforeach;

                ?>
                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_position" style="font-size: 1.5rem;">Ville (<span class="uk-text-danger uk-text-small">*</span>)</label>
                    <div class="uk-form-controls">
                        <?= $form->select('position')->setOptions($villas)->setAttribute('class', 'uk-select'); ?>
                    </div>
                </div>
                <hr>

                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_phone" style="font-size: 1.5rem;">Tel. Portable</label>
                    <div class="uk-form-controls">
                        <?= $form->text('phone')->setAttribute('class', 'uk-input') ?>
                    </div>
                </div>
                <hr>

                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_email" style="font-size: 1.5rem;">Adresse Email (<span class="uk-text-danger uk-text-small">*</span>)</label>
                    <div class="uk-form-controls">
                        <?= $form->text('email')->setType('email')->setAttributes(['value' => $email, 'class' => 'uk-input', 'placeholder' => 'de préférence celle de votre compte facebook']) ?>

                        <span class="uk-text-small uk-text-success">L'adresse email doit être correct car vous allez recevoir les instructions de participation du concours</span>
                    </div>
                </div>
                <hr>

                <div class="uk-margin">
                    <label class="uk-form-label font-flavour" for="tr_field_profession" style="font-size: 1.5rem;">Profession ou étude</label>
                    <div class="uk-form-controls">
                        <?= $form->text('profession')->setAttribute('class', 'uk-input') ?>
                    </div>
                </div>
                <hr>

                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_diplome" style="font-size: 1.5rem;">Dernier diplome</label>
                    <div class="uk-form-controls">
                        <?= $form->text('diplome')->setAttribute('class', 'uk-input') ?>
                    </div>
                </div>
                <hr>

                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_compte" style="font-size: 1.5rem;">Lien facebook/twitter (<span class="uk-text-danger uk-text-small">*</span>)</label>
                    <div class="uk-form-controls">
                        <?= $form->textarea('compte')->setAttribute('class', 'uk-textarea') ?>
                        <span class="uk-text-small uk-text-success">Separé les liens par des virgules si vous en avez plusieurs</span>
                    </div>
                </div>
                <hr>

                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_signe" style="font-size: 1.5rem;">Signe distinctif ?</label>
                    <div class="uk-form-controls">
                        <?= $form->text('signe')->setAttribute('class', 'uk-input') ?>
                    </div>
                </div>
                <hr>
                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_participe" style="font-size: 1.5rem;">Avez-vous déjà participé à un concours de beauté ? Si oui, à quelle occasion</label>
                    <div class="uk-form-controls">
                        <?= $form->textarea('participe')->setAttribute('class', 'uk-textarea') ?>
                        <br>
                        <br>
                        <br>
                    </div>
                </div>
                <hr>

                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_taille" style="font-size: 1.5rem;">Taille sans talons (<span class="uk-text-danger uk-text-small">*</span>)</label>
                    <div class="uk-form-controls">
                        <?= $form->text('taille')->setAttribute('class', 'uk-input') ?>
                        <span class="uk-text-small uk-text-success">Mesure en cm</span>
                    </div>
                </div>
                <hr>

                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_casier" style="font-size: 1.5rem;">Avez-vous un casier judiciaire ?</label>
                    <div class="uk-form-controls">
                        <?php
                            $value = [
                                    'OUI' => 'OUI',
                                    'NON' => 'NON'
                            ]
                        ?>
                        <?= $form->select('casier')->setOptions($value)->setAttribute('class', 'uk-select'); ?>
                        <br>
                        <br>
                    </div>
                </div>
                <hr>

                <div class="uk-margin">
                    <label class="uk-form-label font-flavour uk-h3" for="tr_field_enfant" style="font-size: 1.5rem;">Combien d’enfant (s) avez-vous ?</label>
                    <div class="uk-form-controls">
                        <?= $form->text('enfant')->setAttributes(['class' => 'uk-input', 'value' => '0']) ?>
                        <br>
                        <br>
                    </div>
                </div>
                <hr>

                <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid uk-margin-xlarge-left">
                    <label><input class="uk-checkbox" type="checkbox" id="regle"> Je confirme que j'ai bien lu  <a href="<?= get_post_permalink(tr_options_field('options.page_reglement')) ?>" target="_blank" id="col" class="link">le règlement du concours miss orangina <?= date('Y') ?> </a> et je l'accepte en toute bonne conscience</label>
                </div>
                <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid uk-margin-xlarge-left">
                    <label><input class="uk-checkbox" type="checkbox" id="deont">Je reconnais aussi avoir fourni les informations exactes me concernant</label>
                </div>
                <div class="uk-alert uk-alert-danger uk-hidden">
                    <h4>Certaines informations n'ont pas été renseignées</h4>
                </div>

                <div class="uk-margin uk-text-center">
                    <?php if($candidat): ?>
                        <input type="hidden" name="tr[ID]" value="<?= $candidat[0]->ID; ?>"/>
                    <?php endif; ?>
                    <input type="hidden" name="tr[idfacebook]" value="<?= $idfacebook; ?>"/>
                    <button type="submit" class="uk-button uk-button-primary uk-button-large uk-disabled" id="submit">Poursuivre votre inscription</button>
                </div>

            </div>

            <?php

            echo $form->close();


            ?>


        </div>
    </div>


    <script src="<?php echo get_template_directory_uri(); ?>/js/inputmask.js" type="text/javascript"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.inputmask.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.inputmask.bundle.min.js"></script>


    <script>

        jQuery(document).ready(function() {

            jQuery('#datepickerbirth').on('change', function(){
                MyAge();
            }).inputmask("date", { placeholder:"__/__/____"});

            jQuery("#deont, #regle").on('click', function(){
                if(jQuery("#deont").is(':checked') && jQuery("#regle").is(':checked') ){
                    var $vide = false;
//                $('input[type="text"], textarea').each(function(){
//                    if($.trim($(this).val()) == ''){
//                       $vide = true;
//                    }
//                });

                    if($vide === false){
                        jQuery("#submit").removeClass('uk-disabled');
                        jQuery('.uk-alert').addClass('uk-hidden');
                        MyAge();
                    }else{
                        jQuery('.uk-alert').removeClass('uk-hidden');
                        jQuery("#submit").addClass('disabled');
                    }

                }else{
                    jQuery("#submit").addClass('uk-disabled');
                    jQuery('.uk-alert').addClass('uk-hidden');
                }
            });

            if(jQuery("#deont").is(':checked') && jQuery("#regle").is(':checked') ){
                var $vide = false;
//                $('input[type="text"], textarea').each(function(){
//                    if($.trim($(this).val()) == ''){
//                       $vide = true;
//                    jQuery
//                });

                if($vide === false){
                    jQuery("#submit").removeClass('uk-disabled');
                    jQuery('.uk-alert').addClass('uk-hidden');
                    MyAge();
                }else{
                    jQuery('.uk-alert').removeClass('uk-hidden');
                    jQuery("#submit").addClass('uk-disabled');
                }

            }else{
                jQuery("#submit").addClass('uk-disabled');
                jQuery('.uk-alert').addClass('uk-hidden');
            }

//
//        $('input[type="text"], textarea').on('change', function(){
//            if($("#deont").is(':checked') && $("#regle").is(':checked') ){
//                var $vide = false;
//                $('input[type="text"], textarea').each(function(){
//                    if($.trim($(this).val()) == ''){
//                        $vide = true;
//                    }
//                });
//
//                if($vide == false){
//                    $("#submit").removeClass('disabled');
//
//                    $('.alert').addClass('hidden');
//                    MyAge();
//                }else{
//                    $('.alert').removeClass('hidden');
//                    $("#submit").addClass('disabled');
//                }
//
//            }else{
//                $("#submit").addClass('disabled');
//                $('.alert').addClass('hidden');
//            }
//        });



        });

        function MyAge(){
            // on calcul l'âge
            var maintenant = new Date();
            var dateNais = jQuery('#datepickerbirth').val();
            var data = dateNais.split('/');
            var maDateNaissance = new Date(data[2],data[1]-1,data[0]);
            var monAge = maintenant.getFullYear() - maDateNaissance.getFullYear();

            if (maDateNaissance.getMonth()>maintenant.getMonth()) {
                monAge+=1;
            } else if (maintenant.getMonth()==maDateNaissance.getMonth() && maDateNaissance.getDate()>=maintenant.getDate()) {
                monAge+=1;
            }

            if(monAge >= 18 && monAge <= 25){
                jQuery('#info-birth').removeClass('uk-hidden');
                jQuery('#info-birth').addClass('uk-text-success');
                jQuery('#info-birth').removeClass('uk-text-danger');
                jQuery('#info-birth').text('Vous êtes éligible.');

            }else{
                jQuery('#info-birth').removeClass('uk-hidden');
                jQuery('#info-birth').addClass('uk-text-danger');
                jQuery('#info-birth').removeClass('uk-text-success');
                jQuery('#info-birth').text('Vous n\'êtes pas éligible.');
                jQuery("#submit").addClass('disabled');
            }
        }




    </script>

<?php get_footer(); ?>