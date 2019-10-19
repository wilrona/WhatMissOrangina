<?php

// Ajouter un champ personnalisé sur la catégorie

$cat = new \TypeRocket\Register\Taxonomy('category');
$cat->setMainForm( 'postLayout' );
\TypeRocket\Register\Registry::taxonomyFormContent( $cat );


function add_form_content_category_main(){

	$form = tr_form();

	echo $form->color('couleur_cat')->setLabel('Couleur de la catégorie')->setHelp('La couleur ne s\'utilise que si la catégorie possède un catégorie parente.');
//	echo $form->text('Suffix de la couleur')->setName('suffix')->setHelp('Entrer le suffix de la classe pour apliquer une couleur (ex: -act).');
}

