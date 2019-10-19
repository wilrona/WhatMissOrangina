<?php


remove_action('welcome_panel','wp_welcome_panel');
add_action('welcome_panel','st_welcome_panel');


function st_welcome_panel(){
    ?>

    <div class="welcome-panel-content">
        <h2>Bienvenue dans l'application Miss Orangina</h2>
        <p class="about-description uk-margin-bottom">Cette application vous fournit l'état des inscriptions et des votes du consours de miss</p>
    </div>

    <?php
}