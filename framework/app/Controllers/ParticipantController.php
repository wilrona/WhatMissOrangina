<?php
namespace App\Controllers;


use App\Models\Participant;
use TypeRocket\Controllers\WPPostController;

class ParticipantController extends WPPostController
{
    protected $modelClass = Participant::class;

    public function update($id = null)
    {
        $this->validation = [
            'telephone' => 'required'
        ];

        $post = $this->model->findById( $id );
        $fields = $this->request->getFields();

        if(isset($fields['post_status_old']) && $fields['post_status_old'] == 'auto-draft'):
            if($this->invalid()){
                $post->delete();
                wp_die('Des champs obligatoires n\'ont pas été renseignés. Ces champs sont représentés par (<span style="color: red;">*</span>). <br><br> <a href="'.tr_redirect()->back()->withFields($fields)->url.'">Retour</a>');
            }else{

                if (empty($post->post_title)):

                    if(!$fields['nom'] && !$fields['prenom']):
                        $post->post_title = $fields['telephone'];
                    else:
                        if($fields['nom']):
                            $post->post_title = $fields['nom'];
                        endif;
                        if ($fields['prenom']) {
                            if($fields['nom']):
                                $post->post_title .= ' ' . $fields['prenom'];
                            else:
                                $post->post_title = $fields['prenom'];
                            endif;
                        }
                    endif;
                endif;

                if(!tr_posts_field('last_activity', $id)):
                    update_post_meta($id, 'last_activity', date('Y-m-d H:i:s'));
                endif;

                if($post->post_date_gmt){
                    update_post_meta($id, 'date_save', $post->post_date_gmt);
                }

                parent::update($id);

            }
        else:

            if($post->post_status == 'publish'):

                if($this->invalid()){

                    $post->post_title = $fields['post_title_old'];
                    $post->save();
                    wp_die('Des champs obligatoires n\'ont pas été renseigné. Ces champs sont représentés par (<span style="color: red;">*</span>). <br><br> <a href="'.tr_redirect()->back()->withFields($fields)->url.'">Retour</a>');

                }else{

                    if (empty($post->post_title)):

                        if(!$fields['nom'] && !$fields['prenom']):
                            $post->post_title = $fields['telephone'];
                        else:
                            if($fields['nom']):
                                $post->post_title = $fields['nom'];
                            endif;
                            if ($fields['prenom']) {
                                if($fields['nom']):
                                    $post->post_title .= ' ' . $fields['prenom'];
                                else:
                                    $post->post_title = $fields['prenom'];
                                endif;
                            }
                        endif;

                    endif;

                    parent::update($id);
                }

            endif;
        endif;

    }
}
