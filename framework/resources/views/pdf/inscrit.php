<style>
    table{
        width: 100%;
        border-collapse:collapse; border-spacing:0;
    }
    table td{
        padding: 5px;;
        border-bottom: 1px solid #000;
    }
</style>
<page backtop="7mm" backbottom="7mm" backleft="10mm" backright="10mm">
    <page_footer>
        Miss Orangina : listes des inscriptions [[page_cu]]/[[page_nb]]
    </page_footer>
    <table>
        <tr>
            <td style="width: 31.5%;"></td>
            <td style="width: 39%; text-align: center;">
                <h3>Miss Orangina </h3>
                LISTE DES INSCRIPTIONS <br>
                <?php if(!empty($s)): ?>
                    <strong>Recherche :</strong> <?= $s ?>
                <?php endif; ?>
                <?php if(!empty($slug) && $slug !== 'all'): ?>
                    <strong>âge :</strong><?= $slug ?> ans;
                <?php endif; ?>
                <?php if(!empty($slug_year) && $slug_year !== 'all'): ?>
                    <strong>Année :</strong><?= $slug_year ?>;
                <?php endif; ?>
            </td>
            <td style="width: 31.5%;"></td>
        </tr>
    </table>
    <br/>

    <table>

        <tr>
            <td style="width: 5%;
        border-bottom: 1px solid #000; ">No</td>
            <td style="width: 10% ;
        border-bottom: 1px solid #000; ">Code Insc.</td>
            <td style="width: 25%;
        border-bottom: 1px solid #000; ">Nom et prenom</td>
            <td style="width: 25%;
        border-bottom: 1px solid #000; ">Contact</td>
            <td style="width: 22%;
        border-bottom: 1px solid #000; ">Age</td>
            <td style="width: 10%;
        border-bottom: 1px solid #000; ">Ville</td>
        </tr>

        <?php foreach($candidats as $i => $inscrit): ?>
            <tr>
                <td style="width: 5% ;border-right: 1px solid #000;border-left: 1px solid #000; ">
                    <?= $i+1 ?>
                </td>
                <td style="width: 10% ; border-right: 1px solid #000;">
                    <?= tr_posts_field('codeins', $inscrit->ID) ?>
                </td>
                <td style="width: 25%; border-right: 1px solid #000;">
                    <?= tr_posts_field('nom', $inscrit->ID)." ".tr_posts_field('prenom', $inscrit->ID); ?>
                </td>
                <td style="width: 25%; border-right: 1px solid #000;">
                    <strong>Email : </strong><?= tr_posts_field('email', $inscrit->ID) ?> <br>
                    <strong>Phone : </strong><?= tr_posts_field('phone', $inscrit->ID) ?>
                </td>
                <td style="width: 22%;border-right: 1px solid #000;">
                    <?php

                    list($jour, $mois, $annee) = preg_split('[/]', tr_posts_field('datenais', $inscrit->ID));
                    $today['mois'] = date('n');
                    $today['jour'] = date('j');
                    $today['annee'] = date('Y');
                    $annees = $today['annee'] - $annee;

                    ?>
                    <?=   $annees . ' ans';     ?>
                </td>
                <td style="width: 10%;border-right: 1px solid #000;">
                    <?=  tr_posts_field('position', $inscrit->ID)    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</page>