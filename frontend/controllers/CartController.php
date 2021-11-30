<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\CartItem;
use common\models\Product;
use common\models\Order;
use common\models\OrderAdress;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Payments\AuthorizationsGetRequest;
use Sample\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use Sample\CaptureIntentExamples\CreateOrder;
           


/**
 * Cart controller
 */
class CartController extends  \frontend\base\controller
{
     /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
         [
             'class' => 'yii\filters\ContentNegotiator',
             'only' => ['add','create-order','submit-payment'] ,
             'formats' => [
                'application/json' => yii\web\Response::FORMAT_JSON,
            ],
        ],
        [
              'class' => 'yii\filters\VerbFilter',
              'actions' => [
                  'delete' => ['POST','DELETE'],
                  'create-order' => ['POST'],
              ]
        ]
        ];
    }

    /**
     * {@inheritdoc}
     */   
    
    public function actionIndex(){       

        // Get the items from the db
           $cartItems = CartItem::getItemsForUser(currUserId());
     
        return $this->render('index',[
            'items' => $cartItems,
        ]);
    }

    public function actionAdd(){

        $id = Yii::$app->request->post('id');

        $product = Product::find()->id($id)->published()->one();

        if(!$product){
            throw new NotFoundHttpException('Product doesnt exist');
        }


    // if user is not authorized , save in session
        if(Yii::$app->user->isGuest){        
          
            //   Check if there is anything in the session , if not return an empty array
            $cartItems =  Yii::$app->session->get(CartItem::SESSION_KEY , []);

            // check if there is any item in the session - increase quantity - Avoids duplicate - &$cartItem reference
            $found = false;
            foreach(  $cartItems as  &$item){
                if ( $item['id' ]==  $id ){
                   $item['quantity' ]++;
                    $found = true;
                    break;
                }
            }

            // if not found , create the product afresh
            if(!$found){
                  // Save in an array
              $cartItem = [
                  'id' => $id ,
                  'name' => $product->name,
                  'image' =>  $product->image,
                  'price' =>  $product->price ,
                  'quantity' => 1 ,
                  'total_price' => $product->price
              ];              
             $cartItems[] =  $cartItem;
            }

            //  save in Local session
            Yii::$app->session->set(CartItem::SESSION_KEY ,   $cartItems);

        }else{

            // find if the item exists in the db
             $cartItem = CartItem::find()->userId(Yii::$app->user->id)->productId( $id)->one();

             //if exists - increase the quantity
             if( $cartItem){
                  $cartItem->quantity++;
             } else{

             //create a new item and save in the db

            $cartItem = new CartItem();

             $cartItem->product_id = $id;
             $cartItem->user_id = Yii::$app->user->id;
             $cartItem->quantity = 1 ;             

        }

             if(  $cartItem->save()){
                  return [
                      'success'  => true ,
                  ];
             }else{
                   return [
                      'success' => false,
                      'errors' => $cartItem->errors ,
                   ];
             }
        
    }


    }

    public function actionDelete($id){

        if( isGuest() ){
        // get items from the session
        $cartItems =  Yii::$app->session->get(CartItem::SESSION_KEY , []);
        
        // iterate and delete the id that matches
        foreach($cartItems as $i=> $cartItem){
            if( $cartItem['id'] == $id ){
                // array_splice - Modifies , takes in array , position and no of items to remove
                array_splice($cartItems , $i , 1);
                break;
            }
        }
         Yii::$app->session->set(CartItem::SESSION_KEY ,  $cartItems);

        }else{
            // delete from db
           CartItem::deleteAll(['product_id' => $id , 'user_id' => currUserId()]);
        }

        return $this->redirect(['index']);
    }

        public function actionChangeQuantity(){
   
            $id = Yii::$app->request->post('id');
            $quantity = Yii::$app->request->post('quantity');

            $product = Product::find()->id($id)->published()->one();

            if(!$product){
                throw new NotFoundHttpException('Product doesnt exist');
            }

            if(isGuest()){
            // get items from the session
                $cartItems =  Yii::$app->session->get(CartItem::SESSION_KEY , []);
                
                // iterate and delete the id that matches
                foreach($cartItems as &$cartItem){
                    if( $cartItem['id'] == $id ){
                      $cartItem['quantity'] =   $quantity;
                      break;
                    }
                }
                Yii::$app->session->set(CartItem::SESSION_KEY ,  $cartItems);
            }else{

                   $cartItem = CartItem::find()->userId(currUserId())->productId( $id)->one();

                   if($cartItem){

                         $cartItem->quantity = $quantity;
                        $cartItem->save();
                   }
            }
            return CartItem::getTotalQuantityForUser(currUserId());
        }

         public function actionCheckout(){

            $cartItems = CartItem::getItemsForUser(currUserId());            
            $productQuantity = CartItem::getTotalQuantityForUser(currUserId());
            $totalPrice = CartItem::getTotalPriceForUser(currUserId());

            if( empty($cartItems) ){
                return $this->redirect([Yii::$app->homeUrl]);
            }
           
            $order = new Order();
            $order->total_price = $totalPrice;
            $order->status = Order::STATUS_DRAFT;
             $order->created_at = time();
             $order->created_by =currUserId();

            $transaction = Yii::$app->db->beginTransaction();

            if($order->load(Yii::$app->request->post())
             && $order->save()
             &&  $order->saveAddress(Yii::$app->request->post() )
              && $order->saveOrderItems()){

              $transaction->commit();

            CartItem::clearCartItems(currUserId());


             return $this->render('pay-now',[
                 'order' => $order , 
                //  'orderAddress' => $order->orderAdress[0],

             ]);

            }

            $orderAddress =  new OrderAdress();

            // if user is not guess , retrieve his details
            if( !isGuest()){  

                $user = Yii::$app->user->identity;
                $userAddress = $user->getAddress();

                // Assign user details for that corresponding order
                $order->firstname = $user->firstname; 
                $order->lastname = $user->lastname; 
                $order->email = $user->email; 
                $order->status = Order::STATUS_DRAFT;
                
                // Assign user details for that corresponding addresses
                $orderAddress->adresses = $userAddress->address;
                $orderAddress->city = $userAddress->city;
                $orderAddress->state = $userAddress->state;
                $orderAddress->country = $userAddress->country;
                $orderAddress->zipcode = $userAddress->zipcode;
            }

            return $this->render('checkout',[
                'order' => $order ,
                'orderAddress' => $orderAddress,
                'cartItems' =>$cartItems ,
                'productQuantity' => $productQuantity ,
                'totalPrice' => $totalPrice                
            ]);

         }     
         
          public function actionSubmitPayment($orderId){

            $where = ['id' => $orderId ,'status' => Order::STATUS_DRAFT];

            if( !isGuest()){
                  $where['created_by'] = currUserId();
            }

            $order = Order::findOne($where);

            if(!$order){
                throw new NotFoundHttpException('Order does not exist');
            }

            // Get paypal orderId
             $paypalOrderId = Yii::$app->request->post('orderId');

            $orderIDExists = Order::find()->andWhere(['paypal_order_id' => $paypalOrderId ])->exists();

            if($orderIDExists){
                throw new BadRequestHttpException();
            }
            // todo  Validate transaction ID - It must not be used and it must be valid transactionID in paypal

            $environment = new SandboxEnvironment(Yii::$app->params['paypalClientId'], Yii::$app->params['paypalSecret'] );
            $client = new PayPalHttpClient($environment);
            
            $response = $client->execute(new OrdersGetRequest($paypalOrderId));
           
            if( $response->statusCode === 200){
                   $order->paypal_order_id = $paypalOrderId;
                    $order->status = $response->result->status == 'COMPLETED' ? ORDER::STATUS_COMPLETED : ORDER::STATUS_FAILED;
                    
                $paidAmount = 0;

                foreach($response->result->purchase_units as $purchase_unit){

                    if ( $purchase_unit ->amount->currency_code = 'USD'){
                         $paidAmount += $purchase_unit->amount->value;
                    }
                }

                if( $paidAmount  ===    $order->total_price && $response->result->status === 'COMPLETED'){
                    $order->status = ORDER::STATUS_COMPLETED;
                }

                $order->transaction_id = $response->result->purchase_units[0]->payments->captures[0]->id;

                if($order->save()){

                    if (!$order->sendEmailToVendor() ) {

                        Yii::error("Email to the vendor is not sent");
                    }
                    
                    if(!$order->sendEmailToCustomer()){
                           
                        Yii::error("Email to the customer is not sent");
                    }

                    return [
                        'success' =>true ,
                    ];

                }else{
                    Yii::error("Order was not saved : 
                                    Data".VarDumper::dumpAsString($order->toArray()).
                                    '. Errors : '.VarDumper::dumpAsString($order->errors));
                }
            }       

            throw new BadRequestHttpException();

          }
       
}
