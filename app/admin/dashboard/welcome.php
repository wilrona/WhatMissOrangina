<?php


remove_action('welcome_panel','wp_welcome_panel');
add_action('welcome_panel','st_welcome_panel');


function st_welcome_panel(){
    ?>

    <div class="welcome-panel-content">
        <h2>Bienvenue dans l'application de vote de Miss Orangina</h2>
        <p class="about-description uk-margin-bottom">Cette application vous fournit l'état des votes</p>
    </div>

    <?php
}

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