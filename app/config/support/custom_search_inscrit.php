<?php

/**
 * Modification de la taille des columns
 */

use App\Models\Candidat;

add_action('admin_head', 'my_column_width');

function my_column_width() {
    echo '<style type="text/css">';
    echo '.column-year_participe { width:150px !important; overflow:hidden }';
    echo '</style>';
}


/**
 *
 * Modification des requettes d'un tableau
 *
 */

//join postmeta for search
//add_filter( 'posts_join' , function($join){
//    global $wpdb;
//    if(is_search() && is_admin())
//    {
//        $join .= " INNER JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ";
//    }
//    return $join;
//});

//add_filter( 'posts_fields', 'filter_function_name_9860', 10, 2 );
//function filter_function_name_9860( $fields ){
//    // filter...
//
//    if(is_search() && is_admin() && $_GET['post_type'] == 'inscrit')
//    {
//
//        var_dump($fields);
//        die();
//
//
//    }
//
//    return $fields;
//}

//search [your_postmeta_key] for search string
//add_filter( 'posts_where', function( $where )
//{
//    global $wpdb;
//    if(is_search() && is_admin() && $_GET['post_type'] == 'inscrit')
//    {
//        $searchstring = '%' . $wpdb->esc_like( $_GET['s'] ) . '%';
//
//        //search [your_postmeta_key] as well
//        $where .= $wpdb->prepare(" AND ($wpdb->postmeta.meta_key = 'position' AND $wpdb->postmeta.meta_value LIKE %s) ", $searchstring);
//        $where .= $wpdb->prepare(" AND ($wpdb->postmeta.meta_key = 'codeins' AND $wpdb->postmeta.meta_value LIKE %s) ", $searchstring);
//        $where .= $wpdb->prepare(" OR ($wpdb->postmeta.meta_key = 'year_participe' AND $wpdb->postmeta.meta_value LIKE %s) ", $searchstring);
//
//        if(isset( $_GET['slug'] ) && $_GET['slug'] !='all') {
//
//            $year_user = Date('Y') - intval($_GET['slug']);
//
//            $time_start = strtotime('01/01/'.$year_user);
//            $year_start = date('Y-m-d',$time_start);
//
//            $time_end = strtotime('31/12/'.$year_user);
//            $year_end = date('Y-m-d', $time_end);
//
//            $where .= $wpdb->prepare(" AND ($wpdb->postmeta.meta_key = 'datenais' AND $wpdb->postmeta.meta_value BETWEEN DATE('2001-01-01') AND DATE('2001-12-31')) ", $year_user);
////            var_dump($where);
////            die();
//        }
//    }
//    return $where;
//});

//group by post ID
//add_filter( 'posts_groupby', function ($groupby, $query) {
//
//    global $wpdb;
//
//    if(is_search() && is_admin())
//    {
//        $groupby = "{$wpdb->posts}.ID";
//    }
//    return $groupby;
//
//}, 10, 2 );


/**
 *
 * Afficher le message de filtre
 */

//add_action( 'restrict_manage_posts', 'wisdom_filter_tracked_plugins' );
function wisdom_filter_tracked_plugins() {

    global $typenow;
    global $wp_query;

    if ( $typenow == 'inscrit' ) { // Your custom post type slug

        $plugins = []; // Options for the filter select field

        $inscrit = new Candidat();
        $inscrits = $inscrit->findAll()->published()->get();

        if($inscrits):
            foreach ($inscrits as $ins):

                $date = tr_posts_field('year_participe', $ins->ID);

                if(!in_array($date, $plugins)):
                    $plugins[] = $date;
                endif;

            endforeach;
        endif;

        $current_plugin = '';
        if( isset( $_GET['slug'] ) ) {
            $current_plugin = $_GET['slug']; // Check if option has been selected
        } ?>
        <select name="slug" id="slug">
            <option value="all" <?php selected( 'all', $current_plugin ); ?>>Toutes les ages</option>
            <option value="18" <?php selected( '18', $current_plugin ); ?>>18 ans</option>
            <option value="19" <?php selected( '19', $current_plugin ); ?>>19 ans</option>
            <option value="20" <?php selected( '20', $current_plugin ); ?>>20 ans</option>
            <option value="21" <?php selected( '21', $current_plugin ); ?>>21 ans</option>
            <option value="22" <?php selected( '22', $current_plugin ); ?>>22 ans</option>
            <option value="23" <?php selected( '23', $current_plugin ); ?>>23 ans</option>
            <option value="24" <?php selected( '24', $current_plugin ); ?>>24 ans</option>
            <option value="25" <?php selected( '25', $current_plugin ); ?>>25 ans</option>
<!--            --><?php //foreach( $plugins as $key=>$value ) { ?>
<!--                <option value="--><?php //echo esc_attr( $value ); ?><!--" --><?php //selected( $value, $current_plugin ); ?><!----><?php //echo $value; ?><!--</option>-->
<!--            --><?php //} ?>
        </select>
        <?php
            $current_plugin_year = '';
            if( isset( $_GET['slug-year'] ) ) {
                $current_plugin_year = $_GET['slug-year']; // Check if option has been selected
            }
        ?>

        <select name="slug-year" id="slug-year">
            <option value="all" <?php selected( 'all', $current_plugin_year ); ?>>Toutes les années</option>
            <?php foreach( $plugins as $key=>$value ) { ?>
                <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $current_plugin_year ); ?>><?php echo esc_attr( $value ); ?></option>
            <?php } ?>
        </select>
    <?php }
}



/**
 * @param $query
 * Fonction permettant de modifier le resultat de recherche par filtre: 2eme methode
 */

//add_filter( 'parse_query', 'wisdom_sort_plugins_by_slug' );
function wisdom_sort_plugins_by_slug( $query ) {

    global $pagenow, $wpdb;
    // Get the post type

    $post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';


    if ( is_admin() && $post_type == 'inscrit') {

        $queryParamsCounter = 0;

        if(isset( $_GET['slug'] ) && $_GET['slug'] != 'all'){

            $year_user = Date('Y') - intval($_GET['slug']);
            $queryParamsCounter++;

        }

        if(isset( $_GET['slug-year'] ) && $_GET['slug-year'] != 'all'){

            $year = $_GET['slug-year'];
            $queryParamsCounter++;

        }

        if(isset( $_GET['s'] ) && !empty($_GET['s'])){

            $search = $_GET['s'];
            $queryParamsCounter++;

        }


        $meta_query = array();

        if ($queryParamsCounter > 1) {
            $meta_query['relation'] = 'AND';
        }


        if(isset($year_user)){
            $meta_query[] = array(
                'key' => 'datenais_format',
                'value' => array((string)$year_user.'-01-01', (string)$year_user.'-12-31'),
                'compare' => 'BETWEEN',
                'type' => 'DATE'
            );
        }

        if(isset($year)){
            $meta_query[] = array(
                'key' => 'year_participe',
                'value' => $year
            );
        }

        if(isset($search)){

            $query->query_vars['s'] = '';

            $search_query = array();
            $search_query['relation'] = 'OR';
            $search_query[] = array(
                'key' => 'codeins',
                'value' => $search,
                'compare' => 'LIKE'
            );

            $search_query[] = array(
                'key' => 'position',
                'value' => $search,
                'compare' => 'LIKE'
            );

            $search_query[] = array(
                'key' => 'nom',
                'value' => $search,
                'compare' => 'LIKE'
            );

            $search_query[] = array(
                'key' => 'prenom',
                'value' => $search,
                'compare' => 'LIKE'
            );

            $meta_query[] = $search_query;
        }

        $query->set('meta_query', $meta_query);

    }
}








