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


class DonatedVideoController extends Controller
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
    
    public function donatedVideoFormAction()
    {
        $subtotal = 0;
        
        $payer = new Payer(); // 'objeto paypal' va a contener informacion del cliente que va a hacer el pago
        $payer->setPaymentMethod('paypal'); // método de pago
        
        $sendData = $this->getDonateAmount();
        
        $amountItems = $sendData[0]["amountSelectedProducts"];
//        $amountItems = $sendData[0][8];
        
//                $sendData = array(
//                    'selectedproductId' => $selectedproductId_Value,
//                    'selectedproductDate' => $selectedproductDate_Value,
//                    'selectedproductAmount' => $selectedproductAmount_Value,
//                    'productId' => $productId_Value,
//                    'productName' => $productName_Value,
//                    'productPrice' => $productPrice_Value,
//                    'productDescription' => $productDescription_Value,
//                    'productImage' => $productImage_Value,
//                    'amountSelectedProducts' => 0
//                );
        
//        $amountItems = $sendData["amountSelectedProducts"];
        
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
    
    public function donatedVideoSuccessAction()
    {
        return new Response("success");
    }
    
    public function donatedVideoFailureAction()
    {
        return new Response("failure");
    }
    
    public function getDonatedVideo()
    {
        $em = $this->getDoctrine()->getManager();
        
        if (isset($_SESSION['loginSession'])) {
            $userId = $_SESSION['loginSession'];
            
            $donatedVideo = $em->createQuery(
                "SELECT DISTINCT dv.donatedvideoId, dv.donatedvideoCoinAmount, 
                    v.videoId, v.videoName, v.videoDescription, v.videoImage, v.videoContent, 
                    v.videoUpdatedate, v.videoAmountViews, v.videoAmountComments, 
                    v.videoLikes, v.videoDislikes, 
                    u.userId 

                FROM HomeBundle:Donatedvideo dv 

                JOIN HomeBundle:Video v 
                WITH v.videoId = dv.video 

                JOIN HomeBundle:User u 
                WITH u.userId = dv.user 

                WHERE u.userId = '$userId'
                "
            );

            $donatedVideo_instance = $donatedVideo->getResult();            
            
            $amountDonatedVideos = 0;
            while (isset($donatedVideo_instance[$amountDonatedVideos]['donatedvideoId'])) {
                $amountDonatedVideos++;
            }

            $i = 0;
            while (isset($donatedVideo_instance[$i]['donatedvideoId'])) {
                
                $donatedvideoId_Value = $donatedVideo_instance[$i]['donatedvideoId'];
                $donatedvideoCoinAmount_Value = $donatedVideo_instance[$i]['donatedvideoCoinAmount'];
                $videoId_Value = $donatedVideo_instance[$i]['videoId'];
                $videoName_Value = $donatedVideo_instance[$i]['videoName'];
                $videoDescription_Value = $donatedVideo_instance[$i]['videoDescription'];
                $videoImage_Value = $donatedVideo_instance[$i]['videoImage'];
                $videoContent_Value = $donatedVideo_instance[$i]['videoContent'];
                $videoUpdatedate_Value = $donatedVideo_instance[$i]['videoUpdatedate'];
                $videoAmountViews_Value = $donatedVideo_instance[$i]['videoAmountViews'];
                $videoAmountComments_Value = $donatedVideo_instance[$i]['videoAmountComments'];
                $videoLikes_Value = $donatedVideo_instance[$i]['videoLikes'];
                $videoDislikes_Value = $donatedVideo_instance[$i]['videoDislikes'];
                $userId_Value = $donatedVideo_instance[$i]['userId'];
                
                $sendData[$i]['donatedvideoId'] = $donatedvideoId_Value;
                $sendData[$i]['donatedvideoCoinAmount'] = $donatedvideoCoinAmount_Value;
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
                $donatedvideoId_Value = "_";
                $donatedvideoCoinAmount_Value = "_";
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
                        'donatedvideoId' => $donatedvideoId_Value,
                        'donatedvideoCoinAmount' => $donatedvideoCoinAmount_Value,
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