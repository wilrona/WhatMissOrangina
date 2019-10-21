<?php /* Template Name: Page de parrainage */ ?>



<?php

if(isset($_GET['idfacebook']) && !empty($_GET['idfacebook'])){

    $args = [
        'post_type' => 'inscrit',
        'meta_query' => array(
            array(
                'key' => 'idfacebook',
                'value' => $_GET['idfacebook'],
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

    $id = $_GET['idfacebook'];

    if(!$candidat){
        flash('error-data-inscription', 'Veuillez vous inscrire au préalable.', 'uk-text-warning');
        return tr_redirect()->toUrl(get_post_permalink(tr_options_field('options.page_form')));
    }

}
else{
    flash('error-data-inscription', 'Veuillez vous inscrire au préalable.', 'uk-text-warning');
    return tr_redirect()->toUrl(get_post_permalink(tr_options_field('options.page_form')));
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

            <h2 class="uk-text-center">Maximise tes changes de gagner, <br/> fais toi soutenir par tes ami(e)s</h2>

            <?php

            $form = tr_form('inscrit', 'parrain');

            $form->useUrl('post', '/parrainage');

            echo $form->open();

            $form->useOld();

            ?>

            <div class="uk-form-horizontal uk-margin">

                <?php

                  echo $repeater = $form->repeater('Parrain')->setFields([
                        $form->text('email')->setType('email')->setLabel('Adresse email'),
                    ])->setLabel('Ajouter des ami(e)s pour te soutenir :');

                ?>

                <div class="uk-margin uk-text-center">
                    <?php if($candidat): ?>
                        <input type="hidden" name="tr[ID]" value="<?= $candidat[0]->ID; ?>"/>
                    <?php endif; ?>
                    <input type="hidden" name="tr[idfacebook]" value="<?= $id; ?>"/>
                    <button type="submit" class="uk-button uk-button-primary uk-button-large" id="submit">Terminer votre inscription</button>
                </div>


            </div>

            <?php

            echo $form->close();


            ?>


        </div>
    </div>

    <script>
        jQuery(document).ready(function() {
            jQuery('.add').addClass('uk-button');
            jQuery('.add').addClass('uk-button-primary');
            jQuery('.add').text('Ajouter un parrain');
        })
    </script>

<?php get_footer(); ?>