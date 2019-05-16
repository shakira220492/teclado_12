<?php

namespace PaypalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Authorization;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;


class PromotedVideoController extends Controller
{
    private $_api_context;
    
    public function __construct()
    {
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                'AVo8o2-r-dcBd4jyzYMU5KcwcIus_Yrt8YpXjaLQtMnwOZ-1KviHTju1MPjb5PT45rutHTTFasZiNO7g', 
                'EDi6Opi8fzI6AsyHV_0MRSToNnml4ClcBpzHZ9v7Nt_sCp1Ba8rEyd4QfKnGC0PeuRjTuzluLc_ySkIc'
            )
        );

        $apiContext->setConfig([
            'mode' => 'sandbox',
            'log.LogEnabled' => true,
            'log.FileName' => '../PayPal.log',
            'log.LogLevel' => 'DEBUG',
            'cache.enabled' => true,
            ]);

        $this->apiContext = $apiContext;
    }
    
    public function promotedVideoFormAction()
    {
        $subtotal = 0;
        
        $payer = new Payer(); // 'objeto paypal' va a contener informacion del cliente que va a hacer el pago
        $payer->setPaymentMethod('paypal'); // método de pago
        
        $sendData = $this->getVideoCoin();
        
        $amountItems = $sendData[0]["amountSelectedProducts"];
        
        $itemList = new ItemList(); // 'objeto paypal'
        
        for ($i=0; $i<$amountItems; $i++)
        {
            $productName = $sendData[$i]["productName"];
            $productPrice = $sendData[$i]["productPrice"];
            $selectedproductAmount = $sendData[$i]["selectedproductAmount"];
            
            for ($j=0; $j<$selectedproductAmount; $j++)
            {
                $item = new Item(); // 'objeto paypal'
                $item->setName($productName)
                    ->setCurrency('USD')
                    ->setQuantity($selectedproductAmount)
                    ->setPrice($productPrice);

                $totalProductPrice = $selectedproductAmount * $productPrice;
            }
                $itemList->addItem($item);
                $subtotal += $totalProductPrice;
        }
        
        $shipping = 0;
        $total = $subtotal + $shipping;
        
        
        $details = new Details(); // 'objeto paypal'
        $details->setShipping(0)
                ->setSubtotal($subtotal);
        
        
        $amount = new Amount(); // 'objeto paypal'
        $amount->setCurrency('USD')
            ->setTotal($total)
            ->setDetails($details);
        
        $transaction = new Transaction(); // 'objeto paypal'
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription('Description: camiseta blanca')
            ->setInvoiceNumber(uniqid());
        
        $baseUrl = "http://jbsb92.bikesdealer.com";
        $redirectUrls = new RedirectUrls(); // 'objeto paypal'
        $redirectUrls->setReturnUrl($baseUrl.'/t_Paypal/promotedVideoSuccess')
                     ->setCancelUrl($baseUrl.'/t_Paypal/promotedVideoFailure');
        
        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        
        try {
            $payment->create($this->apiContext);
            
            return $this->redirect($payment->getApprovalLink());

        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            exit(1);
        }
    }
    
    public function promotedVideoSuccessAction()
    {
        return new Response("success");
    }
    
    public function promotedVideoFailureAction()
    {
        return new Response("failure");
    }
    
    public function getPromotedVideo()
    {
        $em = $this->getDoctrine()->getManager();
        
        if (isset($_SESSION['loginSession'])) {
            $userId = $_SESSION['loginSession'];
            
            $promotedVideo = $em->createQuery(
                "SELECT DISTINCT pv.promotedvideoId, pv.promotedvideoCoinAmount, 
                    v.videoId, v.videoName, v.videoDescription, v.videoImage, v.videoContent, 
                    v.videoUpdatedate, v.videoAmountViews, v.videoAmountComments, 
                    v.videoLikes, v.videoDislikes, 
                    u.userId 

                FROM HomeBundle:Promotedvideo pv 

                JOIN HomeBundle:Video v 
                WITH v.videoId = pv.video 

                JOIN HomeBundle:User u 
                WITH u.userId = pv.user 

                WHERE u.userId = '$userId'
                "
            );

            $promotedVideo_instance = $promotedVideo->getResult();            
            
            $amountPromotedVideos = 0;
            while (isset($promotedVideo_instance[$amountPromotedVideos]['promotedvideoId'])) {
                $amountPromotedVideos++;
            }

            $i = 0;
            while (isset($promotedVideo_instance[$i]['promotedvideoId'])) {
                
                $promotedvideoId_Value = $promotedVideo_instance[$i]['promotedvideoId'];
                $promotedvideoCoinAmount_Value = $promotedVideo_instance[$i]['promotedvideoCoinAmount'];
                $videoId_Value = $promotedVideo_instance[$i]['videoId'];
                $videoName_Value = $promotedVideo_instance[$i]['videoName'];
                $videoDescription_Value = $promotedVideo_instance[$i]['videoDescription'];
                $videoImage_Value = $promotedVideo_instance[$i]['videoImage'];
                $videoContent_Value = $promotedVideo_instance[$i]['videoContent'];
                $videoUpdatedate_Value = $promotedVideo_instance[$i]['videoUpdatedate'];
                $videoAmountViews_Value = $promotedVideo_instance[$i]['videoAmountViews'];
                $videoAmountComments_Value = $promotedVideo_instance[$i]['videoAmountComments'];
                $videoLikes_Value = $promotedVideo_instance[$i]['videoLikes'];
                $videoDislikes_Value = $promotedVideo_instance[$i]['videoDislikes'];
                $userId_Value = $promotedVideo_instance[$i]['userId'];
                
                $sendData[$i]['promotedvideoId'] = $promotedvideoId_Value;
                $sendData[$i]['promotedvideoCoinAmount'] = $promotedvideoCoinAmount_Value;
                $sendData[$i]['videoId'] = $videoId_Value;
                $sendData[$i]['videoName'] = $videoName_Value;
                $sendData[$i]['videoDescription'] = $videoDescription_Value;
                $sendData[$i]['videoImage'] = $videoImage_Value;
                $sendData[$i]['videoContent'] = $videoContent_Value;
                $sendData[$i]['videoUpdatedate'] = $videoUpdatedate_Value;
                $sendData[$i]['videoAmountViews'] = $videoAmountViews_Value;
                $sendData[$i]['videoAmountComments'] = $videoAmountComments_Value;
                $sendData[$i]['videoLikes'] = $videoLikes_Value;
                $sendData[$i]['videoDislikes'] = $videoDislikes_Value;
                $sendData[$i]['userId'] = $userId_Value;
                
                $i++;
            }

            if ( $i === 0 )
            {
                $promotedvideoId_Value = "_";
                $promotedvideoCoinAmount_Value = "_";
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
                
                
                
                $sendData = array(
                    array(
                        'promotedvideoId' => $promotedvideoId_Value,
                        'promotedvideoCoinAmount' => $promotedvideoCoinAmount_Value,
                        'videoId' => $videoId_Value,
                        'videoName' => $videoName_Value,
                        'videoDescription' => $videoDescription_Value,
                        'videoImage' => $videoImage_Value,
                        'videoContent' => $videoContent_Value,
                        'videoUpdatedate' => $videoUpdatedate_Value,
                        'videoAmountComments' => $videoAmountComments_Value,
                        'videoLikes' => $videoLikes_Value,
                        'videoDislikes' => $videoDislikes_Value,
                        'userId' => $userId_Value
                    )
                );
            }
        }
        
        return $sendData;
    }
    
}