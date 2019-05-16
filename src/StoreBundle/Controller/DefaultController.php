<?php

namespace StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Store/Default/index.html.twig');
    }
    
    public function getProductsFormAction(Request $request)
    {
        $amountProductsViewed = $_POST["amountProductsViewed"];
        
        if ($request->isXMLHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            
            $product = $em->createQuery(
                "SELECT DISTINCT p.productId, p.productName, p.productPrice, 
                p.productDescription, p.productImage 
                FROM HomeBundle:Product p"
            )
            ->setFirstResult($amountProductsViewed)
            ->setMaxResults(30);
            

            $productInstance = $product->getResult();

            $amountProducts = 0;

            while (isset($productInstance[$amountProducts]['productId'])) {
                $amountProducts++;
            }
            
            $i = 0;
            while (isset($productInstance[$i]['productId'])) {
                $productId_Value = $productInstance[$i]['productId'];
                $productName_Value = $productInstance[$i]['productName'];
                $productPrice_Value = $productInstance[$i]['productPrice'];
                $productDescription_Value = $productInstance[$i]['productDescription'];
                $productImage_Value = $productInstance[$i]['productImage'];

                $sendData[$i] = array(
                    'productId' => $productId_Value,
                    'productName' => $productName_Value,
                    'productPrice' => $productPrice_Value,
                    'productDescription' => $productDescription_Value,
                    'productImage' => $productImage_Value,
                    'amountProducts' => $amountProducts
                );
                
                $i++;
            }
            
            if ( $i === 0 )
            {
                $productId_Value = "_";
                $productName_Value = "_";
                $productPrice_Value = "_";
                $productDescription_Value = "_";
                $productImage_Value = "_";
                
                $sendData[0] = array(
                    'productId' => $productId_Value,
                    'productName' => $productName_Value,
                    'productPrice' => $productPrice_Value,
                    'productDescription' => $productDescription_Value,
                    'productImage' => $productImage_Value,
                    'amountProducts' => $amountProducts
                );
            }
            
            return new Response(json_encode($sendData), 200, array('Content-Type' => 'application/json'));
        }
        
    }
    
    public function addToShoppingCartAction(Request $request)
    {
        $productId = $_POST["productId"];
        $amountProducts = $_POST["amountProducts"];
        
        if ($request->isXMLHttpRequest()) {

            $em = $this->getDoctrine()->getManager();

            if (isset($_SESSION['loginSession'])) {
                $userId = $_SESSION['loginSession'];
                
                $user = $em->getRepository('HomeBundle:User')->findOneByUserId($userId);
                $product = $em->getRepository('HomeBundle:Product')->findOneByProductId($productId);

                $todayDate = date("Y-m-d");
                $todayDate_format = date_create_from_format('Y-m-d', $todayDate);

                $selectedProduct = new \HomeBundle\Entity\Selectedproduct;
                $selectedProduct->setProduct($product);
                $selectedProduct->setUser($user);
                $selectedProduct->setSelectedproductDate($todayDate_format);
                $selectedProduct->setSelectedproductAmount($amountProducts);

                $em->persist($selectedProduct);
                $em->flush();
                
                $selectedproductId = $selectedProduct->getSelectedproductId();
                $selectedproductDate = $selectedProduct->getSelectedproductDate();
                $selectedproductDateString = $selectedproductDate->format('d-M-Y');
                $selectedproductAmount = $selectedProduct->getSelectedproductAmount();
            }
            
            $users2 = array();
            $users2[0] = array(
                'selectedproductId' => $selectedproductId,
                'selectedproductDate' => $selectedproductDateString,
                'selectedproductAmount' => $selectedproductAmount
            );
            return new Response(json_encode($users2), 200, array('Content-Type' => 'application/json'));
        }
    }
    
    public function deleteSelectedProductAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {

            $em = $this->getDoctrine()->getManager();
            
            $users2 = array();
            $users2[0] = array(
                'variable' => "funciona"
            );
            return new Response(json_encode($users2), 200, array('Content-Type' => 'application/json'));
        }
    }
    
    public function getSelectedProductsAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {

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

                    $sendData[$i] = array(
                        'selectedproductId' => $selectedproductId_Value,
                        'selectedproductDate' => $selectedproductDate_Value,
                        'selectedproductAmount' => $selectedproductAmount_Value,
                        'productId' => $productId_Value,
                        'productName' => $productName_Value,
                        'productPrice' => $productPrice_Value,
                        'productDescription' => $productDescription_Value,
                        'productImage' => $productImage_Value,
                        'amountSelectedProducts' => $amountSelectedProducts
                    );

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

                    $sendData[0] = array(
                        'selectedproductId' => $selectedproductId_Value,
                        'selectedproductDate' => $selectedproductDate_Value,
                        'selectedproductAmount' => $selectedproductAmount_Value,
                        'productId' => $productId_Value,
                        'productName' => $productName_Value,
                        'productPrice' => $productPrice_Value,
                        'productDescription' => $productDescription_Value,
                        'productImage' => $productImage_Value,
                        'amountSelectedProducts' => $amountSelectedProducts
                    );
                }

            } else
            {
                $selectedproductId_Value = "_";
                $selectedproductDate_Value = "_";
                $selectedproductAmount_Value = "_";
                $productId_Value = "_";
                $productName_Value = "_";
                $productPrice_Value = "_";
                $productDescription_Value = "_";
                $productImage_Value = "_";

                $sendData[0] = array(
                    'selectedproductId' => $selectedproductId_Value,
                    'selectedproductDate' => $selectedproductDate_Value,
                    'selectedproductAmount' => $selectedproductAmount_Value,
                    'productId' => $productId_Value,
                    'productName' => $productName_Value,
                    'productPrice' => $productPrice_Value,
                    'productDescription' => $productDescription_Value,
                    'productImage' => $productImage_Value,
                    'amountSelectedProducts' => 0
                );
            }
            return new Response(json_encode($sendData), 200, array('Content-Type' => 'application/json'));
        }
    }
    
    
    
}
