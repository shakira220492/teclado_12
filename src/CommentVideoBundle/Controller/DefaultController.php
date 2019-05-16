<?php

namespace CommentVideoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@CommentVideo/Default/index.html.twig');
    }
    
    public function getCommentAboutVideoAction(Request $request) {
        if ($request->isXMLHttpRequest()) {

            $current_video_id = $_POST['current_video_id'];
            $startPosition = $_POST['startPositionComments'];
            $amountComments = $_POST['amountComments'];
            
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                "SELECT c.commentId, c.commentContent, c.commentLikes, c.commentDislikes, c.commentCreationdate, 
                u.userId, u.userName, 
                v.videoId, v.videoAmountComments 
                FROM HomeBundle:Comment c 
                
                JOIN HomeBundle:User u 
                WITH u.userId = c.user 
                
                JOIN HomeBundle:Video v 
                WITH v.videoId = c.video 
                
                WHERE v.videoId = '$current_video_id' 
                ORDER BY c.commentCreationdate DESC "
            )
                ->setFirstResult($startPosition) 
                ->setMaxResults($amountComments); 

            $comment = $query->getResult();                

            if (isset($comment[0]['commentId'])) {
                $commentId_Value = $comment[0]['commentId'];
                $commentContent_Value = $comment[0]['commentContent'];
                $commentLikes_Value = $comment[0]['commentLikes'];
                $commentDislikes_Value = $comment[0]['commentDislikes'];
                $commentCreationdate_Value = $comment[0]['commentCreationdate'];
                $userId_Value = $comment[0]['userId'];
                $userName_Value = $comment[0]['userName'];
                $videoId_Value = $comment[0]['videoId'];
                $videoAmountComments_Value = $comment[0]['videoAmountComments'];

                $users2[0] = array(
                    'commentId' => $commentId_Value,
                    'commentContent' => $commentContent_Value,
                    'commentLikes' => $commentLikes_Value,
                    'commentDislikes' => $commentDislikes_Value,
                    'commentCreationdate' => $commentCreationdate_Value,
                    'userId' => $userId_Value,
                    'userName' => $userName_Value,
                    'videoId' => $videoId_Value,
                    'videoAmountComments' => $videoAmountComments_Value
                );
            } else {
                $users2[0] = array(
                    'commentId' => "_",
                    'commentContent' => "_",
                    'commentLikes' => "_",
                    'commentDislikes' => "_",
                    'commentCreationdate' => "_",
                    'userId' => "_",
                    'userName' => "_",
                    'videoId' => "_",
                    'videoAmountComments' => "_"
                );
            }

            return new Response(json_encode($users2), 200, array('Content-Type' => 'application/json'));
        }
    }

    public function getCommentsAboutVideoAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {

            $current_video_id = $_POST['current_video_id'];
            $startPosition = $_POST['startPositionComments'];
            $amountComments = $_POST['amountComments'];
            
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                "SELECT c.commentId, c.commentContent, c.commentLikes, c.commentDislikes, c.commentCreationdate, 
                u.userId, u.userName, 
                v.videoId, v.videoAmountComments 
                FROM HomeBundle:Comment c 
                
                JOIN HomeBundle:User u 
                WITH u.userId = c.user 
                
                JOIN HomeBundle:Video v 
                WITH v.videoId = c.video 
                
                WHERE v.videoId = '$current_video_id' 
                ORDER BY c.commentCreationdate DESC "
            )
                ->setFirstResult($startPosition) 
                ->setMaxResults($amountComments); 

            $comment = $query->getResult();                

            
            $amountCommentsFound = 0;

            while (isset($comment[$amountCommentsFound]['commentId'])) {
                $amountCommentsFound++;
            }
            
                $i = 0;
                while (isset($comment[$i]['commentId'])) {
                    
                    if ($comment) {
                        $commentId_Value = $comment[$i]['commentId'];
                        $commentContent_Value = $comment[$i]['commentContent'];
                        $commentLikes_Value = $comment[$i]['commentLikes'];
                        $commentDislikes_Value = $comment[$i]['commentDislikes'];
                        $commentCreationdate_Value = $comment[$i]['commentCreationdate'];
                        $userName_Value = $comment[$i]['userName'];
                    } else {
                        $commentId_Value = "_";
                        $commentContent_Value = "_";
                        $commentLikes_Value = "_";
                        $commentDislikes_Value = "_";
                        $commentCreationdate_Value = "_";
                        $userName_Value = "_";
                    }

                    $sendData[$i] = array(
                        'commentId' => $commentId_Value,
                        'commentContent' => $commentContent_Value,
                        'commentLikes' => $commentLikes_Value,
                        'commentDislikes' => $commentDislikes_Value,
                        'commentCreationdate' => $commentCreationdate_Value,
                        'userName' => $userName_Value,
                        'amountCommentsFound' => $amountCommentsFound
                    );
                    $i++;
                }

                if ($i == 0) {
                    $commentId_Value = "_";
                    $commentContent_Value = "_";
                    $commentLikes_Value = "_";
                    $commentDislikes_Value = "_";
                    $commentCreationdate_Value = "_";
                    $userName_Value = "_";

                    $sendData[0] = array(
                        'commentId' => $commentId_Value,
                        'commentContent' => $commentContent_Value,
                        'commentLikes' => $commentLikes_Value,
                        'commentDislikes' => $commentDislikes_Value,
                        'commentCreationdate' => $commentCreationdate_Value,
                        'userName' => $userName_Value,
                        'amountCommentsFound' => $amountCommentsFound
                    );
                }

            return new Response(json_encode($sendData), 200, array('Content-Type' => 'application/json'));
        }
    }
    
    public function addCommentVideoAction(Request $request) {
        if ($request->isXMLHttpRequest()) {

            $em = $this->getDoctrine()->getManager();

            if (isset($_SESSION['loginSession'])) {
                $userId = $_SESSION['loginSession'];
            }
            else {
                $userId = 0;
            }
            
            $current_video_id = $_POST['current_video_id'];
            $commentContent = $_POST['commentContent'];

            if ($current_video_id === "" || $commentContent === "" || $userId === 0) {
                $status = "0";
            } else
            {
                $user = $em->getRepository('HomeBundle:User')->findOneByUserId($userId);
                $video = $em->getRepository('HomeBundle:Video')->findOneByVideoId($current_video_id);

                $todayDate = date("Y-m-d");
                $commentDate = date_create_from_format('Y-m-d', $todayDate);
                
                
                $comment = new \HomeBundle\Entity\Comment();
                $comment->setCommentContent($commentContent);
                $comment->setUser($user);
                $comment->setVideo($video);
                $comment->setCommentLikes(0);
                $comment->setCommentDislikes(0);
                $comment->setCommentCreationdate($commentDate);

                $em->persist($comment);
                $em->flush();
                
                $status = "1";
            }
            

            $users2 = array();
            $users2[0] = array(
                'status' => $status
            );
            return new Response(json_encode($users2), 200, array('Content-Type' => 'application/json'));
        }
    }
    
    public function increaseCommentsAmountVideoAction(Request $request) {
        if ($request->isXMLHttpRequest()) {

            $em = $this->getDoctrine()->getManager();
            
            $current_video_id = $_POST['current_video_id'];
            
            if ($current_video_id) {
                $video = $em->getRepository('HomeBundle:Video')->findOneByVideoId($current_video_id);

                $amountComments = $video->getVideoAmountComments();
                $newAmountComments = $amountComments + 1;

                $video->setVideoAmountComments($newAmountComments);
                $em->persist($video);
                $em->flush();
                
                $status = "1";
            } else
            {
                $status = "0";
            }

            $users2 = array();
            $users2[0] = array(
                'status' => $status
            );
            return new Response(json_encode($users2), 200, array('Content-Type' => 'application/json'));
        }
    }
    
}
