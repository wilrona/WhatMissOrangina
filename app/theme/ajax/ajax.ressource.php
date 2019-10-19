<?php

add_action('wp_ajax_load_ressource', 'load_ressource_callback');
add_action('wp_ajax_nopriv_load_ressource', 'load_ressource_callback');



function load_ressource_callback()
{
  check_ajax_referer('load_ressource_security', 'security');

  $term_id = $_POST['current_rubrique'];

  $args = array(
    'post_status' => 'publish',
    'post_type' => 'ressource',
    'posts_per_page' => '-1',
    'tax_query' => array(
      array(
        'taxonomy' => 'rubrique',
        'field' => 'id',
        'terms' => $term_id,
      )
    )
  );

  $query = new WP_Query($args);

  ?>

  <div class="uk-overflow-auto uk-margin-medium-top">

    <table class="uk-table uk-table-small uk-table-middle uk-table-divider dataTable uk-table-striped">
      <thead>
        <tr>
          <th>Nom du fichier</th>
          <th style="width:20%">Annee</th>
          <th style="width:10%"></th>
        </tr>
      </thead>
      <tbody>

        <?php
        while ($query->have_posts()) : $query->the_post();

          ?>

          <tr>
            <td>
              <?= get_the_title() ?> </br>
              <small>
                <?php $terms = wp_get_post_terms(get_the_ID(), 'rubrique', array("fields" => "all")) ?>


                <?php

                $tax = '';

                foreach ($terms as $term) {

                  if ($term->parent) :
                    $tax .= ' - <b>' . $term->name . '</b>';
                  else :
                    $tax .= '<b>' . $term->name . '</b>';
                  endif;
                }

                echo $tax;
                ?>
              </small>


            </td>
            <td><?= tr_posts_field('annee', get_the_ID()) ?> </td>
            <td>
              <a href="#" uk-toggle="target: #modal" class="uk-button uk-button-primary uk-button-small add_cart"><i class="fas fa-cart-plus"> </i> </a>
            </td>
          </tr>

        <?php endwhile; ?>

      </tbody>
    </table>
  </div>

  <script>
    initDataTable();
  </script>


  <?php


  wp_die();
}



add_action('wp_ajax_load_cart', 'load_cart_callback');
add_action('wp_ajax_nopriv_load_cart', 'load_cart_callback');


function load_cart_callback()
{
  check_ajax_referer('load_cart_security', 'security');

  if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
    $produits = [];
  } else {
    $produits = $_SESSION['panier'];
  }

  $id_current = $_POST['current_item'];
  $remove = $_POST['actions'];

  if ($remove == 'remove') :

    $index = array_search($id_current, $produits);
    unset($produits[$index]);

    $_SESSION['panier'] = $produits;

    echo 'true';

  else :


    $message = 'Votre produit est deja present dans le panier';

    if (!in_array($id_current, $produits)) :

      array_push($produits, $id_current);
      $message = 'Votre produit a ete ajoute avec success';

    endif;

    $_SESSION['panier'] = $produits;

    ?>

    <button class="uk-modal-close-default" type="button" uk-close></button>
    <div class="uk-modal-body">
      <h1 class="uk-h2 uk-text-center"><?= $message ?></h1>
    </div>
    <div class="uk-modal-footer uk-text-right" uk-margin>
      <a href="<?= get_permalink(tr_options_field('options.page_panier')) ?>" class="uk-button uk-button-primary uk-button-small uk-border-rounded">Passer a l'achat</a>
      <button class="uk-button uk-button-danger uk-button-small uk-modal-close uk-border-rounded" type="button">Continuer la selection</button>
    </div>

  <?php

endif;

wp_die();
}
