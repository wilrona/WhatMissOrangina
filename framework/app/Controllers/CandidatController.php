<?php
namespace App\Controllers;

use App\Models\Candidat;
use TypeRocket\Controllers\WPPostController;

class CandidatController extends WPPostController
{
    protected $modelClass = Candidat::class;

    public function update($id = null)
    {

        $this->validation = [
            'nom' => 'required',
            'prenom' => 'required'
        ];

        $post = $this->model->findById( $id );
        $fields = $this->request->getFields();



        if(isset($fields['post_status_old']) && $fields['post_status_old'] == 'auto-draft'):

            if($this->invalid()){

                $post->delete();
                wp_die('Des champs obligatoires n\'ont pas été renseigné. Ces champs sont représentés par (<span style="color: red;">*</span>). <br><br> <a href="'.tr_redirect()->back()->withFields($fields)->url.'">Retour</a>');

            }else{

                if (empty($post->post_title)):
                    $post->post_title = $fields['nom'];
                    if ($fields['prenom']) {
                        $post->post_title .= ' ' . $fields['prenom'];
                    }
                endif;

                $post->update($fields);

            }

        else:

            if($post->post_status == 'publish'):

                if($this->invalid()){

                    $post->post_title = $fields['post_title_old'];
                    $post->save();
                    wp_die('Des champs obligatoires n\'ont pas été renseigné. Ces champs sont représentés par (<span style="color: red;">*</span>). <br><br> <a href="'.tr_redirect()->back()->withFields($fields)->url.'">Retour</a>');

                }else{
                    if (empty($post->post_title)):
                        $post->post_title = $fields['nom'];
                        if ($fields['prenom']) {
                            $post->post_title .= ' ' . $fields['prenom'];
                        }
                    endif;

                    $post->update($fields);
                }

            endif;
        endif;

    }

}
