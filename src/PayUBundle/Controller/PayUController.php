<?php

namespace PayUBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use PayU\api\Environment;
use PayU\api\PaymentMethods;
use PayU\api\PayUCommands;
use PayU\api\PayUConfig;
use PayU\api\PayUCountries;
use PayU\api\PayUHttpRequestInfo;
use PayU\api\PayUKeyMapName;
use PayU\api\PayuPaymentMethodType;
use PayU\api\PayUResponseCode;
use PayU\api\PayUTransactionResponseCode;
use PayU\api\RequestMethod;
use PayU\api\SupportedLanguages;
use PayU\api\TransactionType;


class PayUController extends Controller {

    public function indexAction() {
        return $this->render('@PayU/Default/index.html.twig');
    }
    
    
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
    

    public function PayUFormAction() {
//        merchantId 508029
//        API Login pRRXKOl8ikMmt9u
//        API Key	4Vj8eK4rloUd272L48hsrarnUA
//        accountId 512321
//        País Colombia

//        return new Response("pay u form ");
        
        return $this->redirect("https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/");
        
    }

}
