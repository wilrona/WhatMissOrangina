<?php
namespace App\Controllers;

use App\Models\Candidat;
use App\Models\Participant;
use App\Models\Session;
use App\Models\Ticket;
use App\Models\Vote;
use Psr\Log\NullLogger;
use TypeRocket\Controllers\Controller;
use TypeRocket\Http\Request;
use TypeRocket\Http\Response;

class WhatsappController extends Controller
{
    protected $modelClass = Session::class;

    protected $APIurl;
    protected $token;

    protected $phase;

    protected $formatSession = [
        0 => true,
        1 => false,
        2 => false,
        3 => false,
        4 => false
    ];

    public function bot(){

        $this->APIurl = 'https://eu37.chat-api.com/instance74719/';
        $this->token = '5580xkqy5oszpwt7';

        //get the JSON body from the instance
        $json = file_get_contents('php://input');
        $decoded = json_decode($json,true);


        ob_start();
        var_dump($decoded);
        $input = ob_get_contents();
        ob_end_clean();
        file_put_contents(get_template_directory().'/input_requests.log',$input.PHP_EOL,FILE_APPEND);


        // On indique par whatsapp que l'on a lu le message a l'utilisateur

        // Verifier qu'une phase est active
        // --- Si pas active, on envoie un message d'indisponibilité de vote et d'attendre la prochaine invitation ou session.
        // --- Si active, on envoie un message de bienvenue au concours

        // Verifier que l'utilisateur a une session et prendre celle qui est active
        // --- Si pas de session, on en creer un et on active.
        // --- Si session, on prend les informations des actions

        // On récupère la liste des actions de la session et on cherche le numero de la Key(clé) qui est active.

        // si key == 0 : on envoie un message de bienvenue et le message lui demandant le type de vote. changement de la valeur de la cle active a 1.
        // si key == 1 : on attend la valeur SITE ou HOUSE si aucune valeur on envoie reste sur l'erreur.
        // --- Si l'une des valeurs est ajoutée, on enregistre le candidat dans la phase avec le type de vote et on modifie la phase active a 2
        // --- On envoie le message de la liste des candidats de la phase avec leur numero

        // si key == 2 : on attend le numero d'un candidat, on effectue la recherche d'un candidat dans la phase qui a ce numero
        // --- Si pas de candidat, on envoie un message d'erreur
        // --- Si candidat, on verifie le type de vote du candidat
        // ------ si type == HOUSE, on envoie le message de fin de vote avec le classement et pourcentage du candidat. on passe toute les keys a true et on desactive la session.
        // ------ si type == SITE, on envoie le message demandant les numeros de serie qui doivent etre envoye collé par un underscore. on passe la cle a 3

        // si key == 3 : on attend les numeros de serie dans le format demandee
        // --- Si pas le bon format, on envoie une erreur
        // --- Si bon format, on verifie les codes
        // ----- Si le code existe et est deja utilisé, on le prepare dans un message d'erreur
        // ----- Si le code n'existe pas, on enregistre et celle ci prend la valeur d'un point pour enregistrer le vote
        // ----- Si le code existe et pas encore utilisé, on recherche la valeur du point pour enregistrer le vote

        // On envoie le message d'erreur des codes et le message de fin de vote avec le classement et pourcentage du candidat. on ferme la session.


        $phase = $this->check_phase();

        $type_vote = tr_options_field('options.type_vote');

        $this->phase = $phase;

        if(isset($decoded['messages'])){
            //check every new message
            foreach($decoded['messages'] as $message){

                if(!$message['fromMe']) {

                    $this->readMessage($message['chatId']);

                    $phone = explode('@', $message['author']);

                    $participant = $this->check_participant($phone[0]);

                    $key = $this->session_key($participant);

                    $active_vote = intval(tr_options_field('options.active_vote'));

                    if($active_vote):

                        switch($key){

                            case 0:  {

                                $text = explode(' ',trim($message['body']));

                                switch(mb_strtolower($text[0],'UTF-8')){
                                    case '*start*':
                                    case 'start': {

                                        $participant_id = tr_posts_field('participant_id', $phase->ID);

                                        if(in_array($participant->ID, $participant_id)){ // si le candidat a deja participe

                                            $string = "*Envoyez le numero de votre candidat préféré.*\n\n";

                                            $candidats = tr_posts_field('list_candidats', $phase->ID);

                                            foreach ($candidats as $cand):
                                                $current_candidat = get_post($cand['candidat']);
                                                $string .= "*".$cand['codevote']."* - ".$current_candidat->post_title."\n";
                                            endforeach;

                                            if($type_vote == 'home' || $type_vote == 'both'):
                                                $string .= "\n *NB*: vous ne pouvez que voter un candidat à la fois si votre choix de vote est *HOME*.";
                                            endif;

                                            $this->sendMessage($message['chatId'], $string);

                                            $etape_list = [
                                                0 => false,
                                                1 => false,
                                                2 => true,
                                                3 => false,
                                                4 => false
                                            ];
                                            update_post_meta($participant->ID, 'current_session', serialize($etape_list));

                                        }else{
                                            // On lui demande quel type de vote il souhaite faire

                                            $this->sendMessage($message['chatId'], "*Votre choix dans cette phase est valable pour toute la soirée.*");

                                            sleep(6);

                                            if($type_vote == 'both'):

                                                $this->sendMessage($message['chatId'],
                                                    "*Specifie le type de vote que vous souhaitez effectuer ?* \n\n".
                                                "- *HOME* : pour les votes depuis la maison. Vous pouvez voter pour votre candidate à chaque passage. \n".
                                                "- *SITE* : pour les votes sur site à partir des numéros de ticket remis lors de l'achat de votre bouteille orangina. \n\n".
                                                "Envoyez *SITE* ou *HOME*.");

                                            endif;

                                            if($type_vote == 'home'):

                                                $this->sendMessage($message['chatId'],
                                                    "*Specifie le type de vote que vous souhaitez effectuer ?* \n\n".
                                                    "- *HOME* : pour les votes depuis la maison. Vous pouvez voter pour votre candidate à chaque passage. \n".
                                                    "Envoyez *HOME*.");

                                            endif;

                                            if($type_vote == 'site'):

                                                $this->sendMessage($message['chatId'],
                                                    "*Specifie le type de vote que vous souhaitez effectuer ?* \n\n".
                                                    "- *SITE* : pour les votes sur site à partir des numéros de ticket remis lors de l'achat de votre bouteille orangina\n\n".
                                                    "Envoyez *SITE*.");

                                            endif;

                                            $etape_list = [
                                                0 => false,
                                                1 => true,
                                                2 => false,
                                                3 => false,
                                                4 => false
                                            ];
                                            update_post_meta($participant->ID, 'current_session', serialize($etape_list));
                                        }

                                        break;
                                    }
                                    case '*missorangina*':
                                    case 'missorangina':{
                                        $this->existPhase($message['chatId'], true);
                                        break;
                                    }
                                    case 'stop' : {
                                        $this->stopMessage($message['chatId'], $phase, $participant);
                                        break;
                                    }
                                    default: {

                                        $string = "*Hello* \n";
                                        $string .= "Bienvenue sur l'assistant de vote du concours *Miss Orangina*. \n";
                                        $string .= "Envoyez le mot *missorangina* et suivez les instructions afin de faire valider votre vote. \n\n";
                                        $string .= "*L'equipe Orangina*";

                                        $this->sendMessage($message['chatId'], $string);
                                        break;
                                    }

                                }

                                break;
                            }
                            case 1 : {

                                $text = explode(' ',trim($message['body']));

                                switch(mb_strtolower($text[0],'UTF-8')){
                                    case 'site':
                                    case '*site*':
                                    case '*home*':
                                    case 'home': {

                                        $choix_participant = tr_posts_field('choix_participant', $phase->ID);
                                        if(!$choix_participant):
                                            $choix_participant = array();
                                        endif;

                                        $choix = array();
                                        $choix['idparticipant'] = $participant->ID;
                                        $choix['type_vote'] = mb_strtolower($text[0],'UTF-8');

                                        $choix_participant[] = $choix;

                                        update_post_meta($phase->ID, 'choix_participant', $choix_participant);

                                        $participant_id = tr_posts_field('participant_id', $phase->ID);
                                        if(!$participant_id):
                                            $participant_id = array();
                                        endif;

                                        $participant_id[] = $participant->ID;

                                        update_post_meta($phase->ID, 'participant_id', $participant_id);

                                        $etape_list = [
                                            0 => false,
                                            1 => false,
                                            2 => true,
                                            3 => false,
                                            4 => false
                                        ];

                                        update_post_meta($participant->ID, 'current_session', serialize($etape_list));

                                        $string = "*Envoyez le numero de votre candidat préféré.*\n\n";

                                        $candidats = tr_posts_field('list_candidats', $phase->ID);

                                        foreach ($candidats as $cand):
                                            $current_candidat = get_post($cand['candidat']);
                                            $string .= "*".$cand['codevote']."* - ".$current_candidat->post_title."\n";
                                        endforeach;

                                        if($type_vote == 'home' || $type_vote == 'both'):
                                            $string .= "\n *NB*: vous ne pouvez que voter un candidat à la fois si votre choix de vote est *HOME*.";
                                        endif;

                                        $this->sendMessage($message['chatId'], $string);

                                        break;
                                    }
                                    case 'stop' : {
                                        $this->stopMessage($message['chatId'], $phase, $participant);
                                        break;
                                    }
                                    default: {

                                        if($type_vote == 'site'):
                                            $this->sendMessage($message['chatId'], "Envoyez *SITE* pour valider votre choix.");
                                        endif;

                                        if($type_vote == 'home'):
                                            $this->sendMessage($message['chatId'], "Envoyez *HOME* pour valider votre choix.");
                                        endif;

                                        if($type_vote == 'both'):
                                            $this->sendMessage($message['chatId'], "Envoyez *SITE* ou *HOME* pour valider votre choix.");
                                        endif;

                                        break;
                                    }
                                }


                                break;
                            }

                            case 2 : {

                                $text = explode(' ',trim($message['body']));

                                switch(mb_strtolower($text[0],'UTF-8')){
                                    case 'stop' : {
                                        $this->stopMessage($message['chatId'], $phase, $participant);
                                        break;
                                    }
                                    default :{
                                        $candidats = tr_posts_field('list_candidats', $phase->ID);
                                        $result = $this->search($candidats, 'codevote', $text[0]);

                                        if($result){

                                            $participant_id = tr_posts_field('participant_id', $phase->ID);
                                            $choix_participant = tr_posts_field('choix_participant', $phase->ID);

                                            $key = array_search($participant->ID, $participant_id);
                                            $current_choix = $choix_participant[$key];

                                            if($current_choix['type_vote'] == 'home'){
                                                $vote_query = new Vote();
                                                $exist = $vote_query->where('idparticipant', '=', $participant->ID)
                                                    ->where('idphase', '=', $phase->ID)
                                                    ->where('idetape', '=', tr_options_field('options.sequence_vote'))
                                                    ->first();
                                                if(!$exist):
                                                    $save_vote = new Vote();
                                                    $save_vote->idphase = $phase->ID;
                                                    $save_vote->idetape = tr_options_field('options.sequence_vote');
                                                    $save_vote->idparticipant = $participant->ID;
                                                    $save_vote->point = 1;
                                                    $save_vote->type_vote = 'HOME';
                                                    $save_vote->idcandidat = $result[0]['candidat'];
                                                    $save_vote->save();

                                                    $this->sendMessage($message['chatId'], "Votre vote a été pris en compte.");

                                                else:
                                                    $this->sendMessage($message['chatId'], "Votre vote a déja été pris en compte pour un candidat.");
                                                endif;

                                                $this->pourcentage($message['chatId'], $result[0]['candidat'], $phase->ID);

                                                $this->endMessage($message['chatId']);

                                                update_post_meta($participant->ID, 'current_session', null);
                                            }
                                            else{

                                                $string = "*Saisissez vos numeros de ticket d'achat.*\n\n";
                                                $string .= "Il est marqué sur votre ticket sous le format XXX-XXX\n";
                                                $string .= "Si vous avez plusieurs numéros de ticket, saisissez les en séparant par une virgule (,).\n";
                                                $string .= "*Exemple* : ERD-RRT,EDG-TG9,... .\n\n";
//                                            $string .= "*NB* : S'il y'a la présence des charactères, ils sont en majuscule.\n";

                                                $this->sendMessage($message['chatId'], $string);

                                                $save_vote = new Vote();
                                                $save_vote->idphase = $phase->ID;
                                                $save_vote->idetape = tr_options_field('options.sequence_vote');
                                                $save_vote->idparticipant = $participant->ID;
                                                $save_vote->point = null;
                                                $save_vote->type_vote = 'SITE';
                                                $save_vote->idcandidat = $result[0]['candidat'];
                                                $save_vote->save();

                                                $etape_list = [
                                                    0 => false,
                                                    1 => false,
                                                    2 => false,
                                                    3 => true,
                                                    4 => false
                                                ];

                                                update_post_meta($participant->ID, 'current_session', serialize($etape_list));
                                            }
                                        }
                                        else {
                                            $this->sendMessage($message['chatId'], "Aucun candidat ne correspond à ce numéro.");
                                        }
                                        break;
                                    }
                                }



                                break;
                            }
                            case 3 : {

                                $text = explode(' ',trim($message['body']));

                                switch(mb_strtolower($text[0],'UTF-8')) {
                                    case 'stop' :
                                    {
                                        $this->stopMessage($message['chatId'], $phase, $participant);
                                        break;
                                    }
                                    case 'terminez':
                                    case 'termine': {

                                        $old_vote = new Vote();
                                        $old_vote = $old_vote->where('idphase', '=', $phase->ID)
                                            ->where('idetape', '=', tr_options_field('options.sequence_vote'))
                                            ->where('idparticipant', '=', $participant->ID)
                                            ->where('point', '=', null)->first();

                                        $this->pourcentage($message['chatId'], $old_vote->idcandidat, $phase->ID);

                                        $old_vote->delete();

                                        $this->endMessage($message['chatId'], false);

                                        update_post_meta($participant->ID, 'current_session', null);

                                    }
                                    default:{
                                        $series = explode(',', trim($text[0]));

                                        if($series):

                                            $serie_error = false;

                                            $old_vote = new Vote();
                                            $old_vote = $old_vote->where('idphase', '=', $phase->ID)
                                                ->where('idetape', '=', tr_options_field('options.sequence_vote'))
                                                ->where('idparticipant', '=', $participant->ID)
                                                ->where('point', '=', null)->first();

                                            $error_ticket = array();

                                            foreach ($series as $serie):

                                                $serie_current = explode('-', $serie);
                                                $serie_current = join("", $serie_current);

                                                $args = [
                                                    'post_type' => 'ticket',
                                                    'meta_query' => array(
                                                        array(
                                                            'key' => 'serie',
                                                            'value' => strtoupper($serie_current)
                                                        ),
                                                        array(
                                                            'key' => 'used',
                                                            'value' => 'no'
                                                        )
                                                    )
                                                ];

                                                $ticket = query_posts($args);

                                                if($ticket){
                                                    $save_ticke = (new Ticket())->findById($ticket[0]->ID);
                                                    $save_ticke->used = 'yes';
                                                    $save_ticke->save();

                                                    $save_vote = new Vote();
                                                    $save_vote->idphase = $phase->ID;
                                                    $save_vote->idetape = tr_options_field('options.sequence_vote');
                                                    $save_vote->idparticipant = $participant->ID;
                                                    $save_vote->point = 1;
                                                    $save_vote->type_vote = 'SITE';
                                                    $save_vote->idcandidat = $old_vote->idcandidat;
                                                    $save_vote->idserie = $save_ticke->ID;
                                                    $save_vote->save();
                                                }else{
                                                    $serie_error = true;
                                                    $error_ticket[] = $serie;
                                                }

                                            endforeach;

                                            $string = "Vos tickets de vote ont été enregistrés avec succès.";

                                            if($serie_error):
                                                $string .= "*Certains de vos tickets de vote n'ont pas été pris en compte car ils n'existent pas ou sont déja été utilisés ou mal saisis.*";
                                                foreach ($error_ticket as $error):
                                                    $string .= "- *".$error."*";
                                                endforeach;
                                            endif;

                                            $this->sendMessage($message['chatId'], $string);

                                            if(!$serie_error):

                                                $this->pourcentage($message['chatId'], $old_vote->idcandidat, $phase->ID);

                                                $old_vote->delete();

                                                $this->endMessage($message['chatId'], false);

                                                update_post_meta($participant->ID, 'current_session', null);

                                            else:

                                                $string_message = "Envoyez à nouveau les numéros de ticket qui se trouvent dans la liste précédente en respectant les instructions suivantes.\n\n";
                                                $string_message .= "Vérifiez que :.\n";
                                                $string_message .= "- Vous n'avez pas écrit un zero à la place de la lettre o et vice versa.\n";
                                                $string_message .= "- Vous n'avez pas mis des espaces entre les lettres.\n";
                                                $string_message .= "- Vous n'avez pas mis des espaces entre les virgules (,) lorsque vous avez saisie plusieurs tickets.\n\n";
                                                $string_message .= "Ou envoyez *terminez* si vous n'avez plus de ticket à enregistrer.";

                                                $this->sendMessage(($message['chatId']), $string_message);

                                            endif;

                                        else:
                                            $this->sendMessage($message['chatId'], "Les numeros de ticket ont été mal saisie. Veuillez reessayer svp!");
                                        endif;

                                        break;
                                    }
                                }


                                break;
                            }
                        }

                    else:
                        $this->existPhase($message['chatId'], false);
                    endif;



                }
            }
        }

    }

