<?php


$custom_page = tr_page('Vote', 'manager', 'Manager');
$custom_page->setTitle('Manager de vote');
$custom_page->useController();
$custom_page->setArgument('position', 21);
$custom_page->setIcon('desktop');
