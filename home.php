<?php /* Template Name: Page accueil */ ?>

<?php get_header() ?>

<?php while (have_posts()) : the_post(); ?>

    <div class="uk-position-relative" id="header">



        <div class="uk-position-relative uk-cover-container" uk-height-viewport="offset-bottom: true">
            <?php echo do_shortcode(tr_posts_field('slider')); ?>
        </div>
        <?php get_template_part('partials/menu') ?>
    </div>


    <?php if(tr_options_field('options.ins_active')): ?>

    <div class="uk-section-default uk-section">
        <div class="">
            <div class="uk-grid-collapse" uk-grid>
                <div class="uk-width-3-5@m uk-margin-remove uk-height-1-1 uk-flex uk-flex-middle uk-flex-center">
                    <div class="uk-padding uk-padding-remove-vertical">

                        <h2 class="font-flavour uk-text-center"><?= tr_posts_field('titre_concept2') ?></h2>

                        <div class="concept uk-padding">
                            <ul class="uk-list uk-list-bullet uk-margin-remove">
                                <?php foreach (tr_posts_field('list_concept') as $concept):

                                    ?>
                                <li>
                                    <?= $concept['text'] ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="uk-text-center uk-margin-top">
                            <a href="http://missorangina.test/facebook" class="uk-button uk-button-large uk-button-sign-in uk-button-primary fb-login" >
                                <?= tr_options_field('options.ins_buttonmessage') ?>
                            </a>
                        </div>


                    </div>

                </div>
                <div class="uk-width-2-5@m uk-margin-remove uk-cover-container uk-inline">

                    <?php if(tr_posts_field('img_concept1')): ?>

                        <img src="<?= wp_get_attachment_image_src(tr_posts_field('img_concept1'), 'full')[0] ?>" alt="" class="uk-blend-darken" uk-cover>


                        <?php if(tr_posts_field('titre_concept1')): ?>
                        <div class="uk-position-top uk-position-cover uk-overlay uk-overlay-mo uk-light uk-flex uk-flex-middle uk-flex-center">

                                <div>
                                    <h2><?= tr_posts_field('titre_concept1') ?></h2>
                                    <div class="uk-text-white">
                                        <?= tr_posts_field('desc_concept1') ?>
                                    </div>
                                </div>

                        </div>
                        <?php endif; ?>

                    <?php else: ?>


                        <div class="uk-position-top uk-position-cover uk-overlay uk-overlay-mo uk-light uk-flex uk-flex-middle uk-flex-center">
                            <?php if(tr_posts_field('titre_concept1')): ?>
                                <div>
                                    <h2><?= tr_posts_field('titre_concept1') ?></h2>
                                    <div class="uk-text-white">
                                        <?= tr_posts_field('desc_concept1') ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>


                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php endif; ?>


    <?php if(tr_posts_field('list_lieu')) : ?>

    <div class="uk-section uk-section-mo">
        <div class="uk-container">
            <h2 class="uk-text-center font-flavour uk-margin-bottom uk-h1"><?= tr_posts_field('titre_selection') ?></h2>
            <div class="uk-flex uk-flex-center@l">
                <ul class="uk-flex-center" uk-tab="connect: #lieu; animation: true;">
                    <?php foreach (tr_posts_field('list_lieu') as $lieu): ?>
                        <?php $post_lieu = get_post($lieu['search']); ?>
                        <li><a href="#"><?= get_the_title($post_lieu->ID) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <ul class="uk-switcher uk-margin" id="lieu">
                <?php foreach (tr_posts_field('list_lieu') as $lieu): ?>
                    <?php $post_lieu = get_post($lieu['search']); ?>


                    <li>

                        <div class="uk-child-width-1-2@l uk-flex uk-flex-center" uk-grid uk-height-match=".uk-card">

                            <?php foreach (tr_posts_field('list_lieux', $post_lieu->ID) as $fixe): ?>
                                <div>
                                    <div class="uk-card uk-card-default uk-grid-collapse uk-margin" uk-grid>
                                        <div class="uk-card-media-left uk-cover-container uk-width-1-2@l uk-width-1-3">
                                            <img src="<?= wp_get_attachment_image_src($fixe['image'], 'full')[0] ?>" alt="" uk-cover>
                                            <canvas width="600" height="400"></canvas>
                                        </div>
                                        <div class="uk-width-1-2@l uk-width-2-3">
                                            <div class="uk-card-body">
                                                <h3 class="uk-h1 font-flavour uk-card-title"><?= $fixe['ville']; ?></h3>
                                                <div class="">
                                                    <ul class="uk-list">
                                                        <li><span uk-icon="icon: location;"></span> - <?= $fixe['lieu']; ?></li>
                                                        <li><span uk-icon="icon: calendar;"></span> -  <?= $fixe['date']; ?></li>
                                                        <li><span uk-icon="icon: clock;"></span> - <?= $fixe['heure']; ?></li>
                                                    </ul>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>


                    </li>
                <?php endforeach; ?>


            </ul>

        </div>
    </div>

    <?php endif; ?>

    <div class="uk-section-default uk-section">
        <div class="uk-container">
            <div class="uk-margin" uk-grid>
                <div class="uk-width-2-3@m uk-margin-remove uk-flex uk-flex-middle uk-flex-center">
                    <div class="">

                        <h2 class="font-flavour uk-text-center uk-h1"><?= tr_posts_field('titre_condition') ?></h2>

                        <div class="concept uk-padding">

                            <ul class="uk-list uk-list-bullet uk-margin-remove">
                                <?php foreach (tr_posts_field('list_condition') as $concept):

                                    ?>
                                    <li>
                                        <?= $concept['text'] ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                        </div>
                        <?php if(tr_options_field('options.ins_active')): ?>
                        <div class="uk-text-center uk-margin-top">
                            <a href="http://missorangina.test/facebook" class="uk-button uk-button-large uk-button-sign-in uk-button-primary fb-login" >
                                <?= tr_options_field('options.ins_buttonmessage') ?>
                            </a>
                        </div>
                        <?php endif; ?>

                    </div>

                </div>

                <div class="uk-width-1-3@m uk-margin-remove uk-visible@l">
                    <div class="uk-width-1-1 uk-flex uk-flex-center uk-flex-middle">
                        <?php
                            $small_height = tr_options_field('options.widget_small_height') ? true : false;
                            $adapt_container_width = tr_options_field('options.widget_adapt_container_width') ? true : false;
                            $hide_cover = tr_options_field('options.widget_hide_cover') ? true : false;
                            $show_facepile = tr_options_field('options.widget_show_facepile') ? true : false;
                            $height = tr_options_field('options.widget_height') && intval(tr_options_field('options.widget_height')) >= 70 ? tr_options_field('options.widget_height') : 70;
                            $width = tr_options_field('options.widget_width');
                            if($width):
                                if($width < 180):
                                    $width = 180;
                                else:
                                    if($width > 500):
                                        $width = 500;
                                    else:
                                        $width = tr_options_field('options.widget_width');
                                    endif;
                                endif;
                            else:
                                $width = 180;
                            endif;

                        ?>
                        <iframe src="https://www.facebook.com/plugins/page.php?href=<?= tr_options_field('options.widget_link') ?>&tabs=timeline&width=<?= $width ?>&height=<?= $height ?>&small_header=<?= $small_height ?>&adapt_container_width=<?= $adapt_container_width ?>&hide_cover=<?= $hide_cover ?>&show_facepile=<?= $show_facepile ?>&appId=<?= tr_options_field('options.widget_appid') ?>" width="<?= $width ?>" height="<?= $height ?>" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
                    </div>


                </div>

            </div>
        </div>
    </div>

