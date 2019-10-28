<?php
namespace App\Controllers;

use App\Models\Session;
use App\Models\Vote;
use TypeRocket\Controllers\Controller;
use TypeRocket\Http\Response;

class VoteController extends Controller
{
    protected $modelClass = Vote::class;

    public function manager(){

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

//        $phase_candidat = tr_posts_field('list_candidats', $phase[0]->ID);
//
//        $user_id = array();
//        $numero = array();
//
//        foreach ($phase_candidat as $year):
//            $user_id[] = $year['candidat'];
//            $numero[$year['candidat']] = $year['codevote'];
//        endforeach;

//        $candidats_vote = tr_query()->table('wp_posts')
//                ->select('wp_posts.*', 'SUM(wp_miss_vote.point) as vote')
//                ->join('wp_miss_vote', 'wp_miss_vote.idcandidat', '=', 'wp_posts.ID')
//                ->where('wp_posts.ID', 'IN', $user_id)
//                ->groupBy('wp_posts.ID')
//                ->findAll()->orderBy('vote', 'DESC')->get();

        $phase_candidat = tr_posts_field('list_candidats', $phase[0]->ID);

        $all_vote = tr_query()->table('wp_miss_vote')->select('SUM(point) as vote')->where('idphase', '=', $phase[0]->ID)->get();
        $total = $all_vote[0]->vote;


        return tr_view('inscrit.manager', ['phase' => $phase[0], 'candidats' => $phase_candidat, 'total' => $total]);

    }


    public function anonyme_vote(){

        $field = $this->request->getFields();

        foreach ($field['candidat'] as $key => $candidat):
            if(intval($field['vote'][$key]) > 0):
                $vote = $this->model;

                $vote->idcandidat = $candidat;
                $vote->idphase = $field['phase'];
                $vote->idetape = tr_options_field('options.sequence_vote');
                $vote->point = $field['vote'][$key];
                $vote->type_vote = 'SITE';

                $vote->save();
            endif;
        endforeach;

        return tr_redirect()->back()->now();

    }

    public function annonce(){

        $current_session = tr_posts_field('current_session', '495');

        $key_current = 0;

        if($current_session):

            $listes = $current_session;

            if($listes):

                foreach ($listes as $key => $liste):
                    if($liste):
                        $key_current = $key;
                    endif;
                endforeach;

            endif;

        endif;
        var_dump($key_current);
    }


}
