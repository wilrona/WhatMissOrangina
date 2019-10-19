<?php
/**
 * Created by IntelliJ IDEA.
 * User: online2
 * Date: 12/12/2017
 * Time: 17:50
 */



$agenda = tr_post_type('Agenda', 'Agenda');

$agenda->setIcon('calendar');
$agenda->setArgument('supports', ['title'] );

$box = tr_meta_box('Evenement')->setLabel('Evenement de l\'agenda');

$box->addPostType( $agenda->getId() );


$box->setCallback(function (){
	$form = tr_form();
	$repeater = $form->repeater('event')->setFields([

		$form->text('nomspeaker')->setLabel('Nom du speaker'),
		$form->text('fonctionspeaker')->setLabel('Fonction du speaker'),
		$form->image('imgspeaker')->setLabel('Photo du speaker'),
		$form->editor('descriptionsujet')->setLabel('Description du sujet'),
		$form->text('heuredebut')->setLabel('Heure de début')->setAttribute('placeholder', 'HH:MM'),
		$form->text('heurefin')->setLabel('Heure de fin')->setAttribute('placeholder', 'HH:MM'),

	])->setLabel('Ajouter des intervenants dans cette agenda');
	echo $repeater;

});