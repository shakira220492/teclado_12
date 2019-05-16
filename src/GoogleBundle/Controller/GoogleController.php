<?php

namespace GoogleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


use Google\autoload;
class GoogleController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Google/Default/index.html.twig');
    }
    
    public function googleFormAction()
    {
        // step1: enter your google account credentials
        $client = new \Google_Client();
        // $client->setAuthConfig('/path/to/client_credentials.json');
        $client->setClientId("471191626527-vktdd7p2abd8bjhu8igb9arlge6mi1df.apps.googleusercontent.com");
        $client->setClientSecret("pIbQbvTMNLO5lDK1Bxy-DEfL");
        $client->setRedirectUri("http://jbsb92.bikesdealer/y_Google/successLoginGoogle");
        $client->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
        
        // step2: create the url
        $auth_url = $client->createAuthUrl();
//        echo "<a href='$auth_url'>Login Through Google: $auth_url</a>";
        
        return $this->redirect($auth_url);
    }
    
    public function successLoginGoogleFormAction()
    {
        // step1: enter your google account credentials
        $client = new \Google_Client();
        // $client->setAuthConfig('/path/to/client_credentials.json');
        $client->setClientId("471191626527-vktdd7p2abd8bjhu8igb9arlge6mi1df.apps.googleusercontent.com");
        $client->setClientSecret("pIbQbvTMNLO5lDK1Bxy-DEfL");
        $client->setRedirectUri("http://jbsb92.bikesdealer/y_Google/successLoginGoogle");
        $client->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
        
        
        // step3: get the authorization code
        $code = isset($_GET['code']) ? $_GET['code'] : NULL;
        
        // step4: get access token
        if (isset($code)) {
            try {
                $token = $client->fetchAccessTokenWithAuthCode($code);
                $client->setAccessToken($token);
//                echo '<pre>';
//                var_dump($token);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            
            try {
                $pay_load = $client->verifyIdToken();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $pay_load = null;
        }
//        echo $pay_load['email'];
        
        
        $oAuth = new \Google_Service_Oauth2($client);
        $userData = $oAuth->userinfo_v2_me->get();
        
//        echo '<pre>';
//        var_dump($userData);
        
        $id = $userData['id'];
        $email = $userData['email'];
        $familyName = $userData['familyName'];
        $givenName = $userData['givenName'];
        $name = $userData['name'];
        $picture = $userData['picture'];
        
        $this->signUp_google(
                $email,
                $familyName,
                $givenName,
                $name,
                $picture);
        
        return $this->redirect('http://jbsb92.bikesdealer.com');
    }
    
    public function signUp_google(
        $email,
        $familyName,
        $givenName,
        $name,
        $picture)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->createQuery(
            "SELECT u.userId, u.userName 
            FROM HomeBundle:User u 
            WHERE u.userEmail = '$email'"
        );
        $users = $user->getResult();
        if (!$users) {
            $user = new \HomeBundle\Entity\User;
            $user->setUserProfilephoto($picture);
            $user->setUserName($name);
            $user->setUserFirstgivenname($givenName);
            $user->setUserFirstfamilyname($familyName);
            $user->setUserEmail($email);

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