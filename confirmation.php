<?php /* Template Name: Page Confirmation */

get_header() ?>

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

    <div class="uk-section">
        <div class="uk-container uk-container-small uk-content">
            <h2 class="uk-text-center uk-h1 uk-text-baseline"> Candidature enregistrée avec succès </h2>

            <div class="concept clearfix" style="text-align: center; background: none;">

                <?= get_the_content(); ?>

            </div>
            <br/>
            <div class="uk-text-center uk-margin-top">
                <a href="<?= home_url() ?>" class="uk-button uk-button-large uk-button-sign-in uk-button-primary" >
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

<?php endwhile; ?>

<?php get_footer(); ?>