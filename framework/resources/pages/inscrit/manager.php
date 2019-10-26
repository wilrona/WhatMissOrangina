<h2>Phase en cours : <?php if($phase): ?><span><?= $phase->post_title ?></span><?php endif; ?></h2>

<?php
    $form = tr_form('vote', 'anonyme_vote');

    $form->useUrl('post', '/vote/anonyme');
?>
<?= $form->open(); ?>

<input type="hidden" name="tr[phase]" value="<?= $phase->ID ?>">

<table class="uk-table uk-table-middle">
    <thead>
    <tr>
        <th>No</th>
        <th>Nom du candidat</th>
        <th>Nombre de vote</th>
        <th class="uk-width-1-6"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($candidats as $candidat): ?>
        <tr>

            <td><?= $numero[$candidat->ID] ?></td>
            <td><?= $candidat->post_title; ?></td>
            <td><?= $candidat->vote ?></td>
            <td>
                <input type="number" name="tr[vote][]" class="uk-input uk-form-width-xsmall" value="0" min="0">
                <input type="hidden" name="tr[candidat][]" value="<?= $candidat->ID; ?>">
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?= $form->submit('Enregistrer'); ?>
<?= $form->close(); ?>
