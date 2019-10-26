<!DOCTYPE html>
<!--[if lt IE 7]><html
        class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]><html
        class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]><html
        class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en">
<!--<![endif]-->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
    <title>
        <?php if (is_category()) {
            single_cat_title();
            echo ' | ';
            bloginfo('name');
            //	    } elseif ( is_tag() ) {
            //		    echo 'Tag Archive for &quot;'; single_tag_title(); echo '&quot; | '; bloginfo( 'name' );
            //	    } elseif ( is_archive() ) {
            //		    wp_title(''); echo ' Archive | '; bloginfo( 'name' );
        } elseif (is_search()) {
            echo 'Recherche pour &quot;' . wp_specialchars($s) . '&quot; | ';
            bloginfo('name');
        } elseif (is_home() || is_front_page()) {
            bloginfo('name');
            echo ' | ';
            bloginfo('description');
        } elseif (is_404()) {
            echo 'Error 404 Not Found | ';
            bloginfo('name');
        } elseif (is_single()) {
            wp_title('');
            echo ' | ';
            bloginfo('name');
        } else {
            wp_title('');
            echo ' | ';
            bloginfo('name');
        } ?>
    </title>
    <meta http-equiv="x-ua-compatible" content="ie=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="<?= wp_get_attachment_image_src(tr_options_field('options.icon'), 'full')[0]; ?>" />
    <?php
    wp_head();
    ?>

</head>

<body class="uk-position-relative">

