<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 04/05/2018
 * Time: 21:12
 */

add_action('admin_head', function () {
    if (get_page_template_slug(get_the_ID()) == 'like.php') :
        remove_post_type_support('page', 'editor');
    endif;

    if (get_page_template_slug(get_the_ID()) == 'parrain.php') :
        remove_post_type_support('page', 'editor');
    endif;

    if (get_page_template_slug(get_the_ID()) == 'formInscription.php') :
        remove_post_type_support('page', 'editor');
    endif;

});
