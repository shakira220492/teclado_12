<?php

namespace SearchengineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class HistoryController extends Controller {

    public function getHistorySongsAction(Request $request)
    {
        
        $amountVideosViewed = $_POST["amountVideosViewed"];
        
        if ($request->isXMLHttpRequest()) {

            $em = $this->getDoctrine()->getManager();

            if (isset($_SESSION['loginSession'])) {
                $userId = $_SESSION['loginSession'];
                $useripId = 0;
            }
            else {
                $userId = 0;
                $useripId = $this->get_useripId($em);
            }
            
            
            if ($userId != 0)
            {
                $history = $em->createQuery(
                    "SELECT v.videoId, v.videoName, v.videoDescription, v.videoImage, 
                    v.videoContent, v.videoUpdatedate, v.videoAmountViews, 
                    v.videoAmountComments, v.videoLikes, v.videoDislikes, u.userId, u.userName, h.historyId 
                    FROM HomeBundle:History h 

                    JOIN HomeBundle:Video v 
                    WITH v.videoId = h.video 

                    JOIN HomeBundle:User u 
                    WITH u.userId = h.user 

                    WHERE h.user = '$userId'

                    ORDER BY h.historyDate DESC"
                )
                ->setFirstResult($amountVideosViewed)
                ->setMaxResults(30);
            } else
            {
                $history = $em->createQuery(
                    "SELECT v.videoId, v.videoName, v.videoDescription, v.videoImage, 
                    v.videoContent, v.videoUpdatedate, v.videoAmountViews, 
                    v.videoAmountComments, v.videoLikes, v.videoDislikes, u.userId, u.userName, h.historyId 
                    FROM HomeBundle:History h 

                    JOIN HomeBundle:Video v 
                    WITH v.videoId = h.video 

                    JOIN HomeBundle:Userip ui 
                    WITH ui.useripId = h.userip 

                    JOIN HomeBundle:User u 
                    WITH u.userId = v.user 

                    WHERE h.userip = '$useripId'

                    ORDER BY h.historyDate DESC"
                )
                ->setFirstResult($amountVideosViewed)
                ->setMaxResults(30);
            }
            

            $history_v = $history->getResult();
                        
            $amountVideos = 0;
            while (isset($history_v[$amountVideos]['videoId'])) {
                $amountVideos++;
            }
            
            $i = 0;
            while (isset($history_v[$i]['videoId'])) {

                $videoUpdatedate = $history_v[$i]['videoUpdatedate'];
                $videoUpdatedateString = $videoUpdatedate->format('d-M-Y');

                $videoAmountViews = $history_v[$i]['videoAmountViews'];
                $videoAmountViewsFormat = number_format($videoAmountViews);

                $videoAmountComments = $history_v[$i]['videoAmountComments'];
                $videoAmountCommentsFormat = number_format($videoAmountComments);

                if ($history_v) {
                    $videoId_Value = $history_v[$i]['videoId'];
                    $videoName_Value = $history_v[$i]['videoName'];
                    $videoDescription_Value = $history_v[$i]['videoDescription'];
                    $videoImage_Value = $history_v[$i]['videoImage'];
                    $videoContent_Value = $history_v[$i]['videoContent'];
                    $videoUpdatedate_Value = $videoUpdatedateString;
                    $videoAmountViews_Value = $videoAmountViewsFormat;
                    $videoAmountComments_Value = $videoAmountCommentsFormat;
                    $videoLikes_Value = $history_v[$i]['videoLikes'];
                    $videoDislikes_Value = $history_v[$i]['videoDislikes'];
                    $userId_Value = $history_v[$i]['userId'];
                    $userName_Value = $history_v[$i]['userName'];
                    $historyId_Value = $history_v[$i]['historyId'];
                    $amountVideos_Value = $amountVideos;
                } else {
                    $videoId_Value = "_";
                    $videoName_Value = "_";
                    $videoDescription_Value = "_";
                    $videoImage_Value = "_";
                    $videoContent_Value = "_";
                    $videoUpdatedate_Value = "_";
                    $videoAmountViews_Value = "_";
                    $videoAmountComments_Value = "_";
                    $videoLikes_Value = "_";
                    $videoDislikes_Value = "_";
                    $userId_Value = "_";
                    $userName_Value = "_";
                    $historyId_Value = "_";
                    $amountVideos_Value = $amountVideos;
                }

                $videoFromUser[$i] = array(                    
                    'videoId' => $videoId_Value,
                    'videoName' => $videoName_Value,
                    'videoDescription' => $videoDescription_Value,
                    'videoImage' => $videoImage_Value,
                    'videoContent' => $videoContent_Value,
                    'videoUpdatedate' => $videoUpdatedate_Value,
                    'videoAmountViews' => $videoAmountViews_Value,
                    'videoAmountComments' => $videoAmountComments_Value,
                    'videoLikes' => $videoLikes_Value,
                    'videoDislikes' => $videoDislikes_Value,
                    'userId' => $userId_Value,
                    'userName' => $userName_Value,
                    'historyId' => $historyId_Value,
                    'amountVideos' => $amountVideos_Value
                );
                $i++;
            }
            
            if (isset($history_v[0]['videoId'])) {
                
            } else
            {
                $videoFromUser[0] = array(                    
                    'videoId' => "-",
                    'videoName' => "-",
                    'videoDescription' => "-",
                    'videoImage' => "-",
                    'videoContent' => "-",
                    'videoUpdatedate' => "-",
                    'videoAmountViews' => "-",
                    'videoAmountComments' => "-",
                    'videoLikes' => "-",
                    'videoDislikes' => "-",
                    'userId' => "-",
                    'userName' => "-",
                    'historyId' => "-",
                    'amountVideos' => "-"
                );
            }
            
            return new Response(json_encode($videoFromUser), 200, array('Content-Type' => 'application/json'));
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
