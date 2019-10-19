<?php
namespace App\Controllers;

use App\Models\Inscrit;
use App\Models\Parrain;
use App\Models\Vote;
use Spipu\Html2Pdf\Html2Pdf;
use TypeRocket\Controllers\WPPostController;

class InscritController extends WPPostController
{
    protected $modelClass = Inscrit::class;


    public function inscription(){

        $this->validation = [
            'nom' => 'required',
            'prenom' => 'required',
            'datenais' => 'required',
            'email' => 'required|email',
            'position' => 'required',
            'compte' => 'required',
            'taille' => 'required'
        ];


        $fields = $this->request->getFields();

        if($this->invalid()){
            flash('error-data-inscription', 'Des champs obligatoire n\'ont pas été renseigné', 'uk-text-danger');
            return tr_redirect()->back()->withFields($fields);
        }else{

            if($this->Age($fields['datenais'])) {

                $post_title = $fields['nom'];
                if ($fields['prenom']) {
                    $post_title .= ' ' . $fields['prenom'];
                }

                if($fields['ID']):
                    $id = $fields['ID'];
                    wp_update_post(array(
                        'ID' => $id,
                        'post_title' => $post_title
                    ));
                else:
                    $id = wp_insert_post(array(
                        'post_type' => 'inscrit',
                        'post_title' => $post_title
                    ));
                endif;

                $parrain = new Parrain();
                $exist_parrain = $parrain->where('email', '=', strtolower($fields['email']))->first();
                if(!$exist_parrain){
                    $parrain->email = strtolower($fields['email']);
                    $parrain->parrain = false;
                    $parrain->save();
                }

                if (empty(tr_posts_field('codeins', $id))):
                    $option_ville = tr_options_field('options.insc_ville') ? tr_options_field('options.insc_ville') : [];
                    $codecell = '';
                    foreach ($option_ville as $ville):
                        if (strtoupper($ville['ville']) == strtoupper($fields['position'])):
                            $codecell = $ville['code'] . '' . $this->random(4);
                        endif;
                    endforeach;

                    $fields['codeins'] = $codecell;
                endif;

                $time = \DateTime::createFromFormat('d/m/Y', tr_posts_field('datenais', $id));

                $newformat = $time->format('Y-m-d');

                $fields['datenais_format'] = $newformat;

                $post = $this->model->findById($id);
                $post->update($fields);

                $msg = tr_view('email.inscription', ['candidat' => $post]);
                /**
                 * Envoyer un email au candidat
                 */

                $header = "From: no-reply@missorangina-cm.com\r\n";
                add_filter('wp_mail_content_type',array(__CLASS__, 'set_html_content_type'));
                add_filter( 'wp_mail_from_name', array(__CLASS__,'custom_wp_mail_from_name') );

                wp_mail(tr_posts_field('email', $post->ID), 'Inscription Reussie', $msg, $header);

                remove_filter ('wp_mail_content_type', array(__CLASS__, 'set_html_content_type'));


                return tr_redirect()->toUrl(get_post_permalink(tr_options_field('options.page_parrain')));

            }else{
                flash('error-data-inscription', 'Le candidat n\'est pas éligible. Pensez à verifier votre date de naissance', 'uk-text-danger');
                return tr_redirect()->back()->withFields($fields);
            }


        }

    }


    public function parrain(){

        $fields = $this->request->getFields();

        $post = $this->model->findById($fields['ID']);

        foreach ($fields['parrain'] as $parrain):

            $parr = new Parrain();

            $exist = $parr->where('email', '=', strtolower($parrain['email']))->first();

            if(!$exist):
                $parr->email = strtolower($parrain['email']);
                $parr->parrain = true;
                $parr->save();

                $msg = tr_view('email.parrain', ['candidat' => $post]);

                /**
                 * Envoyer un email avec les informations du candidat
                 */

                $header = "From: no-reply@missorangina-cm.com\r\n";
                add_filter('wp_mail_content_type',array(__CLASS__, 'set_html_content_type'));
                add_filter( 'wp_mail_from_name', array(__CLASS__,'custom_wp_mail_from_name') );

                wp_mail(strtolower($parrain['email']), 'Demande de parrainage', $msg, $header);

                remove_filter ('wp_mail_content_type', array(__CLASS__, 'set_html_content_type'));

            endif;

        endforeach;

        return tr_redirect()->toUrl(get_post_permalink(tr_options_field('options.page_end_inscription')));

    }

