<?php

namespace DataminingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class StoreDataminingController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Datamining/Default/index.html.twig');
    }
    
    public function storeDataminingAction(Request $request)
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
            
            $dataminingListId = $this->get_dataminingListId($em, $userId, $useripId, $current_video_id);
                            
            $users2 = array();
            $users2[0] = array(
                'dataminingListId' => $dataminingListId
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
    
    public function get_dataminingListId($em, $userId, $useripId, $current_video_id)
    {
        if ($userId != 0)
        {
            $datamininglist = $em->createQuery(
                "SELECT d.datamininglistId, d.datamininglistScore 
                FROM HomeBundle:Datamininglist d 

                JOIN HomeBundle:Video v 
                WITH d.video = v.videoId 

                JOIN HomeBundle:User u 
                WITH d.user = u.userId 

                WHERE v.videoId = '$current_video_id' 
                and u.userId = '$userId' 
                    
                ORDER BY d.datamininglistScore DESC
                "
            );
        }
        else 
        {
            $datamininglist = $em->createQuery(
                "SELECT d.datamininglistId, d.datamininglistScore 
                FROM HomeBundle:Datamininglist d 

                JOIN HomeBundle:Video v 
                WITH d.video = v.videoId 

                JOIN HomeBundle:Userip ui 
                WITH d.userip = ui.useripId 

                WHERE v.videoId = '$current_video_id' 
                and ui.useripId = '$useripId' 
                    
                ORDER BY d.datamininglistScore DESC
                "
            );
        }
        
        $datamininglist_e = $datamininglist->getResult();

        
        if($datamininglist_e)
        {
            $datamininglistId = $datamininglist_e[0]['datamininglistId'];
            $datamininglistScore = $datamininglist_e[0]['datamininglistScore'];
            $this->edit_dataminingList($em, $datamininglistId, $datamininglistScore);
            return $datamininglistId;
        } else
        {
            $datamininglistId = $this->set_dataminingList($em, $userId, $useripId, $current_video_id);
            return $datamininglistId;
        }
    }
    
    function set_dataminingList($em, $userId, $useripId, $current_video_id)
    {
        $datamininglist = new \HomeBundle\Entity\Datamininglist;
        if (isset($_SESSION['loginSession'])) {
            $userId = $_SESSION['loginSession'];
            $useripId = 0;
            $userEntity = $em->getRepository('HomeBundle:User')->findOneByUserId($userId);
            $datamininglist->setUser($userEntity);
        }
        else {
            $userId = 0;
            $useripId = $this->get_useripId($em);
            $useripEntity = $em->getRepository('HomeBundle:Userip')->findOneByUseripId($useripId);
            $datamininglist->setUserip($useripEntity);
        }
        
        $todayDate = date("Y-m-d");
        $todayDate_format = date_create_from_format('Y-m-d', $todayDate);
        $datamininglist->setDatamininglistDate($todayDate_format);
        $datamininglist->setDatamininglistScore(0);
        
        $videoEntity = $em->getRepository('HomeBundle:Video')->findOneByVideoId($current_video_id);
        $datamininglist->setVideo($videoEntity);
        
        $em->persist($datamininglist);
        $em->flush();
        
        $datamininglistId = $datamininglist->getDatamininglistId();
        return $datamininglistId;
    }
    
    function edit_dataminingList($em, $datamininglistId, $datamininglistScore)
    {
        $datamininglist = $em->getRepository('HomeBundle:Datamininglist')->findOneByDatamininglistId($datamininglistId);
        $datamininglistScore++;
        $datamininglist->setDatamininglistScore($datamininglistScore);
        $todayDate = date("Y-m-d");
        $todayDate_format = date_create_from_format('Y-m-d', $todayDate);
        $datamininglist->setDatamininglistDate($todayDate_format);
        
        $em->persist($datamininglist);
        $em->flush();
    }
}