<?php

$meta_box = tr_meta_box('inscription globale');
$meta_box->setLabel('Globales');
$meta_box->addScreen('dashboard');

$meta_box->setCallback(function(){

    $args = [
        'post_type' => 'inscrit',
        'posts_per_page' => '-1',
        'meta_query' => array(
            array(
                'key' => 'year_participe',
                'value' => tr_options_field('options.ins_year'),
                'compare' => '='
            )
        )
    ];

    $candidat = query_posts($args);

?>

    <div class="uk-padding-small" uk-grid>
        <div class="uk-width-1-1">
            <table class="uk-table">
                <caption><strong>STATISTIQUE GLOBALE <?= tr_options_field('options.ins_year'); ?></strong></caption>
                <tbody>
                    <tr>
                        <td><strong>Total des inscrits</strong></td>
                        <td><?= count($candidat); ?> candidats</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

<?php
});


$meta_box->setPriority('high');



/**
 * Liste des elements du tableau de bord par defaut a initialiser
 */

function remove_dashboard_widgets()
{
    global $wp_meta_boxes;

    // Tableau de bord général
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); // Presse-Minute
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Commentaires récents
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // Extensions
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // Liens entrant
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // Billets en brouillon
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // Blogs WordPress
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // Autres actualités WordPress
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']); // Active sur le site
}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets');
