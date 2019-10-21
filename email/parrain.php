<html>
<body>

    Bonjour, <br><br>

    Votre ami(e) <strong><?= $candidat->post_title; ?></strong> vous invite à la soutenir pour le concours Miss Orangina <?= tr_options_field('options.ins_year'); ?>.
    <br><br>
    Cliquez sur ce lien pour accéder au site <a href="<?= home_url() ?>">Miss Orangina</a>
    <br>
    <br>
    <?php if(tr_options_field('options.online')): ?>
        <strong>Infoline : </strong> <?= tr_options_field('options.online') ?>
    <?php endif; ?>
    <strong>L'équipe Orangina</strong>


</body>
</html>