    public function resend($codeins=null){

        $args = array(
            'post_type' => 'inscrit'
        );

        if(!$codeins):

            $posts = query_posts($args);

            foreach ($posts as $post):

                $msg = tr_view('email.inscription', ['candidat' => $post]);
                /**
                 * Envoyer un email au candidat
                 */

                $header = "From: no-reply@missorangina-cm.com\r\n";
                add_filter('wp_mail_content_type',array(__CLASS__, 'set_html_content_type'));
                add_filter( 'wp_mail_from_name', array(__CLASS__,'custom_wp_mail_from_name') );

                wp_mail(tr_posts_field('email', $post->ID), 'Reconfirmation du lieu de casting et de votre formulaire d\'inscription', $msg, $header);

                remove_filter ('wp_mail_content_type', array(__CLASS__, 'set_html_content_type'));

            endforeach;

        else:

            $codeins = preg_split('[/]', $codeins)[0];

            $args['meta_query'] = array(
                    array(
                        'key' => 'codeins',
                        'value' => $codeins,
                        'compare' => '='
                    )
                );

            $post = query_posts($args);

            $msg = tr_view('email.inscription', ['candidat' => $post[0]]);
            /**
             * Envoyer un email au candidat
             */

            $header = "From: no-reply@missorangina-cm.com\r\n";
            add_filter('wp_mail_content_type',array(__CLASS__, 'set_html_content_type'));
            add_filter( 'wp_mail_from_name', array(__CLASS__,'custom_wp_mail_from_name') );

            wp_mail(tr_posts_field('email', $post[0]->ID), 'Reconfirmation du lieu de casting et de votre formulaire d\'inscription', $msg, $header);

            remove_filter ('wp_mail_content_type', array(__CLASS__, 'set_html_content_type'));

        endif;

        return tr_view('email.resend');

    }

    public function vote($idcandidat, $idselection){

        $id = preg_split('[/]', $idcandidat)[0];
        $idSelection = preg_split('[/]', $idselection)[0];


        if(isset($_SESSION) && isset($_SESSION['token_fb_vote'])) {

            $facebook = new FacebookController();

            $fb = $facebook->set_facebook();
            $fb->setDefaultAccessToken($_SESSION['token_fb_vote']);

            $response = $fb->get('/me?locale=en_US&fields=id,email');
            $userNode = $response->getGraphUser();

//            $post = $this->model->findById($id);

            $idfacebook = tr_posts_field('idfacebook', $id);
            $current_year = tr_options_field('options.ins_year');

            $miss_vote = new Vote();
            $exit = $miss_vote->where('idfacebook', '=', $idfacebook)->where('year', '=', $current_year)->where('etape', '=', $idSelection)->count();

            if(!$exit):

                $miss_vote->idcandidat = $id;
                $miss_vote->idfacebook =  $idfacebook;
                $miss_vote->year = $current_year;
                $miss_vote->etape = $idSelection;

                $miss_vote->save();

                $parr = new Parrain();

                $exist = $parr->where('email', '=', strtolower($userNode->getField('email')))->first();

                if(!$exist):
                    $parr->email = strtolower($userNode->getField('email'));
                    $parr->parrain = false;
                    $parr->save();
                endif;

                return tr_redirect()->toUrl(get_post_permalink(tr_options_field('options.page_vote_confirm')));

            else:

                return tr_redirect()->toUrl(get_post_permalink(tr_options_field('options.page_vote_exist')));

            endif;

        }else{
            session_destroy();
            return tr_redirect()->back()->now();
        }



    }

    public function fiche($codeins = null){

        $codeins = preg_split('[/]', $codeins)[0];

        if($codeins == null){
            return tr_redirect()->back()->now();
        }

        ob_start();

        $args = array(
            'post_type' => 'inscrit'
        );
        $args['meta_query'] = array(
            array(
                'key' => 'codeins',
                'value' => $codeins,
                'compare' => '='
            )
        );

        $post = query_posts($args);

        tr_view('pdf.fiche', ['candidat' => $post[0]])::load();

        $content = ob_get_clean();

        http_response_code(200);

        try{
            $pdf = new HTML2PDF('P', 'A4', 'fr');
            $pdf->writeHTML($content);
            $pdf->Output($codeins.'.pdf');
        }catch (\HTML2PDF_exception $e){
            die($e);
        }

        exit;

    }

