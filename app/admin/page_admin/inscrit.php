<?php


$custom_page = tr_page('Inscrit', 'index', 'Inscrits '. tr_options_field('options.ins_year'));
$custom_page->useController();
$custom_page->setArgument('position', 21);
$custom_page->setIcon('woman');