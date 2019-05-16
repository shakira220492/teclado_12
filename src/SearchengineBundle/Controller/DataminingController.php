<?php

namespace SearchengineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DataminingController extends Controller {

    public function getMostViewedVideosAction(Request $request) {
        
        
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
                $video = $em->createQuery(
                "SELECT DISTINCT v.videoId, v.videoName, v.videoDescription, v.videoImage, 
                v.videoContent, v.videoUpdatedate, v.videoAmountViews, 
                v.videoAmountComments, v.videoLikes, v.videoDislikes, u.userId, u.userName, dl.datamininglistId 
                
                FROM HomeBundle:Video v
                
                JOIN HomeBundle:User u 
                WITH u.userId = v.user 

                JOIN HomeBundle:Datamininglist dl 
                WITH v.videoId = dl.video 
                
                WHERE 

                dl.user = '$userId' 

                ORDER BY 

                dl.datamininglistScore DESC, 
                dl.datamininglistDate DESC, 

                v.videoLikes DESC, 
                v.videoAmountViews DESC, 
                v.videoAmountComments DESC"
                )
                ->setFirstResult($amountVideosViewed)
                ->setMaxResults(30);
            } else
            {
                $video = $em->createQuery(
                "SELECT DISTINCT v.videoId, v.videoName, v.videoDescription, v.videoImage, 
                v.videoContent, v.videoUpdatedate, v.videoAmountViews, 
                v.videoAmountComments, v.videoLikes, v.videoDislikes, u.userId, u.userName, dl.datamininglistId 
                
                FROM HomeBundle:Video v 
                
                JOIN HomeBundle:User u 
                WITH u.userId = v.user 

                JOIN HomeBundle:Datamininglist dl 
                WITH v.videoId = dl.video 

                WHERE 

                dl.userip = '$useripId' 

                ORDER BY 
 
                dl.datamininglistScore DESC,
                dl.datamininglistDate DESC, 

                v.videoLikes DESC, 
                v.videoAmountViews DESC, 
                v.videoAmountComments DESC"
                )
                ->setFirstResult($amountVideosViewed)
                ->setMaxResults(30);
            }

            $videoInstance = $video->getResult();

            $amountVideos = 0;

            while (isset($videoInstance[$amountVideos]['videoId'])) {
                $amountVideos++;
            }

            $i = 0;
            while (isset($videoInstance[$i]['videoId'])) {

                $videoUpdatedate = $videoInstance[$i]['videoUpdatedate'];
                $videoUpdatedateString = $videoUpdatedate->format('d-M-Y');

                $videoAmountViews = $videoInstance[$i]['videoAmountViews'];
                $videoAmountViewsFormat = number_format($videoAmountViews);

                $videoAmountComments = $videoInstance[$i]['videoAmountComments'];
                $videoAmountCommentsFormat = number_format($videoAmountComments);

                if ($videoInstance) {
                    $videoId_Value = $videoInstance[$i]['videoId'];
                    $videoName_Value = $videoInstance[$i]['videoName'];
                    $videoDescription_Value = $videoInstance[$i]['videoDescription'];
                    $videoImage_Value = $videoInstance[$i]['videoImage'];
                    $videoContent_Value = $videoInstance[$i]['videoContent'];
                    $videoUpdatedate_Value = $videoUpdatedateString;
                    $videoAmountViews_Value = $videoAmountViewsFormat;
                    $videoAmountComments_Value = $videoAmountCommentsFormat;
                    $videoLikes_Value = $videoInstance[$i]['videoLikes'];
                    $videoDislikes_Value = $videoInstance[$i]['videoDislikes'];
                    $userId_Value = $videoInstance[$i]['userId'];
                    $userName_Value = $videoInstance[$i]['userName'];
                    $datamininglistId_Value = $videoInstance[$i]['datamininglistId'];
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
                    $datamininglistId_Value = "_";
                }

                $sendData[$i] = array(
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
                    'datamininglistId' => $datamininglistId_Value,
                    'amountVideos' => $amountVideos
                );
                $i++;
            }

            if ($i == 0) {
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

                $sendData[0] = array(
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
                    'amountVideos' => 0
                );
            }

            return new Response(json_encode($sendData), 200, array('Content-Type' => 'application/json'));
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

            $sendData[0] = array(
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
                'amountVideos' => 0
            );
            return new Response(json_encode($sendData), 200, array('Content-Type' => 'application/json'));
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
