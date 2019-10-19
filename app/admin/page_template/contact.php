<?php


$boxUne = tr_meta_box('Formulaire de contact');
$boxUne->addScreen('page'); // updated
$boxUne->setCallback(function () {
  $form = tr_form();
  echo $form->text('form_contact')->setLabel('Shorcode de contact form 7')->setHelp('Il faut uniquement introduire un shortcode du plugin contact form 7');
});

$boxWelcome = tr_meta_box('Information de contact complementaire');
$boxWelcome->addScreen('page');
$boxWelcome->setCallback(function () {
  $form = tr_form();
  echo $form->editor('texte_contact')->setLabel('Information complementaire');
});


add_action('admin_head', function () use ($boxUne, $boxWelcome) {
  if (get_page_template_slug(get_the_ID()) !== 'contact.php') :
    remove_meta_box($boxUne->getId(), 'page', 'normal');
    remove_meta_box($boxWelcome->getId(), 'page', 'normal');
  endif;
});
