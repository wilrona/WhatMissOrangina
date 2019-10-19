<?php

add_action('wp_ajax_contactusform', 'contactusform_callback');
add_action('wp_ajax_nopriv_contactusform', 'contactusform_callback');



function contactusform_callback() {
	check_ajax_referer('contactus_form_ajax', 'security');


    wp_die();
}