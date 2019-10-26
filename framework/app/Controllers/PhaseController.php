<?php
namespace App\Controllers;

use App\Models\Phase;
use TypeRocket\Controllers\WPPostController;

class PhaseController extends WPPostController
{
    protected $modelClass = Phase::class;

    public function update($id = null)
    {

        $this->validation = [
            'statut' => 'required',
            'list_candidats' => 'required'
        ];

        $post = $this->model->findById( $id );
        $fields = $this->request->getFields();


        if(isset($fields['post_status_old']) && $fields['post_status_old'] == 'auto-draft'):

            if($this->invalid()){

                $post->delete();
                wp_die('Des champs obligatoires n\'ont pas été renseigné. Ces champs sont représentés par (<span style="color: red;">*</span>). <br><br> <a href="'.tr_redirect()->back()->withFields($fields)->url.'">Retour</a>');

            }else{

                if(empty($post->post_title)){
                    $post->post_title = (string)date('d-m-y');
                }


                if($fields['statut'] === 'active'):

                    $args = [
                        'post_type' => 'phase',
                        'meta_query' => array(
                            array(
                                'key' => 'statut',
                                'value' => 'active',
                                'compare' => '='
                            )
                        )
                    ];

                    $phase = query_posts($args);
//                    wp_reset_query();

                    if($phase):
                        update_post_meta($phase[0]->ID, 'statut', 'desactive');
                    endif;

                endif;

                update_post_meta($id, 'list_candidats', $fields['list_candidats']);

                parent::update($id);

            }

        else:

            if($post->post_status == 'publish'):

                if($this->invalid()){

                    $post->post_title = $fields['post_title_old'];
                    $post->save();
                    wp_die('Des champs obligatoires n\'ont pas été renseigné. Ces champs sont représentés par (<span style="color: red;">*</span>). <br><br> <a href="'.tr_redirect()->back()->withFields($fields)->url.'">Retour</a>');

                }else{

                    if(empty($post->post_title)){
                        $post->post_title = (string)date('d-m-y');
                    }

                    if($fields['statut'] === 'active'):

                        $args = [
                            'post_type' => 'phase',
                            'meta_query' => array(
                                array(
                                    'key' => 'statut',
                                    'value' => 'active',
                                    'compare' => '='
                                )
                            )
                        ];

                        $phase = query_posts($args);
//                        wp_reset_query();

                        if($phase):
                            update_post_meta($phase[0]->ID, 'statut', 'desactive');
                        endif;

                    endif;

                    update_post_meta($id, 'list_candidats', $fields['list_candidats']);

                    parent::update($id);

                }

            endif;
        endif;

    }

}
