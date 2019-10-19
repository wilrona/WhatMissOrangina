<?php

# ajout des elements css et js dans mon template
function themeprefix_bootstrap_modals()
{



    wp_register_script('uikit', get_stylesheet_directory_uri() . '/js/uikit.js', '', '1', true);
    wp_register_script('uikit-icon', get_stylesheet_directory_uri() . '/js/uikit-icons.js', '', '1', true);
    wp_register_script('dataTable', get_stylesheet_directory_uri() . '/js/datatables.js', '', '1', true);
    wp_register_script('dataTableUikit', get_stylesheet_directory_uri() . '/js/dataTables.uikit.min.js', '', '1', true);
    wp_register_script('app', get_stylesheet_directory_uri() . '/js/app.js', '', '1', true);


    wp_register_style('uikit', get_stylesheet_directory_uri() . '/css/uikit.css', '', '', 'all');
    wp_register_style('all', get_stylesheet_directory_uri() . '/css/all.css', '', '', 'all');
    // wp_register_style('dataTable', get_stylesheet_directory_uri() . '/css/dataTables.css', '', '', 'all');
    wp_register_style('dataTableUikit', get_stylesheet_directory_uri() . '/css/dataTables.uikit.css', '', '', 'all');
    wp_register_style('app', get_stylesheet_directory_uri() . '/css/app.css', '', '', 'all');



    wp_enqueue_script('uikit');
    wp_enqueue_script('uikit-icon');
    wp_enqueue_script('dataTable');
    wp_enqueue_script('dataTableUikit');
    wp_enqueue_script('app');

    wp_enqueue_style('uikit');
    wp_enqueue_style('all');
    wp_enqueue_style('dataTable');
    wp_enqueue_style('dataTableUikit');
    wp_enqueue_style('app');
}

add_action('wp_enqueue_scripts', 'themeprefix_bootstrap_modals');

function load_custom_wp_admin_asset()
{
    wp_register_script('admin', get_stylesheet_directory_uri() . '/js/admin.js', '', '1.1', true);
    wp_enqueue_script('admin');
}
add_action('admin_enqueue_scripts', 'load_custom_wp_admin_asset');


// Ajout de select 2 dans l'interface d'administration
function enqueue_select2_jquery()
{
    wp_register_style('select2css', get_stylesheet_directory_uri() . '/css/select2.css', false, '1.0', 'all');
    wp_register_style('uikit', get_stylesheet_directory_uri() . '/css/uikit.css', '', '', 'all');

    wp_register_script('select2', get_stylesheet_directory_uri() . '/js/select2.min.js', '', '1.0', true);
    wp_register_script('uikit', get_stylesheet_directory_uri() . '/js/uikit.js', '', '1', true);
    wp_register_script('uikit-icon', get_stylesheet_directory_uri() . '/js/uikit-icons.js', '', '1', true);


    wp_enqueue_style('uikit');
    wp_enqueue_style('select2css');


    wp_enqueue_script('uikit');
    wp_enqueue_script('uikit-icon');
    wp_enqueue_script('select2');
}
add_action('admin_enqueue_scripts', 'enqueue_select2_jquery');

function select2jquery_inline()
{
    ?>
    <!--	<style type="text/css">-->
    <!--		.select2-container {margin: 0 2px 0 2px;}-->
    <!--		.tablenav.top #doaction, #doaction2, #post-query-submit {margin: 0px 4px 0 4px;}-->
    <!--	</style>-->
    <script>
        jQuery(document).ready(function($) {
            if ($('select.select').length > 0) {
                $('select.select').select2();
            }
        });
    </script>
<?php
}
add_action('admin_head', 'select2jquery_inline');



