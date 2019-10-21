<?php
namespace App\Controllers;


use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use TypeRocket\Controllers\Controller;

class FacebookController extends Controller
{

    public function set_facebook(){
        $fb = new Facebook([
                'app_id' => tr_options_field('options.facebook_appid'),
                'app_secret' => tr_options_field('options.facebook_appsecret'),
                'default_graph_version' => tr_options_field('options.facebook_version')
            ]);

        return $fb;
    }


    public function js_login_callback(){

        $fb = $this->set_facebook();
        $helper = $fb->getJavaScriptHelper();

        try{
            $accessToken = $helper->getAccessToken();
        }catch(FacebookResponseException $e){
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        }catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if(isset($accessToken)){
            $_SESSION['token_fb'] = (string) $accessToken;
            return tr_redirect()->toUrl(get_post_permalink(tr_options_field('options.page_like')));
        }
        elseif ($helper->getError()) {
            session_destroy();
            return tr_redirect()->back()->now();
        }
    }

    public function vote_callback($idcandidat, $idselection){

        $fb = $this->set_facebook();
        $helper = $fb->getJavaScriptHelper();
//        var_dump($helper = $fb->getRedirectLoginHelper());

        try{
            $accessToken = $helper->getAccessToken();
        }catch(FacebookResponseException $e){
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        }catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if(isset($accessToken)){
            $_SESSION['token_fb_vote'] = (string) $accessToken;
            return tr_redirect()->toHome('/vote/'.$idcandidat.'/'.$idselection);

        }
        elseif($helper->getError()) {
            return tr_redirect()->back()->now();
        }
    }

}
