<?php


/**
 * Function to format date
 * @param $format
 * @param null $timestamp
 * @param null $echo
 * @return string
 */

function sky_date_french($format, $timestamp = null, $echo = null)
{
    $param_D = array('', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim');
    $param_l = array('', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
    $param_F = array('', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
    $param_M = array('', 'Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc');
    $return = '';
    if (is_null($timestamp)) {
        $timestamp = mktime();
    }
    for ($i = 0, $len = strlen($format); $i < $len; $i++) {
        switch ($format[$i]) {
            case '\\': // fix.slashes
                $i++;
                $return .= isset($format[$i]) ? $format[$i] : '';
                break;
            case 'D':
                $return .= $param_D[date('N', $timestamp)];
                break;
            case 'l':
                $return .= $param_l[date('N', $timestamp)];
                break;
            case 'F':
                $return .= $param_F[date('n', $timestamp)];
                break;
            case 'M':
                $return .= $param_M[date('n', $timestamp)];
                break;
            default:
                $return .= date($format[$i], $timestamp);
                break;
        }
    }
    if (is_null($echo)) {
        return $return;
    } else {
        echo $return;
    }
}


/**
 * Function to create and display error and success messages
 * @access public
 * @param string session name
 * @param string message
 * @param string display class
 * @return string message
 */
function flash($name = '', $message = '', $class = 'uk-alert-success')
{
    //We can only do something if the name isn't empty
    if (!empty($name)) {
        //No message, create it
        if (!empty($message) && empty($_SESSION[$name])) {
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        }
        //Message exists, display it
        elseif (!empty($_SESSION[$name]) && empty($message)) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : 'uk-alert-success';
            echo '<div class="' . $class . '" uk-alert> <a class="uk-alert-close" uk-close></a> <p>' . $_SESSION[$name] . '</p></div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}




/**
 *
 * Function to paginate query in fronted
 *
 * @param string $pages
 * @param int $range
 *
 *
 */
function kriesi_pagination($pages = '', $range = 4)
{
    $showitems = ($range * 2)+1;

    global $paged;
    if(empty($paged)) $paged = 1;

    if($pages == '')
    {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if(!$pages)
        {
            $pages = 1;
        }
    }

    if(1 != $pages)
    {
        echo "<ul class=\"uk-pagination uk-flex-center\">";
        if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."'><span uk-pagination-previous></span><span uk-pagination-previous></span></a></li>";
        if($paged > 1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged - 1)."'><span uk-pagination-previous></span></a></li>";

        for ($i=1; $i <= $pages; $i++)
        {
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
            {
                echo ($paged == $i)? "<li class=\"uk-active\"><span>".$i."</span></li>":"<li><a href='".get_pagenum_link($i)."' >".$i."</a></li>";
            }
        }

        if ($paged < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged + 1)."'><span uk-pagination-next></span></a></li>";
        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($pages)."'><span uk-pagination-next></span><span uk-pagination-next></span></a></li>";
        echo "</ul>\n";
    }
}

/**
 * Function pour afficher le montant avec K et M a la fin
 * @param $val
 * @return float|int|string
 */
function displayMontant($val) {
    if( $val < 1000 ) return $val;
    $val = $val/1000;
    if( $val < 1000 ) return "${val} K";
    $val = $val/1000;
    return "${val} M";
}

/**
 *
 * Function to set number of views in post or page
 * @param $postID
 */

function SetPostViews($postID) {
    $meta_key = 'post_views_count'; //La clef, ou slug, de la méta-donnée
    $count = get_post_meta($postID, $meta_key, true); //Extraction de la valeur, qui est finalement un compteur
    if($count==''): //Si le compte est nul, la méta-donné n'existe pas, on va donc la créer
        $count = 0; //Initialisation à 0
        delete_post_meta($postID, $meta_key); //Simple précaution : si la méta-donnée existait déjà pour un autre usage exotique
        add_post_meta($postID, $meta_key, '1'); //On ajoute la méta-donné
    else:
        $count++; // Si la méta-donnée existe, on l'incrémente
        update_post_meta($postID, $meta_key, $count); //Et on met à jour
    endif;
}

/**
 * @param $date
 */

function date_naiss($date){
    list($jour, $mois, $annee) = preg_split('[/]', $date);
    $today['mois'] = date('n');
    $today['jour'] = date('j');
    $today['annee'] = date('Y');
    $annees = $today['annee'] - $annee;
    if ($today['mois'] <= $mois) {
        if ($mois == $today['mois']) {
            if ($jour > $today['jour'])
                $annees--;
        }
        else
            $annees--;
    }

    echo $annees;
}

function search($array, $key, $value)
{
    $results = array();

    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, search($subarray, $key, $value));
        }
    }

    return $results;
}