function select2jquery_inline_frontend()
{
    ?>
    <!--    	<style type="text/css">-->
    <!--    		.select2-container {margin: 0 2px 0 2px;}-->
    <!--    		.tablenav.top #doaction, #doaction2, #post-query-submit {margin: 0px 4px 0 4px;}-->
    <!--            .select2 {-->
    <!--                width:100%!important;-->
    <!--            }-->
    <!--    	</style>-->
    <script>
        jQuery(document).ready(function($) {
            if ($('select.selected').length > 0) {
                $('select.selected').select2();
                //                $( document.body ).on( "click", function() {
                //                    $( 'select' ).select2();
                //                });
            }

            if ($('select.selectedComp').length > 0) {
                $('select.selectedComp').select2({
                    maximumSelectionLength: 10
                });
                //                $( document.body ).on( "click", function() {
                //                    $( 'select' ).select2();
                //                });
            }
        });

        jQuery(document).ready(function() {

            window.initDataTable = function() {

                var settings = {
                    "destroy": true,
                    "scrollCollapse": true,
                    "searching": true,
                    "language": {
                        "processing": "Traitement en cours...",
                        "search": "",
                        "searchPlaceholder": "Filtre par annee",
                        "lengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
                        "info": "Affichage de  _START_ &agrave; _END_ sur _TOTAL_ ",
                        "infoEmpty": "Affichage de 0 &agrave; 0 sur 0 ",
                        "infoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                        "infoPostFix": "",
                        "loadingRecords": "Chargement en cours...",
                        "zeroRecords": "<h3 class='uk-margin-top uk-margin-bottom'>Aucun &eacute;l&eacute;ment &agrave; afficher</h3>",
                        "emptyTable": "<h3 class='uk-margin-top uk-margin-bottom'>Aucune donn&eacute;e disponible</h3>",
                        "paginate": {
                            "first": "",
                            "previous": "",
                            "next": "",
                            "last": ""
                        },
                        "aria": {
                            "sortAscending": ": activer pour trier la colonne par ordre croissant",
                            "sortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                        }
                    },
                    "pageLength": 25,
                    "columnDefs": [{
                            "orderable": false,
                            "searchable": false,
                            "targets": 2
                        },
                        {
                            "orderable": true,
                            "searchable": false,
                            "targets": 0
                        }
                    ]

                }

                jQuery('.dataTable').dataTable(settings);
            }

            initDataTable();


        });
    </script>
<?php
}
add_action('wp_enqueue_scripts', 'enqueue_select2_jquery');
add_action('wp_footer', 'select2jquery_inline_frontend');


function datepicker()
{
    wp_register_style('datepickercss', get_stylesheet_directory_uri() . '/css/datepicker.css', false, '1.0', 'all');
    wp_register_script('datepicker', get_stylesheet_directory_uri() . '/js/datepicker.js', '', '1.0', true);
    wp_register_script('datepicker.fr', get_stylesheet_directory_uri() . '/js/datepicker.fr-FR.js', '', '1.0', true);
    wp_register_script('repeater', get_stylesheet_directory_uri() . '/js/jquery.repeater.js', array('jquery'), '1.0', true);
    wp_enqueue_style('datepickercss');
    wp_enqueue_script('datepicker');
    wp_enqueue_script('datepicker.fr');
    wp_enqueue_script('repeater');
}

add_action('wp_enqueue_scripts', 'datepicker');
add_action('admin_enqueue_scripts', 'datepicker');

function datepicker_script()
{
    ?>

    <script>
        jQuery(document).ready(function($) {
            if ($('.datepicker').length > 0) {
                $('.datepicker').datepicker({
                    language: 'fr-FR',
                    format: 'dd/mm/yyyy',
                    autoHide: true
                });
            }

            if ($('.datepicker_birth').length > 0) {
                $('.datepicker_birth').datepicker({
                    language: 'fr-FR',
                    format: 'dd/mm/yyyy',
                    startView: 2,
                    autoHide: true
                });
            }

            if ($('.datepicker_start').length > 0) {
                $('.datepicker_start').datepicker({
                    language: 'fr-FR',
                    format: 'dd/mm/yyyy',
                    startView: 2,
                    autoHide: true,
                    pick: function(date) {
                        $date_end = $(date.currentTarget).parent().next().find('input.datepicker_end');
                        $reforme_date_start_show = ('0' + date.date.getDate()).slice(-2) + '/' + ('0' + (date.date.getMonth() + 1)).slice(-2) + '/' + date.date.getFullYear();
                        $reforme_date_start = '' + ('0' + (date.date.getMonth() + 1)).slice(-2) + '/' + date.date.getDate() + '/' + date.date.getFullYear();

                        if ($date_end) {

                            $reforme_date_end = "";

                            if ($date_end.val() === '') {
                                $date_end.val($reforme_date_start_show);
                            } else {
                                date_end_js = $date_end.val().split('/');
                                $reforme_date_end = (date_end_js[1]) + '/' + date_end_js[0] + '/' + date_end_js[2];
                            }

                            if (new Date($reforme_date_start) >= new Date($reforme_date_end)) {
                                $date_end.val($reforme_date_start_show);
                            }
                        }
                    }
                });
            }

            if ($('.datepicker_end').length > 0) {
                $('.datepicker_end').datepicker({
                    language: 'fr-FR',
                    format: 'dd/mm/yyyy',
                    startView: 2,
                    autoHide: true,
                    pick: function(date) {
                        $date_start = $(date.currentTarget).parent().prev().find('input.datepicker_start');
                        $reforme_date_end_show = ('0' + date.date.getDate()).slice(-2) + '/' + ('0' + (date.date.getMonth() + 1)).slice(-2) + '/' + date.date.getFullYear();
                        $reforme_date_end = '' + ('0' + (date.date.getMonth() + 1)).slice(-2) + '/' + date.date.getDate() + '/' + date.date.getFullYear();

                        if ($date_start) {

                            $reforme_date_start = "";

                            if ($date_start.val() === '') {
                                $date_start.val($reforme_date_end_show);
                            } else {
                                date_start_js = $date_start.val().split('/');
                                $reforme_date_start = (date_start_js[1]) + '/' + date_start_js[0] + '/' + date_start_js[2];
                            }


                            if (new Date($reforme_date_end) <= new Date($reforme_date_start)) {
                                $date_start.val($reforme_date_end_show);
                            }
                        }
                    }
                });
            }

            if ($('.datepicker_year_start').length > 0) {
                $('.datepicker_year_start').datepicker({
                    language: 'fr-FR',
                    format: 'yyyy',
                    startView: 2,
                    autoHide: true,
                    pick: function(date) {

                        if ($('.datepicker_year_end')) {

                            if ($('.datepicker_year_end').val() === '') {
                                $('.datepicker_year_end').val(date.date.getFullYear() + 1);
                            }

                            if (parseFloat(date.date.getFullYear()) >= parseInt($('.datepicker_year_end').val())) {

                                $('.datepicker_year_end').val(date.date.getFullYear() + 1);
                            }
                        }

                    }
                })
            }

            if ($('.datepicker_year_end').length > 0) {
                $('.datepicker_year_end').datepicker({
                    language: 'fr-FR',
                    format: 'yyyy',
                    startView: 2,
                    autoHide: true,
                    pick: function(date) {
                        if ($('.datepicker_year_start')) {

                            if ($('.datepicker_year_start').val() === '') {
                                $('.datepicker_year_start').val(date.date.getFullYear() - 1);
                            }

                            if (parseFloat(date.date.getFullYear()) <= parseInt($('.datepicker_year_start').val())) {

                                $('.datepicker_year_start').val(date.date.getFullYear() - 1);
                            }
                        }
                    }
                })
            }



            // Listen for input event on numInput.
            $('#number').onkeydown = function(e) {
                if (!((e.keyCode > 95 && e.keyCode < 106) ||
                        (e.keyCode > 47 && e.keyCode < 58) ||
                        e.keyCode == 8)) {
                    return false;
                }
            }


            $('.repeater').repeater({
                show: function() {
                    $(this).slideDown();
                },
                hide: function(remove) {
                    if (confirm('Etes vous sure de supprimer cet élément ?')) {
                        $(this).slideUp(remove);
                    }
                }
            });
        });
    </script>


<?php

}

add_action('wp_footer', 'datepicker_script');

function datepicker_script_admin()
{
    ?>

    <script>
        jQuery(document).ready(function($) {
            if ($('.datepicker').length > 0) {
                datepicker()
            }

            $('.button .add').on('click', function () {
                console.log('clicked');
                datepicker();
            });

            function datepicker(){
                $('.datepicker').datepicker({
                    language: 'fr-FR',
                    format: 'dd/mm/yyyy',
                    autoHide: true
                });
            }
        });
    </script>


<?php

}

add_action('admin_footer', 'datepicker_script_admin');