    public function stopMessage($chatId, $phase, $participant){

        $this->sendMessage($chatId, "Vous avez solliciter recommencer la procedure de vote.\n\n *Merci de votre participation*");

        $participant_id = tr_posts_field('participant_id', $phase->ID);
        $choix_participant = tr_posts_field('choix_participant', $phase->ID);

        $key = array_search($participant->ID, $participant_id);
        $current_choix = $choix_participant[$key];

        update_post_meta($participant->ID, 'current_session', null);

        if ($current_choix['type_vote'] == 'home') {
            $this->endMessage($chatId);
        } else {
            $this->endMessage($chatId, false);
        }

    }

    public function pourcentage($chatId, $idcandidat, $phaseId){

        $resultat_candidat = tr_query()->table('wp_posts')
            ->select('wp_posts.*', 'SUM(wp_miss_vote.point) as vote')
            ->join('wp_miss_vote', 'wp_miss_vote.idcandidat', '=', 'wp_posts.ID')
            ->where('wp_miss_vote.idphase', '=', $phaseId)
            ->where('wp_posts.ID', 'IN', [$idcandidat])
            ->groupBy('wp_posts.ID')
            ->findAll()->orderBy('vote', 'DESC')->get();

        $all_vote = tr_query()->table('wp_miss_vote')->select('SUM(point) as vote')->where('idphase', '=', $phaseId)->get();

        $last_candidate = get_post($idcandidat);
        $pourcentage = $resultat_candidat[0]->vote * 100;
        $pourcentage = round($pourcentage / $all_vote[0]->vote, 1);
        $string = "Votre candidate *". $last_candidate->post_title. "* a *".$pourcentage."%* de vote. \n\n";
        $string .= "*Pensez à inviter des amis à vous aider à la soutenir*";

        $this->sendMessage($chatId, $string);

    }