    public function update($id = null)
    {

        $this->validation = [
            'nom' => 'required',
            'prenom' => 'required',
            'datenais' => 'required',
            'email' => 'required|email',
            'position' => 'required'
        ];



        $post = $this->model->findById( $id );
        $fields = $this->request->getFields();

        if($fields['post_status_old'] == 'auto-draft'):
            if($this->invalid()){
                $post->delete();
                wp_die('Des champs obligatoires n\'ont pas été renseigné. Ces champs sont représentés par (<span style="color: red;">*</span>). <br><br> <a href="'.tr_redirect()->back()->withFields($fields)->url.'">Retour</a>');
            }else{
                if($this->Age($fields['datenais'])){

                    if (empty($post->post_title)):
                        $post->post_title = $fields['nom'];
                        if ($fields['prenom']) {
                            $post->post_title .= ' ' . $fields['prenom'];
                        }
                    endif;

                    if(empty(tr_posts_field('codeins', $id))):
                        $option_ville = tr_options_field('options.insc_ville') ? tr_options_field('options.insc_ville') : [];
                        $codecell = '';
                        foreach ($option_ville as $ville):
                            if(strtoupper($ville['ville']) == strtoupper($fields['position'])):
                                $codecell = $ville['code'].''.$this->random(4);
                            endif;
                        endforeach;


                        update_post_meta( $post_id = $id, $key = 'codeins', $value = $codecell );
                    endif;

                    $time = \DateTime::createFromFormat('d/m/Y', tr_posts_field('datenais', $id));

                    $newformat = $time->format('Y-m-d');

                    update_post_meta($id, 'datenais_format', $newformat);

                    parent::update($id);
                }else{
                    $post->delete();
                    wp_die('l\'age du candidate doit etre compris entre 18 ans and et 26 ans. <br><br> <a href="'.tr_redirect()->back()->withFields($fields)->url.'">Retour</a>');
                }
            }
        else:

            if($post->post_status == 'publish'):
                if($fields){
                    if($this->invalid()){
                        $post->post_title = $fields['post_title_old'];
                        $post->save();
                        wp_die('Des champs obligatoires n\'ont pas été renseigné. Ces champs sont représentés par (<span style="color: red;">*</span>). <br><br> <a href="'.tr_redirect()->back()->withFields($fields)->url.'">Retour</a>');
                    }else{
                        if($this->Age($fields['datenais'])){

                            if (empty($post->post_title)):
                                $post->post_title = $fields['nom'];
                                if ($fields['prenom']) {
                                    $post->post_title .= ' ' . $fields['prenom'];
                                }
                            endif;

                            if(empty(tr_posts_field('codeins', $id))):
                                $option_ville = tr_options_field('options.insc_ville') ? tr_options_field('options.insc_ville') : [];
                                $codecell = '';
                                foreach ($option_ville as $ville):
                                    if(strtoupper($ville['ville']) == strtoupper($fields['position'])):
                                        $codecell = $ville['code'].''.$this->random(4);
                                    endif;
                                endforeach;


                                update_post_meta( $post_id = $id, $key = 'codeins', $value = $codecell );
                            endif;

                            $time = \DateTime::createFromFormat('d/m/Y', tr_posts_field('datenais', $id));

                            $newformat = $time->format('Y-m-d');

                            update_post_meta($id, 'datenais_format', $newformat);

                            parent::update($id);
                        }else {
                            $post->post_title = $fields['post_title_old'];
                            $post->save();
                            wp_die('l\'âge du candidate doit être compris entre 18 ans and et 26 ans. <br><br> <a href="'.tr_redirect()->back()->withFields($fields)->url.'">Retour</a>');
                        }
                    }
                }

            endif;
        endif;

    }

