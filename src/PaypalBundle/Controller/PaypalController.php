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


class PaypalController extends Controller
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
    
    public function paypalFormAction()
    {
        $subtotal = 0;
        
        $payer = new Payer(); // 'objeto paypal' va a contener informacion del cliente que va a hacer el pago
        $payer->setPaymentMethod('paypal'); // método de pago
        
        $sendData = $this->getItems();
        
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
        $redirectUrls->setReturnUrl($baseUrl.'/t_Paypal/success')
                     ->setCancelUrl($baseUrl.'/t_Paypal/failure');
        
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
    
    public function successAction()
    {
        
        $token = $_GET['token'];
        $paymentId = $_GET['paymentId'];
        $PayerID = $_GET['PayerID'];
        
//////////////////////////////////////////////////////////////////////////////////////////
// EJECUTAR EL PAGO 
        
        $payment = Payment::get($paymentId, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($PayerID);
        $payment->execute($execution, $this->apiContext);
        
//////////////////////////////////////////////////////////////////////////////////////////
// OBTENER EL TOKEN DEL PAGO        
        
        $login = curl_init("https://api.sandbox.paypal.com/v1/oauth2/token");
        curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($login, CURLOPT_USERPWD,'AVo8o2-r-dcBd4jyzYMU5KcwcIus_Yrt8YpXjaLQtMnwOZ-1KviHTju1MPjb5PT45rutHTTFasZiNO7g'.":".'EDi6Opi8fzI6AsyHV_0MRSToNnml4ClcBpzHZ9v7Nt_sCp1Ba8rEyd4QfKnGC0PeuRjTuzluLc_ySkIc');
        curl_setopt($login, CURLOPT_POSTFIELDS,'grant_type=client_credentials');
        
        $respuesta = curl_exec($login);
        
        $objRespuesta = json_decode($respuesta);

        $accessToken = $objRespuesta->access_token; // es para consultar los datos de la compra mediante el acces token
        
//////////////////////////////////////////////////////////////////////////////////////////
// VERIFICADOR DEL PAGO, OBTENER INFORMACION DEL PAGO
        
        $venta = curl_init("https://api.sandbox.paypal.com/v1/payments/payment/".$paymentId); // consultar la informacion de ese pago
        curl_setopt($venta,CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: Bearer ".$accessToken));
        curl_setopt($venta,CURLOPT_RETURNTRANSFER,TRUE);
        
        $respuestaVenta = curl_exec($venta);
        $objDatosTransaccion = json_decode($respuestaVenta);
        
        $state = $objDatosTransaccion->state;
        $email = $objDatosTransaccion->payer->payer_info->email;
        
        $total = $objDatosTransaccion->transactions[0]->amount->total;
        $currency = $objDatosTransaccion->transactions[0]->amount->currency;
//        $custom = $objDatosTransaccion->transactions[0]->custom;
//        
        print_r($objDatosTransaccion);
        
//////////////////////////////////////////////////////////////////////////////////////////        
        
        curl_close($venta);
        curl_close($login);
        return new Response(" ");
    }
    
    public function failureAction()
    {
        return new Response("failure");
    }
    
    public function getItems()
    {
        $em = $this->getDoctrine()->getManager();

        if (isset($_SESSION['loginSession'])) {
            $userId = $_SESSION['loginSession'];

            $selectedProduct = $em->createQuery(
                "SELECT DISTINCT sp.selectedproductId, sp.selectedproductDate, sp.selectedproductAmount, 
                    p.productId, p.productName, p.productPrice, 
                    p.productDescription, p.productImage 
                FROM HomeBundle:Selectedproduct sp 

                JOIN HomeBundle:Product p 
                WITH p.productId = sp.product 

                JOIN HomeBundle:User u 
                WITH u.userId = sp.user 

                WHERE u.userId = '$userId'
                "
            );

            $selectedProductInstance = $selectedProduct->getResult();

            $amountSelectedProducts = 0;

            while (isset($selectedProductInstance[$amountSelectedProducts]['selectedproductId'])) {
                $amountSelectedProducts++;
            }

            $i = 0;
            while (isset($selectedProductInstance[$i]['selectedproductId'])) {
                $selectedproductDate = $selectedProductInstance[$i]['selectedproductDate'];
                $selectedproductDateString = $selectedproductDate->format('d-M-Y');

                $selectedproductId_Value = $selectedProductInstance[$i]['selectedproductId'];
                $selectedproductDate_Value = $selectedproductDateString;
                $selectedproductAmount_Value = $selectedProductInstance[$i]['selectedproductAmount'];
                $productId_Value = $selectedProductInstance[$i]['productId'];
                $productName_Value = $selectedProductInstance[$i]['productName'];
                $productPrice_Value = $selectedProductInstance[$i]['productPrice'];
                $productDescription_Value = $selectedProductInstance[$i]['productDescription'];
                $productImage_Value = $selectedProductInstance[$i]['productImage'];

                $sendData[$i]['selectedproductId'] = $selectedproductId_Value;
                $sendData[$i]['selectedproductDate'] = $selectedproductDate_Value;
                $sendData[$i]['selectedproductAmount'] = $selectedproductAmount_Value;
                $sendData[$i]['productId'] = $productId_Value;
                $sendData[$i]['productName'] = $productName_Value;
                $sendData[$i]['productPrice'] = $productPrice_Value;
                $sendData[$i]['productDescription'] = $productDescription_Value;
                $sendData[$i]['productImage'] = $productImage_Value;
                $sendData[$i]['amountSelectedProducts'] = $amountSelectedProducts;

                $i++;
            }

            if ( $i === 0 )
            {
                $selectedproductId_Value = "_";
                $selectedproductDate_Value = "_";
                $selectedproductAmount_Value = "_";
                $productId_Value = "_";
                $productName_Value = "_";
                $productPrice_Value = "_";
                $productDescription_Value = "_";
                $productImage_Value = "_";

                
                $sendData = array(
                    array(
                        'selectedproductId' => $selectedproductId_Value,
                        'selectedproductDate' => $selectedproductDate_Value,
                        'selectedproductAmount' => $selectedproductAmount_Value,
                        'productId' => $productId_Value,
                        'productName' => $productName_Value,
                        'productPrice' => 0,
                        'productDescription' => $productDescription_Value,
                        'productImage' => $productImage_Value,
                        'amountSelectedProducts' => 0
                    )
                );
            }

        }
        
        return $sendData;
        
    }
}