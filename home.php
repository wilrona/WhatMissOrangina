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

    return $candidats[0] ? $candidats[0]->vote * 100 : 0;
}

?>

<div class="uk-position-relative uk-background-cover" id="header" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/fond-pied.jpg');">
    <div class="uk-position-relative uk-cover-container" uk-height-viewport="offset-bottom: true">

        <div class="uk-width-1-1 uk-flex uk-flex-center uk-flex-middle uk-padding-small uk-margin-small-top">
            <!--            <img src="--><?php //echo get_template_directory_uri(); ?><!--/img/ban.png" alt="" class="uk-width-1-3">-->
        </div>

        <?php $option = tr_options_field('options.type_affiche') ?>

        <?php if(empty($option) || $option == 'block'): ?>
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
                                <h3 class="uk-h4 uk-margin-remove-bottom uk-text-truncate"><?= $candidat['codevote'] ?> - <?= $current_candidat->post_title ?>
                                </h3>
                                <p class="uk-text-meta uk-margin-small-top">
                                    <progress class="uk-progress progress" value="0" max="100" data-pourcentage="<?= pourcentage($candidat['candidat']) ? round(pourcentage($candidat['candidat']) / $total, 1) : pourcentage($candidat['candidat']);  ?>"></progress>
                                </p>
                            </div>
                            <div class="uk-width-auto uk-text-center">
                                <span class="uk-text-pourcentage uk-text-bold"><span class="integers"><?= pourcentage($candidat['candidat']) ? round(pourcentage($candidat['candidat']) / $total, 1) : pourcentage($candidat['candidat']);  ?></span> %</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php endif; ?>
        <?php if($option == 'bar'): ?>

            <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/js/apex/apexcharts.css">

            <div class="uk-height-large">
                <div id="chart"></div>
            </div>

            <script type="application/javascript" src="<?php echo get_template_directory_uri(); ?>/js/apex/apexcharts.js"></script>

            <script>
                var colors = ['yellow'];
                var options = {
                    chart: {
                        height: 900,
                        type: 'bar',
                    },
                    colors: colors,
                    plotOptions: {
                        bar: {
                            columnWidth: '70%',
                            distributed: true,
                            dataLabels: {
                                position: 'top', // top, center, bottom
                            },
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            return val + "%";
                        },
                        offsetY: -20,
                        style: {
                            fontSize: '12px',
                            colors: ["yellow"]
                        }
                    },
                    series: [{
                        data: [2.3, 3.1, 4.0, 10.1, 4.0, 3.6, 3.2, 2.3, 1.4, 0.8, 0.5, 0.2, 0.2, 0.2, 0.2, 0.2, 0.2, 0.2, 0.2, 0.2]
                    }],
                    xaxis: {
                        categories: ["01 - Ronald", "02 - Steve Ronald", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "12", "12", "12", "12", "12", "12", "12", "12"],
                        position: 'bottom',
                        labels: {
                            rotate: -45,
                            style: {
                                colors: colors,
                                fontSize: '14px'
                            }

                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    yaxis: {
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false,
                        },
                        labels: {
                            show: false,
                            formatter: function (val) {
                                return val + "%";
                            }
                        }

                    },
                    fill: {
                        colors: ['#ffff00']
                    }
                }

                var chart = new ApexCharts(
                    document.querySelector("#chart"),
                    options
                );

                chart.render();
            </script>

        <?php endif; ?>

        <?php if($option == 'vertical'): ?>

            <div class="uk-child-width-1-4@m uk-padding" uk-grid uk-height-match="target: > div > .uk-card">
                <?php foreach ($phase_candidat as $candidat): $current_candidat = get_post($candidat['candidat'])?>
                <div>
                    <div class="uk-card uk-card-default card-candidat uk-card-small">
                        <div class="uk-card-media-top uk-margin-remove uk-h1 uk-text-center uk-padding-small" style="background-color:yellow; color: #016db5;">
                            <span class="integers"><?= pourcentage($candidat['candidat']) ? round(pourcentage($candidat['candidat']) / $total, 1) : pourcentage($candidat['candidat']);  ?></span> %
                        </div>
                        <div class="uk-card-body">
                            <h3 class="uk-margin-remove-bottom uk-text-truncate uk-text-center"><?= $candidat['codevote'] ?> - <?= $current_candidat->post_title ?></h3>

                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>

        <?php endif; ?>

        <?php if($option == 'single'): ?>

            <?php $the_candidat = tr_posts_field('show_current', $phase[0]->ID); ?>
            <?php if($the_candidat): ?>
            <?php $result = search($phase_candidat, 'candidat', $the_candidat)[0] ?>

            <div class="uk-child-width-1-2@m uk-padding">
                <?php $current_candidat = get_post($the_candidat)?>
                <div class="uk-position-center">
                    <div class="uk-card uk-card-default card-candidat uk-width-medium">
                        <div class="uk-card-media-top uk-flex uk-flex-center uk-flex-middle uk-padding">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" alt="">
                        </div>
                        <div class="uk-card-media-top uk-margin-remove uk-h1 uk-text-center uk-padding-large" style="background-color:yellow; color: #016db5;">
                            <span class="integers"><?= pourcentage($result['candidat']) ? round(pourcentage($result['candidat']) / $total, 1) : pourcentage($result['candidat']);  ?></span> %
                        </div>
                        <div class="uk-card-body">
                            <h3 class="uk-margin-remove-bottom uk-text-truncate uk-text-center"><?= $result['codevote'] ?> - <?= $current_candidat->post_title ?></h3>
                            <progress class="uk-progress progress" value="0" max="100" data-pourcentage="<?= pourcentage($result['candidat']) ? round(pourcentage($result['candidat']) / $total, 1) : pourcentage($result['candidat']);  ?>"></progress>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>


        <?php endif; ?>

        <?php if($option == 'message'): ?>
            <div class="uk-card uk-card-default uk-card-body uk-width-1-1 uk-position-center">
                <div class="uk-card-media-top uk-flex uk-flex-center uk-flex-middle">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" alt="">
                </div>
                <h3 class="uk-h2 uk-text-center">Votez votre candidate</h3>
                <p class="uk-text-lead uk-text-center">Vote ta candidate sur WhatsApp en voyant <br><strong>MISSORANGINA</strong>
                    <br>au</p>
                <h3 class="uk-h2 uk-text-center uk-text-bold uk-text-danger">680 53 80 80</h3>
            </div>
        <?php endif; ?>
    </div>
    <?php get_template_part('partials/menu') ?>
</div>


<?php get_footer(); ?>