<?php if(tr_posts_field('list_selection')): ?>

    <div class="uk-section uk-section-mo">
        <div class="uk-container uk-container-small">
            <h2 class="uk-text-center font-flavour uk-margin-bottom uk-h1"><?= tr_posts_field('titre_selectionc') ?></h2>
            <?php if(tr_posts_field('infos_selectionc')): ?>
                <div class="uk-flex uk-flex-center uk-width-1-1 uk-margin-small">
                    <div class="uk-text-center uk-width-3-4">
                        <?= tr_posts_field('infos_selectionc') ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php $list_selection = tr_posts_field('list_selection'); ?>
            <div class="uk-flex uk-flex-center">
                <ul class="uk-flex-center" uk-tab="connect: #candidat; animation: true;">
                    <?php foreach ($list_selection as $key => $title): ?>
                        <?php $post = get_post($title['selection']); ?>
                        <li><a href="#"><?= tr_posts_field('list_title'); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <ul class="uk-switcher uk-margin" id="candidat">
                <?php foreach ($list_selection as $key => $title_phase): ?>
                    <?php $post_select = get_post($title_phase['selection']); ?>
                    <li>

                        <div class="uk-child-width-1-2 uk-child-width-1-4@l uk-flex uk-flex-center uk-grid-small" uk-height-match="div > .uk-card" uk-grid>
                            <?php $list_candidats = tr_posts_field('list_candidats', $post_select->ID) ?>
                            <?php foreach ($list_candidats as $candidat): ?>
                            <div>
                                <div class="uk-card uk-card-default">
                                    <div class="uk-card-media-top uk-cover-container uk-text-center">
                                        <div class="uk-height-1-1 uk-inline-clip uk-transition-toggle uk-flex uk-flex-middle uk-flex-center">
                                            <img src="<?= get_the_post_thumbnail_url($candidat['candidat'], 'fulll') ?>" class="uk-transition-scale-up uk-transition-opaque uk-width-auto" alt="" width="266" height="300" />
                                        </div>
                                    </div>
                                    <div class="uk-card-body uk-card-small">
                                        <h3 class="uk-h4 font-flavour uk-text-center uk-margin-remove"><?= get_the_title($candidat['candidat']); ?> </h3>
                                        <?php if(!tr_options_field('options.vote_active')): ?>
                                            <p class="uk-text-center uk-text-xsmall uk-margin-small-top">
                                                Code de vote du candidate <br> <strong><?= $candidat['codevote'] ?></strong>
                                            </p>
                                        <?php else: ?>

                                            <a href="#" data-candidat="<?= $candidat['candidat'] ?>" data-selection="<?= $post_select->ID ?>" class="uk-button uk-width-1-1 uk-button-small uk-button-sign-in uk-button-primary uk-margin-top fb-vote" >
                                                Votez-moi
                                            </a>

                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>

                        </div>


                    </li>

                <?php endforeach; ?>
            </ul>

        </div>
    </div>

<?php endif; ?>
<?php endwhile; ?>

<?php get_footer(); ?>