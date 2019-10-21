<?php /* Template Name: Page Like Page */ ?>

<?php get_header() ?>

<?php

if(!isset($_SESSION) || !isset($_SESSION['token_fb'])){
    session_destroy();
    return tr_redirect()->back()->now();
}

?>

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
            <h2 class="uk-text-center"> Like la page officielle sur facebook </h2>

            <div class="concept clearfix" style="text-align: center; background: none;">

                <div class="fb-page" data-href="<?= tr_options_field('options.widget_link') ?>" data-width="400" data-height="70" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false" data-show-posts="false">
                    <div class="fb-xfbml-parse-ignore">
                        <blockquote cite="<?= tr_options_field('options.widget_link') ?>"><a href="<?= tr_options_field('options.widget_link') ?>">Miss Orangina</a>
                        </blockquote>
                    </div>
                </div>

            </div>
            <br/>
            <div class="uk-text-center uk-margin-top">
                <a href="<?= get_post_permalink(tr_options_field('options.page_form')) ?>" class="uk-button uk-button-large uk-button-sign-in uk-button-primary" >
                    Suivre
                </a>
            </div>
        </div>
    </div>

<?php endwhile; ?>

<?php get_footer(); ?>