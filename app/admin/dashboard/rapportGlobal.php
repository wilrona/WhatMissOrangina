<?php

$meta_box = tr_meta_box('stat_type_vote');
$meta_box->setLabel('Statistique type de vote');
$meta_box->addScreen('dashboard');

$meta_box->setCallback(function(){

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

    if($phase):


    $count_home = tr_query()->table('wp_miss_vote')
                ->where('type_vote', '=', 'HOME')
                ->where('idphase', '=', $phase[0]->ID)
                ->count();

    $count_site_ticket = tr_query()->table('wp_miss_vote')
                ->where('type_vote', '=', 'SITE')
                ->where('idphase', '=', $phase[0]->ID)
                ->where('idserie', '!=', null)->count();

    $count_site_anonyme = tr_query()->table('wp_miss_vote')
        ->select('SUM(point) as vote')
        ->where('type_vote', '=', 'SITE')
        ->where('idphase', '=', $phase[0]->ID)
        ->where('idserie', '=', null)->get();
?>

    <div class="uk-padding-small" uk-grid>
        <div class="uk-width-1-1">
            <table class="uk-table">
                <caption><strong>STATISTIQUE TYPE DE VOTE</strong></caption>
                <tbody>
                    <tr>
                        <td><strong>HOME</strong></td>
                        <td><?= $count_home ?></td>
                    </tr>
                    <tr>
                        <td><strong>SITE</strong></td>
                        <td><?= $count_site_ticket ?></td>
                    </tr>
                    <tr>
                        <td><strong>ANONYME</strong></td>
                        <td><?= $count_site_anonyme[0]->vote ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

<?php

    endif;
});


$meta_box->setPriority('high');
