 <nav class="nav-top uk-box-shadow-small uk-background-default uk-visible@l" uk-navbar uk-sticky>
     <?php
        if(tr_options_field('options.online')):
     ?>
    <div class="uk-navbar-left">
        <a href="" class="uk-icon-button uk-margin-small-right uk-icon-facebook" uk-icon="phone"></a>
        <span class="uk-h6 font-flavour uk-margin-remove">Infoline : <?= tr_options_field('options.online') ?></span>
    </div>
     <?php endif ?>
    <div class="uk-navbar-center">

        <div class="uk-navbar-center-left">
            <div>
                <?php
                $defaults = array(
                    'container'       => '',
                    'container_class' => '',
                    'menu_class' => 'uk-navbar-nav',
                    'theme_location' => 'header-left',
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'menu' => ''
                );
                wp_nav_menu($defaults);
                ?>
            </div>
        </div>
        <a class="uk-navbar-item uk-logo" href="/">
            <img src="<?= wp_get_attachment_image_src(tr_options_field('options.logo'), 'full')[0]; ?>" alt="">
        </a>
        <div class="uk-navbar-center-right">
            <div>
                <?php
                $defaults = array(
                    'container'       => '',
                    'container_class' => '',
                    'menu_class' => 'uk-navbar-nav',
                    'theme_location' => 'header-right',
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'menu' => ''
                );
                wp_nav_menu($defaults);
                ?>
            </div>
        </div>

    </div>
    <div class="uk-navbar-right">
        <?php if(tr_options_field('options.lien_instagram')): ?>
            <a href="<?= tr_options_field('options.lien_instagram') ?>" class="uk-icon-button uk-icon-instagram" uk-icon="instagram" target="_blank"></a>
        <?php endif; ?>
        <?php if(tr_options_field('options.lien_youtube')): ?>
            <a href="<?= tr_options_field('options.lien_youtube') ?>" class="uk-icon-button uk-icon-youtube" uk-icon="youtube" target="_blank"></a>
        <?php endif; ?>
        <?php if(tr_options_field('options.lien_facebook')): ?>
            <a href="<?= tr_options_field('options.lien_facebook') ?>" class="uk-icon-button uk-icon-facebook" uk-icon="facebook" target="_blank"></a>
        <?php endif; ?>
    </div>
</nav>
<nav class="uk-box-shadow-small uk-background-default uk-hidden@l" uk-navbar="mode: click;" uk-sticky>
    <div class="uk-navbar-left">
        <a class="uk-navbar-toggle" uk-navbar-toggle-icon href="#" uk-toggle="target: #offcanvas-nav-primary"></a>
    </div>
    <div class="uk-navbar-center">
        <a class="uk-navbar-item uk-logo-mobile" href="<?= home_url() ?>">
            <img src="<?= wp_get_attachment_image_src(tr_options_field('options.logo'), 'full')[0]; ?>" alt="">
        </a>
    </div>
</nav>
<div id="offcanvas-nav-primary" uk-offcanvas="overlay: true" class="uk-offcanvas">
    <div class="uk-offcanvas-bar uk-flex uk-flex-column">
        <a class="uk-navbar-item uk-logo" href="<?= home_url() ?>">
            <img src="<?= wp_get_attachment_image_src(tr_options_field('options.logo'), 'full')[0]; ?>" alt="">
        </a>
        <?php
        $defaults = array(
            'container'       => '',
            'container_class' => '',
            'menu_class' => 'uk-nav-primary uk-nav-default uk-nav-parent-icon uk-margin-auto-vertical uk-nav',
            'theme_location' => 'header-mobile',
            'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'menu' => ''
        );
        wp_nav_menu($defaults);
        ?>
    </div>
</div>

