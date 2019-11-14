<?php
namespace App\Controllers;


use App\Models\Ticket;
use Spipu\Html2Pdf\Html2Pdf;
use TypeRocket\Controllers\WPPostController;

class TicketController extends WPPostController
{
    protected $modelClass = Ticket::class;

    public function update($id = null)
    {

        $post = $this->model->findById( $id );
        $fields = $this->request->getFields();

        if(isset($fields['post_status_old']) && $fields['post_status_old'] == 'auto-draft'):

            $post->delete();
            wp_die('Impossible de creer des numeros de serie. Pensez à utiliser la fonction de génération de nouveau numero de serie. <br><br> <a href="'.tr_redirect()->back()->withFields($fields)->url.'">Retour</a>');

        else:

            if(isset($fields['post_status_old']) && $post->post_status == 'publish'):

                if($fields['post_title_old']):
                    $post->post_title = $fields['post_title_old'];
                    $post->save();
                endif;

            endif;

        endif;

        parent::update($id);

    }

    public function generer($point){

        $debut_couche = isset($_GET['souche']) && !empty($_GET['souche']) ? intval($_GET['souche']) : '';

        if($point):


            if($debut_couche):

                $souche = $debut_couche;

                for($j=0; $j<25; $j++):

//                    $chars = array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
//                    $serial = '';
//                    $max = count($chars)-1;
//
//                    for($i=0;$i<3;$i++){
//
//                        $serial .= $chars[rand(0, $max)];
//
//                    }

//                    if(!empty($serial)):

                        $id = wp_insert_post(array(
                            'post_title' => str_pad($debut_couche, 7, 0, STR_PAD_LEFT),
                            'post_type' => 'ticket',
                            'post_status' => 'publish'
                        ));

                        update_post_meta($id, $key = 'serie', $debut_couche);
                        update_post_meta($id, $key = 'souche', $souche);
                        update_post_meta($id, $key = 'used', 'no');
                        update_post_meta($id, $key = 'genered', 'yes');
                        update_post_meta($id, $key = 'point', $point);

                        $debut_couche++;

//                    endif;

                endfor;


            else:

                for($j=0; $j<20; $j++):

                    $chars = array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                    $serial = '';
                    $max = count($chars)-1;

                    for($i=0;$i<6;$i++){

                        $serial .= (!($i % 3) && $i ? '-' : '').$chars[rand(0, $max)];

                    }

                    if(!empty($serial)):

                        $id = wp_insert_post(array(
                            'post_title' => $serial,
                            'post_type' => 'ticket',
                            'post_status' => 'publish'
                        ));

                        $serie = explode("-", $serial);

                        update_post_meta($id, $key = 'serie', $serie[0].''.$serie[1]);
                        update_post_meta($id, $key = 'used', 'no');
                        update_post_meta($id, $key = 'genered', 'yes');
                        update_post_meta($id, $key = 'point', $point);

                        $debut_couche++;

                    endif;

                endfor;

            endif;

        endif;

        return tr_redirect()->back()->now();

    }

    /**
     *  Imprimer les tickets genere non utilisé
     */

    public function printer(){

        ob_start();

        $args = array(
            'post_type' => 'ticket',
            'posts_per_page' => -1
        );
        $args['meta_query'] = array(
            array(
                'key' => 'used',
                'value' => 'no',
                'compare' => '='
            )
        );

        $post = query_posts($args);

        tr_view('pdf.ticket', ['tickets' => $post])::load();

        $content = ob_get_clean();

        http_response_code(200);

        try{
            $pdf = new HTML2PDF('L', 'A4', 'fr');
            $pdf->writeHTML($content);
            $pdf->Output('ticket.pdf');
        }catch (\HTML2PDF_exception $e){
            die($e);
        }

        exit;

    }

    public function correction(){

            $args = array(
                'post_type' => 'ticket',
                'posts_per_page' => -1
            );

            $posts = query_posts($args);

            foreach ($posts as $post):
                var_dump($post->ID);
                update_post_meta($post->ID, 'serie', $post->post_title);
            endforeach;
    }



}
