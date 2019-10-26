<?php
namespace App\Controllers;


use App\Models\Ticket;
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

            if($post->post_status == 'publish'):

                if($fields['post_title_old']):
                    $post->post_title = $fields['post_title_old'];
                    $post->save();
                endif;

            endif;

        endif;

        parent::update($id);

    }

    public function generer($point){

        if($point):

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

                endif;

            endfor;

        endif;

        return tr_redirect()->back()->now();

    }



}
