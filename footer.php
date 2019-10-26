

<?php wp_footer(); ?>


<script src="<?= get_template_directory_uri(); ?>/js/waypoints.js"></script>
<script src="<?= get_template_directory_uri(); ?>/js/jquery.counterup.js"></script>



<script type="application/javascript">

    jQuery(document).ready(function() {

        UIkit.util.ready(function () {

            let bar = document.getElementsByClassName('progress');

            jQuery.each(bar, function ($index, $value) {

                // console.log($value.value);
                let pourcentage = jQuery(this).data('pourcentage');

                var animate = setInterval(function () {

                    $value.value += 5;

                    if ($value.value >= parseFloat(pourcentage)) {
                        clearInterval(animate);
                    }

                }, 1000);
            });

        });



        jQuery('.integers').counterUp({
            delay: 2.5,
            time: 5000
        });

    });

</script>

</body>

</html>
