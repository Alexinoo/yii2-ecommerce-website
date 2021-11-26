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

         // Forward to layout
         $this->view->params['cartItemCount'] = CartItem::getTotalQuantityForUser( currUserId() );

        return  parent::beforeAction($action);
    }

  

}