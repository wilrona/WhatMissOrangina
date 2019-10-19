<style>
    table{ width: 100%;}
</style>

<page backtop="7mm" backbottom="7mm" backleft="10mm" backright="10mm">


    <table>
        <tr>
            <td style="width: 25%;"></td>
            <td style="width: 50%; text-align: center;">
                <h3>Miss Orangina <?= tr_options_field('options.ins_year') ?></h3>
                FORMULAIRE D’INSCRIPTION
            </td>
            <td style="width: 25%;"></td>
        </tr>
    </table>

    <table style="border: 1px solid #000; margin-top: 10px; padding: 10px;">
        <tr>
            <td style="text-align: justify;">
                La prise en compte effective de l’inscription implique que le présent formulaire et le règlement
                soient signés et accompagnés de la photocopie de CNI de la candidate, et  <b>d’un accord parental pour les  les candidates âgées de moins de 21 ans. En outre, les candidates mineures peuvent être accompagnées par leur père ou leur tuteur LEGAL</b>
            </td>
        </tr>
    </table>

    <h4 style="text-align: center"> INSCRIPTION (<?php echo tr_posts_field('codeins', $candidat->ID) ?>)</h4>
    <table style="margin-top: 10px;">
        <tr>
            <td style="padding: 10px 0 0; width: 50%;" colspan="2">Je soussignée, Mademoiselle <b><?php echo $candidat->post_title; ?></b></td>
        </tr>
        <tr>
            <td style="padding: 10px 0 0; width: 50%;" colspan="2">Atteste de l’exactitude des informations ci-après :</td>
        </tr>
        <tr>
            <td style="padding: 10px 0 0; width: 50%;" colspan="2">Je suis née le <?= tr_posts_field('datenais', $candidat->ID); ?> a <?= tr_posts_field('lieu', $candidat->ID); ?></td>

        </tr>
        <tr>
            <td style="padding: 10px 0 0; width: 50%;">Je suis de Nationalité</td>
            <td style="width: 50%;"><strong><?php echo tr_posts_field('nationalite', $candidat->ID); ?></strong></td>
        </tr>
        <tr>
            <td style="padding: 10px 0 0; width: 50%;">Mon Adresse E-Mail est</td>
            <td style="padding: 10px 0 0; width: 50%;"><?php echo tr_posts_field('email', $candidat->ID) ?></td>
        </tr>
        <tr>
            <td style="padding: 10px 0 0; width: 50%;">Mon Adresse Personnelle est <strong><?php echo tr_posts_field('adresse', $candidat->ID); ?></strong>
            </td>
            <td style="padding: 10px 0 0; width: 50%;"><strong>Ville :</strong><?php echo tr_posts_field('position', $candidat->ID) ?></td>

        </tr>
        <tr>
            <td style="padding: 10px 0 0; width: 50%;">Mon Numéro de Tél. est le</td>
            <td style="padding: 10px 0 0; width: 50%;"><b><?php echo tr_posts_field('phone', $candidat->ID) ?></b></td>
        </tr>
    </table>

    <h4 style="text-align: center"> QUESTIONNAIRE </h4>

    <table style="margin-top: 10px;">
        <tr>
            <td style="padding: 10px 0; width: 50%;">Profession ou études en cours</td>
            <td style="width: 50%;"><?php echo tr_posts_field('profession', $candidat->ID) ?></td>
        </tr>
        <tr>
            <td style="padding: 10px 0; width: 50%;">Dernier diplôme obtenu </td>
            <td style="width: 50%;"><?php echo tr_posts_field('diplome', $candidat->ID) ?></td>
        </tr>
        <tr>
            <td style="padding: 10px 0; width: 50%">Quel est votre compte facebook/twitter ?

            </td>
            <td style="width: 50%;"><?php echo $this->candidat['dream'] ?></td>
        </tr>
        <tr>
            <td style="padding: 10px 0; width: 50%">Quel est votre signe distintif ?
            </td>
            <td style="width: 50%;">
                <?php echo tr_posts_field('compte', $candidat->ID) ?>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 0; width: 50%;">Avez-vous déjà participé à un concours de beauté ? <br/>Si oui, à quelle occasion</td>
            <td style="width: 50%;"><?php echo tr_posts_field('participe', $candidat->ID) ?> </td>
        </tr>
        <tr>
            <td style="padding: 10px 0; width: 50%;">Votre taille sans talons</td>
            <td style="width: 50%"><?php echo tr_posts_field('taille', $candidat->ID) ?></td>
        </tr>
        <tr>
            <td style="padding: 10px 0; width: 50%;">Avez vous un casier judiciaire ? </td>
            <td style="width: 50%"><?php echo tr_posts_field('casier', $candidat->ID) ?></td>
        </tr>
        <tr>
            <td style="padding: 10px 0; width: 50%;">Combien d’enfant (s) avez-vous ?</td>
            <td style="width: 50%;"> <?php echo tr_posts_field('enfant', $candidat->ID) ?> </td>
        </tr>

    </table>


    <table style="margin-top: 20px;">
        <tr>
            <td style="width: 50%"></td>
            <td style="width: 50%; text-align: center;">
                Fait à ---------------------------, le----------------------- <?= date('Y') ?> <br/><br/>
                <b>Nom Prenom et Signature</b>

            </td>
        </tr>
    </table>



</page>