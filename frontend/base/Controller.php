<?php

namespace frontend\base;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * Cart controller
 */
class Controller extends \yii\web\Controller
{
    public function beforeAction($action){

        $itemCount =  \common\models\CartItem::findBySql("
        SELECT SUM(quantity)
        FROM CART_ITEMS 
        WHERE user_id = :userId ",[ 'userId'  => Yii::$app->user->id ])
        ->scalar();

        // Forward to layout
         $this->view->params['cartItemCount'] = $itemCount;

        return  parent::beforeAction($action);
    }

  

}