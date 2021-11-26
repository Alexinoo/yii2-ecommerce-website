<?php

namespace frontend\base;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use  common\models\CartItem;
/**
 * Cart controller
 */
class Controller extends \yii\web\Controller
{
    public function beforeAction($action){

        // Get items if the user is not authorized
        if(Yii::$app->user->isGuest){
            $cartItems = Yii::$app->session->get(CartItem::SESSION_KEY , []);
            $sum = 0;

            foreach( $cartItems as $cartItem){

                $sum+=$cartItem['quantity'];
            }
        }

        else{
              $sum =  CartItem::findBySql("
        SELECT SUM(quantity)
        FROM CART_ITEMS 
        WHERE user_id = :userId ",[ 'userId'  => Yii::$app->user->id ])
        ->scalar();
        }     

        // Forward to layout
         $this->view->params['cartItemCount'] = $sum;

        return  parent::beforeAction($action);
    }

  

}