    public function check_phase(){
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

        return $phase ? $phase[0] : null;
    }

    public function check_participant($phone){

        $args = [
            'post_type' => 'participant',
            'meta_query' => array(
                array(
                    'key' => 'telephone',
                    'value' => $phone
                )
            )
        ];

        $participant = query_posts($args);

        if($participant):

            update_post_meta($participant[0]->ID, 'last_activity', date('Y-m-d H:i:s'));


            $result = $participant[0];

        else:

            $participation = new Participant();
            $participation->post_title = $phone;
            $participation->post_status = 'publish';
            $participation->telephone = $phone;
            $participation->last_activity = date('Y-m-d H:i:s');
            $participation->save();

            if($participation->post_date_gmt){
                update_post_meta($participation->ID, 'date_save', $participation->post_date_gmt);
            }

            $result = $participation;

        endif;

        $current_session = tr_posts_field('current_session', $result->ID);
        if(!$current_session):
            $current_session = serialize($this->formatSession);
            update_post_meta($result->ID, 'current_session', $current_session);
        endif;

        return $result;
    }

    public function check_session($participant){

        $current_session = tr_posts_field('current_session', $participant->ID);

        $session = (new Session())
            ->where('idparticipant', '=', $participant->ID)
            ->where('status', '=', 1)
            ->first();

        update_post_meta($participant->ID, 'current_session', true);

        if($current_session && $session):

            $session = (new Session())
                ->where('idparticipant', '=', $participant->ID)
                ->where('status', '=', 1)
                ->first();

        else:

            $sess = new Session();
            $sess->idparticipant = $participant->ID;
            $sess->etape_list = serialize($this->formatSession);
            $sess->status = 1;
            $sess->save();

            $session = $sess;

        endif;

        return $session;
    }

