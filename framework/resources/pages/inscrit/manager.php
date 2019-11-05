<h2>Phase en cours : <?php if($phase): ?><span><?= $phase->post_title ?></span><?php endif; ?></h2>

<?php

function pourcentage($user_id, $vote=false){

    $candidats = tr_query()->table('wp_posts')
        ->select('wp_posts.*', 'SUM(wp_miss_vote.point) as vote')
        ->join('wp_miss_vote', 'wp_miss_vote.idcandidat', '=', 'wp_posts.ID')
        ->where('wp_posts.ID', 'IN', [$user_id])
        ->groupBy('wp_posts.ID')
        ->findAll()->orderBy('vote', 'DESC')->get();

    if($vote):
        return $candidats ? $candidats[0]->vote : 0 ;
    else:
        return $candidats ? $candidats[0]->vote * 100 : 0 ;
    endif;
}

?>

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
        <th>Pourcentage</th>
        <th>Show front</th>
        <th class="uk-width-1-6"></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($candidats as $candidat): $current_candidat = get_post($candidat['candidat'])?>
        <tr>

            <td><?= $candidat['codevote'] ?></td>
            <td><?= $current_candidat->post_title; ?></td>
            <td><?= pourcentage($candidat['candidat'], true); ?></td>
            <td><?= pourcentage($candidat['candidat']) ? round(pourcentage($candidat['candidat']) / $total, 1) : pourcentage($candidat['candidat']); ?></td>
            <td>
                <input type="radio" name="tr[show]" value="<?= $current_candidat->ID; ?>" <?php if(tr_posts_field('show_current', $phase->ID) == $current_candidat->ID): ?> checked <?php endif; ?>>
            </td>
            <td>
                <input type="number" name="tr[vote][]" class="uk-input uk-form-width-xsmall" value="0" min="0">
                <input type="hidden" name="tr[candidat][]" value="<?= $current_candidat->ID; ?>">
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?= $form->submit('Enregistrer'); ?>
<?= $form->close(); ?>
