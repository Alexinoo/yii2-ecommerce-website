<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\CartItem;
use common\models\Product;

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
             'only' => ['add'] ,
             'formats' => [
                'application/json' => yii\web\Response::FORMAT_JSON,
            ],
         ]
        ];
    }

    /**
     * {@inheritdoc}
     */   
    
    public function actionIndex(){

        // if the user is not authorized
        if( Yii::$app->user->isGuest){
            // Get the items from session
               $cartItems =  Yii::$app->session->get(CartItem::SESSION_KEY , []);
        }else{

              // Get the items from the db
           $cartItems = CartItem::findBySql("
                        SELECT
                            c.product_id as id , 
                            p.image ,
                            p.name ,
                            p.price ,
                            c.quantity ,
                            p.price *  c.quantity as total_price
                        FROM cart_items c
                        LEFT JOIN products p 
                        ON p.id = c.product_id
                        WHERE c.user_id = :userId" ,
                        ['userId' => Yii::$app->user->id ])
                        ->asArray()
                        ->all();

        }
     
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
            foreach(  $cartItems as  &$cartItem){
                if ( $cartItem['id' ]==  $id ){
                    $cartItem['quantity' ]++;
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
                // break;
            }
        }
         Yii::$app->session->set(CartItem::SESSION_KEY ,  $cartItems);

        }else{
            // delete from db
           CartItem::deleteAll(['product_id' => $id , 'user_id' => currUserId()]);
        }

        return $this->redirect(['index']);
    }
   
}
