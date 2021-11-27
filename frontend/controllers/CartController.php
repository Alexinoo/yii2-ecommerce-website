<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\CartItem;
use common\models\Product;
use common\models\Order;
use common\models\OrderAdress;

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
             'only' => ['add','create-order'] ,
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
            throw new \yii\web\NotFoundHttpException('Product doesnt exist');
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
                throw new \yii\web\NotFoundHttpException('Product doesnt exist');
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

            // CartItem::clearCartItems(currUserId());


             return $this->render('pay-now',[
                 'order' => $order , 
                 'orderAddress' => $order->orderAdress[0],

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
       
}
