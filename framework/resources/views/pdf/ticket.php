<style>
    table{
        width: 100%;
        border-collapse:collapse; border-spacing:0;
    }
    table td{
        padding: 10px;
        border-bottom: 1px solid #000;
    }
</style>
<page backtop="7mm" backbottom="7mm" backleft="5mm" backright="10mm">
    <page_footer>
        Miss Orangina : listes des tickets [[page_cu]]/[[page_nb]]
    </page_footer>

    <table>
        <tr>
            <td style="width: 31.5%;"></td>
            <td style="width: 39%; text-align: center;">
                <h3>Miss Orangina </h3>
                LISTE DES TICKETS DISPONIBLES<br>
            </td>
            <td style="width: 31.5%;"></td>
        </tr>
    </table>
    <br/>

    <?php $ligne = count($tickets) / 10; ?>

    <table>
        <?php for($i=0; $i < $ligne; $i++): ?>

            <?php
                $offset = 0;
                if($i >= 1):
                    $offset = $i * 10;
                endif;
            ?>

            <tr>

                <?php foreach (array_slice($tickets, $offset, 10) as $ticket): ?>

                <td style="border: 1px solid #000;"><?= $ticket->post_title; ?> (<?= tr_posts_field('point', $ticket->ID) ?>)</td>

                <?php endforeach; ?>

            </tr>

        <?php endfor; ?>
    </table>



</page>