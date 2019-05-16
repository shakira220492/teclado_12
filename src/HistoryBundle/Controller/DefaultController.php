<?php

namespace HistoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@History/Default/index.html.twig');
    }
    
    public function storeHistoryAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $current_video_id = $_POST['current_video_id'];

            if (isset($_SESSION['loginSession'])) {
                $userId = $_SESSION['loginSession'];
                $useripId = 0;
            }
            else {
                $userId = 0;
                $useripId = $this->get_useripId($em);
            }
            
            $videoEntity = $em->getRepository('HomeBundle:Video')->findOneByVideoId($current_video_id);
            $todayDate = date("Y-m-d");
            $todayDate_format = date_create_from_format('Y-m-d', $todayDate);
            
            $history = new \HomeBundle\Entity\History;
            if ($userId === 0)
            {
                $useripEntity = $em->getRepository('HomeBundle:Userip')->findOneByUseripId($useripId);
                $history->setUserip($useripEntity);
            } else if ($useripId === 0)
            {
                $userEntity = $em->getRepository('HomeBundle:User')->findOneByUserId($userId);
                $history->setUser($userEntity);
            }
            $history->setVideo($videoEntity);
            $history->setHistoryDate($todayDate_format);
            
            $em->persist($history);
            $em->flush();
                            
            $users2 = array();
            $users2[0] = array(
                'userId' => $userId,
                'useripId' => $useripId,
                'current_video_id' => $current_video_id
            );
            return new Response(json_encode($users2), 200, array('Content-Type' => 'application/json'));
        }
    }
    
    
    
    public function get_useripId($em)
    {
        $geolocalization = $_SERVER["REMOTE_ADDR"];
        
        $existentedUserip = $em->createQuery(
            "SELECT ui.useripId 
            FROM HomeBundle:Userip ui 
            WHERE ui.useripGeolocalization = '$geolocalization'"
        );
        
        $existentedUserip_v = $existentedUserip->getResult();

        if (isset($existentedUserip_v[0]['useripId'])) {
            $useripId = $existentedUserip_v[0]['useripId'];
        } else {
            $useripId = $this->insertar_userip($em, $geolocalization);
        }
        return $useripId;
    }
    
    public function insertar_userip($em, $geolocalization)
    {
        $userip = new \HomeBundle\Entity\Userip;
        $userip->setUseripGeolocalization($geolocalization);
        $em->persist($userip);
        $em->flush();
        
        $existentedUserip = $em->createQuery(
            "SELECT ui.useripId 
            FROM HomeBundle:Userip ui 
            WHERE ui.useripGeolocalization = '$geolocalization'"
        );
        $existentedUserip_v = $existentedUserip->getResult();
        $useripId = $existentedUserip_v[0]['useripId'];
        
        return $useripId;
    }
    
    
        
}
