<?php

namespace FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Facebook\autoload;

class FacebookController extends Controller {

    public function indexAction() {
        return $this->render('@Facebook/Default/index.html.twig');
    }

    public function facebookFormAction() { // config
        
        $baseUrl = "http://jbsb92.bikesdealer.com";

        $fb = new \Facebook\Facebook([
            'app_id' => '316702028996590',
            'app_secret' => 'bb6065e8a4ea1bc47249272e2ee43e9e',
            'default_graph_version' => 'v3.2'
        ]);

        $helper = $fb->getRedirectLoginHelper(); // to perform operation after redirection
        
        $redirectURL = $baseUrl . '/x_Facebook/successLogin';
        $permissions = ['email'];
        $loginUrl = $helper->getLoginUrl($redirectURL, $permissions);
        
        return $this->redirect($loginUrl);
    }
    
    

    public function successLoginFormAction() {
        
        $fb = new \Facebook\Facebook([
            'app_id' => '316702028996590',
            'app_secret' => 'bb6065e8a4ea1bc47249272e2ee43e9e',
            'default_graph_version' => 'v3.2'
        ]);

        $helper = $fb->getRedirectLoginHelper(); // to perform operation after redirection
        
        $_SESSION['FBRLH_state']=$_GET['state'];
        
        try {
            $accessToken = $helper->getAccessToken(); // to fetch access token
//            echo 'success token... ' . $accessToken . " ...";
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When facebook server returns error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // when issue with the fetching access token
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        //// Logged in
        //echo '<h3>Access Token</h3>';
        //var_dump($accessToken->getValue());
        
        
        try {
            // to get required fields using access token
            $response = $fb->get('/me?fields=id,name,email', $accessToken->getValue());
        } catch (Facebook\Exceptions\FacebookResponseException $e) {// throws an error if invalid fields are specified
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $user = $response->getGraphUser(); // to get user details

        $facebookUser_id = $user['id'];
        $facebookUser_name = $user['name'];
        $facebookUser_email = $user['email'];

        $this->signUpFacebook(
                $facebookUser_id,
                $facebookUser_name,
                $facebookUser_email
                );
        
        return $this->redirect('http://jbsb92.bikesdealer.com');
        
    }
    
    public function signUpFacebook(
                $facebookUser_id,
                $facebookUser_name,
                $facebookUser_email
            )
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->createQuery(
            "SELECT u.userId, u.userName 
            FROM HomeBundle:User u 
            WHERE u.userEmail = '$facebookUser_email'"
        );
        $users = $user->getResult();
        if (!$users) {
            $user = new \HomeBundle\Entity\User;
            $user->setUserName($facebookUser_name);
            $user->setUserFirstgivenname($facebookUser_name);
            $user->setUserFirstfamilyname($facebookUser_name);
            $user->setUserEmail($facebookUser_email);

            $em->persist($user);
            $em->flush();

            $user_id = $user->getUserId();
            $user_name = $user->getUserName();

            $_SESSION['loginSession'] = $user_id;

            $users2 = array();
            $users2[] = array(
                'id' => $user_id,
                'userName' => $user_name
            );
            return new Response(json_encode($users2), 200, array('Content-Type' => 'application/json'));
        } else {
            $_SESSION['loginSession'] = $users[0]['userId'];

            $users2 = array();
            $users2[] = array(
                'id' => $users[0]['userId'],
                'userName' => $users[0]['userName']
            );
            return new Response(json_encode($users2), 200, array('Content-Type' => 'application/json'));
        }
    }
    
}