<?php
if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

// Setup Form
$form = tr_form()->useJson()->setGroup($this->getName());
?>

<h1>Theme Options</h1>
<div class="typerocket-container">
    <?php
    echo $form->open();

    $company = function () use ($form) {

        echo '<h2 class="uk-padding-remove-bottom uk-text-center">Traitement du vote</h2>';

        echo $form->text('sequence_vote')->setType('number')->setLabel('Sequence de vote');

        echo '<hr />';

        echo $form->image('logo')->setLabel('Logo du site web')->setSetting('button', 'Inserer le logo');

        echo '<hr />';

        $villas = [
            'Affichage en bloc' => 'block',
            'Affichage en bloc sans statistique' => 'block_no_stat',
            'Affichage en barre' => 'bar',
            'Affichage en block vertical' => 'vertical',
            'Affichage candidat single' => 'single',
            'Affichage Message' => 'message',
            'Affichage du chrono' => 'chrono'
        ];

        echo $form->select('type_affiche')->setLabel('Type d\'affichage des resultats')->setOptions($villas);

        echo $form->select('active_vote')->setLabel('Activation des votes')->setOptions([
            'Oui' => 1,
            'Non' => 0
        ]);

        echo $form->select('type_vote')->setLabel('Type de vote')->setOptions([
            'Vote SITE' => 'site',
            'Vote HOME' => 'home',
            'Vote HOME et SITE' => 'both'
        ]);

        echo $form->text('chrono')->setType('number')->setLabel('Valeur du chrono')->setAttribute('value', 15);
    };
    // Save
    $save = $form->submit('Enregistrement');

    // Layout
    tr_tabs()->setSidebar($save)
        ->addTab('Traitement de vote', $company)
        ->render('box');
    echo $form->close();
    ?>

</div>
