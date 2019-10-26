<?php /* Template Name: Page accueil */ ?>

<?php get_header() ?>

<?php

$args = [
    'post_type' => 'phase',
    'meta_query' => array(
        array(
            'key' => 'statut',
            'value' => 'active',
            'compare' => '='
        )
    )
];

$phase = query_posts($args);
wp_reset_query();


$phase_candidat = tr_posts_field('list_candidats', $phase[0]->ID);

$all_vote = tr_query()->table('wp_miss_vote')->select('SUM(point) as vote')->where('idphase', '=', $phase[0]->ID)->get();

$total = $all_vote[0]->vote;

function pourcentage($user_id){

    $candidats = tr_query()->table('wp_posts')
        ->select('wp_posts.*', 'SUM(wp_miss_vote.point) as vote')
        ->join('wp_miss_vote', 'wp_miss_vote.idcandidat', '=', 'wp_posts.ID')
        ->where('wp_posts.ID', 'IN', [$user_id])
        ->groupBy('wp_posts.ID')
        ->findAll()->orderBy('vote', 'DESC')->get();

    return $candidats[0]->vote * 100;
}

?>

<div class="uk-position-relative uk-background-cover" id="header" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/fond-pied.jpg');">
    <div class="uk-position-relative uk-cover-container" uk-height-viewport="offset-bottom: true">
        <div class="uk-width-1-1 uk-flex uk-flex-center uk-flex-middle uk-padding-small uk-margin-small-top">
            <img src="<?php echo get_template_directory_uri(); ?>/img/ban.png" alt="" class="uk-width-1-3">
        </div>
        <div uk-grid class="uk-padding">
            <?php foreach ($phase_candidat as $candidat): $current_candidat = get_post($candidat['candidat'])?>
            <div class="uk-width-1-3">
                <div class="uk-card uk-card-default uk-card-small">
                    <div class="uk-card-header card-candidat">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" alt="" class="uk-border-circle" style="width:40px; height: 40px;">
                            </div>
                            <div class="uk-width-expand">
                                <h3 class="uk-card-title uk-margin-remove-bottom uk-text-truncate"><?= $candidat['codevote'] ?> - <?= $current_candidat->post_title ?>
                                </h3>
                                <p class="uk-text-meta uk-margin-remove-top">
                                    <progress class="uk-progress progress" value="0" max="100" data-pourcentage="<?= round(pourcentage($candidat['candidat']) / $total, 1)  ?>"></progress>
                                </p>
                            </div>
                            <div class="uk-width-auto uk-text-center">
                                <span class="uk-text-pourcentage uk-text-bold"><span class="integers"><?= round(pourcentage($candidat['candidat']) / $total, 1) ?></span> %</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php get_template_part('partials/menu') ?>
</div>


<?php get_footer(); ?>
