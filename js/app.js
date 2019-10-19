jQuery.fn.extend({
  treed: function (o) {

    var openedClass = 'glyphicon-minus-sign';
    var closedClass = 'glyphicon-plus-sign';

    if (typeof o != 'undefined') {
      if (typeof o.openedClass != 'undefined') {
        openedClass = o.openedClass;
      }
      if (typeof o.closedClass != 'undefined') {
        closedClass = o.closedClass;
      }
    };

    //initialize each of the top levels
    var tree = jQuery(this);
    tree.addClass("tree");
    tree.find('li').has("ul").each(function () {
      var branch = jQuery(this); //li with children ul
      branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
      branch.addClass('branch');
      branch.on('click', function (e) {
        if (this == e.target) {
          var icon = jQuery(this).children('i:first');
          icon.toggleClass(openedClass + " " + closedClass);
          jQuery(this).children().children().toggle();
        }
      })
      branch.children().children().toggle();
    });
    //fire event from the dynamically added icon
    tree.find('.branch .indicator').each(function () {
      jQuery(this).on('click', function () {
        jQuery(this).closest('li').click();
      });
    });
    //fire event to open branch if the li contains an anchor instead of text
    tree.find('.branch>a').each(function () {
      jQuery(this).on('click', function (e) {
        jQuery(this).closest('li').click();
        e.preventDefault();
      });
    });
    //fire event to open branch if the li contains a button instead of text
    tree.find('.branch>button').each(function () {
      jQuery(this).on('click', function (e) {
        jQuery(this).closest('li').click();
        e.preventDefault();
      });
    });
  }
});

//Initialization of treeviews
jQuery('.tree2').treed({
  openedClass: 'far fa-minus-square',
  closedClass: 'far  fa-plus-square'
});

jQuery(document).ready(function ($) {
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

jQuery(document).ready(function () {

  window.initDataTableMember = function () {

    var settings = {
      "destroy": true,
      "scrollCollapse": true,
      "searching": true,
      "language": {
        "processing": "Traitement en cours...",
        "search": "",
        "searchPlaceholder": "Recherche ....",
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
      "pageLength": 10,
      "columnDefs": [{
        "orderable": false,
        "searchable": false,
        "targets": 2
      },
      {
        "orderable": true,
        "searchable": false,
        "targets": 1
      }
      ]

    }

    jQuery('.dataTableMember').dataTable(settings);

    var settings2 = {
      "destroy": true,
      "scrollCollapse": true,
      "searching": false,
      "language": {
        "processing": "Traitement en cours...",
        "search": "",
        "searchPlaceholder": "Recherche ....",
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
      "pageLength": 10,

    }

    jQuery('.dataTableMember2').dataTable(settings2);
  }

  initDataTableMember();


  jQuery('body').on('click', '.modal', function (e) {
    e.preventDefault();
    var url = jQuery(this).attr('href');

    UIkit.modal(jQuery('#modal')).hide();
    UIkit.modal(jQuery('#modal')).show();

    jQuery.ajax({
      method: "GET",
      url: url
    })
      .done(function (msg) {
        jQuery('#modal .uk-body-custom').html(msg);
      });

  });


  jQuery('#modal').on('hide', function () {
    jQuery('#modal .uk-body-custom').html('<div class="uk-text-center uk-height-1-1 uk-flex-middle uk-padding"><div uk-spinner></div><h1 style="color: #000;" class="uk-margin-remove">Chargement</h1></div>');
  });

  jQuery("body").on('click', 'a', function () {
    window.onbeforeunload = null;
  });


});