<?php

$meta_box = tr_meta_box('inscription age');
$meta_box->setLabel('Rapport par age');
$meta_box->addScreen('dashboard');

$meta_box->setCallback(function(){


    $years = tr_query()->table('wp_posts')
        ->setIdColumn('ID')
        ->select('wp_posts.ID')
        ->join('wp_postmeta', 'wp_postmeta.post_id', '=', 'wp_posts.ID')
        ->where('wp_posts.post_type', '=', 'inscrit')
        ->where('wp_postmeta.meta_key', '=', 'year_participe')
        ->where('wp_postmeta.meta_value', '=', tr_options_field('options.ins_year'))
        ->findAll()->get();

    $user_id = array();

    if($years):
        foreach ($years as $year):
            $user_id[] = $year->ID;
        endforeach;
    endif;

    $villes = tr_query()->table('wp_posts')
        ->select('count(*) as postcount', 'YEAR(CURDATE())-YEAR(wp_postmeta.meta_value) as Age', 'YEAR(wp_postmeta.meta_value) as date')
        ->join('wp_postmeta', 'wp_postmeta.post_id', '=', 'wp_posts.ID')
        ->where('wp_posts.post_type', '=', 'inscrit')
        ->where('wp_postmeta.meta_key', '=', 'datenais_format')
        ->where('wp_posts.ID', 'IN', $user_id)
        ->findAll()
        ->groupBy('Age')
        ->get();
?>

    <div class="uk-padding-small" uk-grid>
        <div class="uk-width-1-1">
            <table class="uk-table">
                <caption><strong>STATISTIQUE PAR AGE <?= tr_options_field('options.ins_year'); ?></strong></caption>
                <tbody>
                <?php if ($years && $villes): ?>
                    <?php foreach ($villes as $ville): ?>
                        <tr>
                            <td><strong><?= $ville->Age ?> ans</strong></td>
                            <td><?= $ville->postcount ?> candidats</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2"><h3>Aucune donnée disponible</h3></td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>
        </div>

    </div>

<?php
});


$meta_box->setPriority('high');
$meta_box->setContext('side');



