<html>
<body>

    Bonjour, <?= $candidat->post_title ?> <br>

    Nous vous remercions pour votre inscription à notre concours Miss Orangina <?= tr_options_field('options.ins_year'); ?>.
    Nous vous invitons à imprimer votre formulaire d'inscription en cliquant sur le lien ci-dessous et à vous présenter au lieu de casting correspondant à votre ville. <br><br>

    Consultez notre site web afin d'être plus informé sur les lieux de casting. <br><br>

    <strong>Présentez vous munie de votre carte nationale d'identité ou de votre carte d'identite scolaire</strong><br><br>

    Si vous êtes âgée de moin de 21 ans révolu au jour du casting, veuillez aussi imprimer, faire signer l'autorisation parentale par votre prère ou tuteur légal
    et la faire legaliser auprès des autorités compétentes. <br>

    A ce document, bien vouloir joindre les photocopies de votre CNI. <br><br>
    Si votre parent est dans l'impossibilité de légaliser l'autorisation parentale au préalable, il peut la signer au lieu de casting ou avant ce jour en se rendant
    au siège de l'agence ACCENT COM (367, Rue Paul Monthé-Rue UTA, Bonapriso, Douala). <br><br>

    Si vous envisager de presenter une carte d'identité scolaire, veuillez ramener la photocopie de votre acte de naissance. <br><br>

    <?php if(tr_options_field('options.auth_parental')): ?>
        <a href="<?= wp_get_attachment_image_src(tr_options_field('options.auth_parental'), 'full')[0]; ?>" class="">Télécharger l'autorisation parentale</a>
    <?php endif; ?>

    <br><br>

    <?php $code = tr_posts_field('codeins', $candidat->ID); ?>
    <a href="<?= tr_redirect()->toHome('/inscrit/formulaire/'.$code)->url; ?>" class="">Telecharger mon formulaire d'inscription</a>
    <br>
    <br>
    <?php if(tr_options_field('options.online')): ?>
    <strong>Infoline : </strong> <?= tr_options_field('options.online') ?><br>
    <?php endif; ?>
    <strong>L'équipe Orangina</strong>


</body>
</html>