    public function session_key($participant){

        $current_session = tr_posts_field('current_session', $participant->ID);

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

        return $key_current;

    }

    public function existPhase($chatId, $exist=false){

        if($exist):

            $this->sendMessage($chatId,

                "*Hello*\n\n".
                "Bienvenue sur l'assistant de vote du concours *Miss Orangina*.\n".
                "Pour voter votre candidate, envoyez *start* pour lancer la procédure de vote.\n".
                "Suivez les instructions de bout en bout afin de valider votre vote.\n\n".
                "Envoyez *stop* pour arrêter ou recommencer la procédure de vote.\n\n".
                "*L'equipe Orangina.*"
            );

        else:

            $this->sendMessage($chatId,

                "*Hello*\n\n".
                "Bienvenue sur l'assistant de vote du concours *Miss Orangina*.\n".
                "Les votes sont achevés pour la phase *".$this->phase->post_title."*.\n\n".
                "Merci de solliciter notre plateforme pour voter votre candidate favorite.\n".
                "*L'equipe Orangina.*\n"
            );

        endif;
    }

    public function endMessage($chatId, $typeVote=true){

        if($typeVote):
            $this->sendMessage($chatId,
                "*Merci de votre participation.*\n\n".
                "Pensez à voter de nouveau pour votre candidate après le prochain passage des candidates.\n".
                "Pour voter votre candidate, envoyer *start* pour relancer la procédure de vote.\n\n".
                "Envoyer *stop* pour arrêter ou recommencer la procédure de vote.\n\n".
                "*L'equipe Orangina.*\n"
            );
        else:
            $this->sendMessage($chatId,
                "*Merci de votre participation.*\n\n".
                "Pensez à voter de nouveau pour votre candidate.\n".
                "Pour voter votre candidate, envoyer *start* pour relancer la procédure.\n\n".
                "Envoyer *stop* pour arrêter ou recommencer la procédure de vote.\n\n".
                "*L'equipe Orangina.*\n"
            );
        endif;

    }

    public function sendMessage($chatId, $text){
        $data = array('chatId'=>$chatId,'body'=>$text);
        $this->sendRequest('message',$data);
    }

    public  function readMessage($chatId){
        $data = array('chatId'=>$chatId);
        $this->sendRequest('readChat', $data);
    }
    public function sendRequest($method,$data){

        $url = $this->APIurl.$method.'?token='.$this->token;
        if(is_array($data)){ $data = json_encode($data);}
        $options = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $data]]);
        $response = file_get_contents($url,false,$options);
        file_put_contents(get_template_directory().'/requests.log',$response.PHP_EOL,FILE_APPEND);

    }

    public function search($array, $key, $value)
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, $this->search($subarray, $key, $value));
            }
        }

        return $results;
    }


}