    public function importer(){

        $query = tr_query()->table('wp_miss_inscrit');

        $inscrits = $query->findAll()->get();

        foreach ($inscrits as $inscrit):

            $query_ins = tr_query()->table('wp_miss_inscrit');

            $args = array(
                'post_type' => 'inscrit'
            );
            $args['meta_query'] = array(
                array(
                    'key' => 'codeins',
                    'value' => $inscrit->codeins,
                    'compare' => '='
                )
            );

            $post = query_posts($args);

            if(!$post):

                $post_title = $inscrit->nom;
                if ($inscrit->prenom) {
                    $post_title .= ' ' . $inscrit->prenom;
                }

                $id = wp_insert_post(array(
                    'post_type' => 'inscrit',
                    'post_title' => $post_title,
                    'post_status' => 'publish'
                ));

                update_post_meta($id, 'codeins', $inscrit->codeins);
                update_post_meta($id, 'nom', $inscrit->nom);
                update_post_meta($id, 'prenom', $inscrit->prenom);
                update_post_meta($id, 'datenais_format', $inscrit->dateNais);

                $time = \DateTime::createFromFormat('Y-m-d', $inscrit->dateNais);

                $newformat = $time->format('d/m/Y');

                update_post_meta($id, 'datenais', $newformat);

                update_post_meta($id, 'lieu', $inscrit->lieuNais);
                update_post_meta($id, 'email', $inscrit->email);
                update_post_meta($id, 'nationalite', $inscrit->nationalite);
                update_post_meta($id, 'adresse', $inscrit->adresse);

                $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                    'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                    'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                    'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                    'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );

                $position = strtr( $inscrit->ville, $unwanted_array );

                update_post_meta($id, 'position', strtoupper($position));
                update_post_meta($id, 'phone', $inscrit->phone);

                update_post_meta($id, 'diplome', $inscrit->diplome);
                update_post_meta($id, 'profession', $inscrit->profession);
                update_post_meta($id, 'compte', $inscrit->dream);
                update_post_meta($id, 'signe', $inscrit->ambition);
                update_post_meta($id, 'taille', $inscrit->taille);
                update_post_meta($id, 'casier', $inscrit->qualite);
                update_post_meta($id, 'enfant', $inscrit->enfant);
                update_post_meta($id, 'participe', $inscrit->concours);
                update_post_meta($id, 'idfacebook', $inscrit->idfacebook);
                update_post_meta($id, 'year_participe', tr_options_field('options.ins_year'));

            endif;

            $query_ins->findById($inscrit->id)->delete();

        endforeach;

        return tr_redirect()->back()->now();
    }


    public function exporter(){

        ob_start();

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

        $args = ['post_type' => 'inscrit', 'posts_per_page' => '-1'];

        if($meta_query){
            $args['meta_query'] = $meta_query;
        }

        $candidat = query_posts($args);

        tr_view('pdf.inscrit', ['candidats' => $candidat, 'slug' => $_GET['slug'], 'slug_year' => $_GET['slug-year'], 's' => $_GET['s']])::load();

        $content = ob_get_clean();

        http_response_code(200);

        try{
            $pdf = new HTML2PDF('L', 'A4', 'fr');
            $pdf->writeHTML($content);
            $pdf->Output('FicheInscrit.pdf');
        }catch (\HTML2PDF_exception $e){
            die($e);
        }

        exit;

    }

    public function Age($date){
        list($jour, $mois, $annee) = preg_split('[/]', $date);
        $today['mois'] = date('n');
        $today['jour'] = date('j');
        $today['annee'] = date('Y');
        $annees = $today['annee'] - $annee;
//        if ($today['mois'] <= $mois) {
//            if ($mois == $today['mois']) {
//                if ($jour > $today['jour'])
//                    $annees--;
//            }
//            else
//                $annees--;
//        }
        if($annees < 18 || $annees > 26):
            return false;
        else:
            return true;
        endif;
    }


    public function random($car) {
        $string = "";
        $chaine = "1234567890";
        srand((double)microtime()*1000000);
        for($i=0; $i<$car; $i++) {
            $string .= $chaine[rand()%strlen($chaine)];
        }
        return $string;
    }

    static function set_html_content_type(){
        return 'text/html';
    }

    static function custom_wp_mail_from_name( $original_email_from ) {
        return 'L\'équipe Orangina';
    }
}
