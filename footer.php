<div class="uk-background-norepeat uk-background-cover uk-background-center-center" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/fond-pied.jpg');">

    <div class="uk-grid-collapse" uk-grid>
        <div class="uk-width-1-4 uk-visible@l uk-height-auto uk-background-norepeat uk-background-contain uk-background-center-left" style="background-image: url('<?php echo get_template_directory_uri(); ?>/img/Miss-orangina.png');">

        </div>
        <div class="uk-width-1-1 uk-width-1-3@l uk-height-auto uk-background-norepeat uk-background-contain uk-background-center-center" style="background-image: url(<?= wp_get_attachment_image_src(tr_options_field('options.footer_image'), 'full')[0]; ?>);">

        </div>
        <div class="uk-width-expand uk-padding uk-padding-remove-horizontal uk-position-relative">
            <div class="uk-margin-left uk-margin-right">
                <h3 class="single-font-flavour uk-text-center uk-h2 uk-text-white">Laissez un message</h3>

                <?php echo do_shortcode(tr_options_field('options.form_contact')); ?>
            </div>
        </div>
        <div class="uk-width-1-1 uk-padding-small uk-flex uk-flex-center" style="border-top: 1px solid #fff;">

            <ul class="uk-subnav uk-subnav-divider uk-flex uk-flex-center">
                <li class="uk-text-white uk-text-lowercase uk-text-small single-font-flavour">All Copyrights Reserved</li>
                <li class="uk-text-white uk-text-lowercase uk-text-small single-font-flavour">2016 - <?= date('Y'); ?></li>
                <li class="single-font-flavour uk-text-white uk-text-small"><a href="#" class="uk-text-white uk-margin-small-right" target="_blank">Accent Com</a>  designed by  <a href="#" class="uk-text-white uk-text-lowercase uk-margin-small-left" target="_blank">Aligodu</a> </li>
            </ul>

        </div>
    </div>


</div>


<?php wp_footer(); ?>

<script>
    // Load the SDK asynchronously
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.async = true;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : "<?= tr_options_field('options.facebook_appid') ? tr_options_field('options.facebook_appid') : '' ?>",
            cookie     : true,
            xfbml      : true,
            version    : "<?= tr_options_field('options.facebook_version') ? tr_options_field('options.facebook_version') : 'v2.8' ?>" // Use whatever version is latest at this time
        });
    };

    function fb_login(){
        FB.login(function(response){
            var grantedScopes = response.authResponse.grantedScopes;

            if (response.status === 'connected') {
                if(grantedScopes.indexOf('email') !== -1 || grantedScopes.indexOf('contact_email') !== -1){

                    window.location.replace('<?php echo get_site_url()."/facebook/connect"; ?>');

                } else {
                    FB.api("/me/permissions", "delete", function(response){
                        alert('Authorization Failed. Email Required.');
                    });
                }
            }
        }, {
            scope: 'public_profile, email',
            return_scopes: true
        });

    };

    function fb_vote(idcandidat, idselection){
        FB.login(function(response){
            var grantedScopes = response.authResponse.grantedScopes;

            if (response.status === 'connected') {
                if(grantedScopes.indexOf('email') !== -1 || grantedScopes.indexOf('contact_email') !== -1){

                    window.location.replace('<?= tr_redirect()->toHome('facebook/vote/', '')->url; ?>'+idcandidat+'/'+idselection);

                } else {
                    FB.api("/me/permissions", "delete", function(response){
                        alert('Authorization Failed. Email Required.');
                    });
                }
            }
        }, {
            scope: 'public_profile, email',
            return_scopes: true
        });
    }

    jQuery('.fb-login').on('click', function(e){
        e.preventDefault();
        fb_login();
    });

    jQuery('.fb-vote').on('click', function(e){
        e.preventDefault();
        fb_vote(jQuery(this).data('candidat'), jQuery(this).data('selection'));
    });
</script>

</body>

</html>