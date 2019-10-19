<?php




add_action('admin_head', function () {
  if (get_page_template_slug(get_the_ID()) === 'actualite.php') :
    remove_post_type_support('page', 'editor');
  endif;
});
