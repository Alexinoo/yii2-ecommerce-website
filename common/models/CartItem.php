<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%cart_items}}".
 *
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property int $user_id
 *
 * @property Product $product
 * @property User $user
 */
class CartItem extends \yii\db\ActiveRecord
{
    const SESSION_KEY = 'CART_ITEMS';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cart_items}}';
    }

     public static function getTotalQuantityForUser($currUserId){

     // Get items if the user is not authorized
        if( isGuest() ){
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
        WHERE user_id = :userId ",[ 'userId'  => $currUserId ])
        ->scalar();
        }     
        return $sum;
    }

         public static function getTotalPriceForUser($currUserId){

     // Get items if the user is not authorized
        if( isGuest() ){
            $cartItems = Yii::$app->session->get(CartItem::SESSION_KEY , []);
            $sum = 0;

            foreach( $cartItems as $cartItem){

                $sum+=$cartItem['quantity'] * $cartItem['price'] ;
            }
        }
        else{
              $sum =  CartItem::findBySql("
        SELECT SUM(c.quantity * p.price)
        FROM CART_ITEMS c , PRODUCTS p
        WHERE p.id = c.product_id
        AND c.user_id = :userId ",[ 'userId'  => $currUserId ])
        ->scalar();
        }     
        return $sum;
    }

         public static function getTotalPriceForItemForUser($productId , $currUserId){

     // Get items if the user is not authorized
        if( isGuest() ){
            $cartItems = Yii::$app->session->get(CartItem::SESSION_KEY , []);
            $sum = 0;

            foreach( $cartItems as $cartItem){
                if( $cartItem === $productId){
                $sum+=$cartItem['quantity'] * $cartItem['price'] ;
                }
            }
        }
        else{
              $sum =  CartItem::findBySql("
        SELECT SUM(c.quantity * p.price)
        FROM CART_ITEMS c , PRODUCTS p
        WHERE p.id = c.product_id
        AND c.product_id = :id
        AND c.user_id = :userId ",
        [ 'id' => $productId,
         'userId'  => $currUserId ])
        ->scalar();
        }     
        return $sum;
    }


    public static function getItemsForUser($currUserId){

         // if the user is not authorized
        if( Yii::$app->user->isGuest){
            // Get the items from session
               $cartItems =  Yii::$app->session->get(CartItem::SESSION_KEY , []);
        }else{
             // Get the items from the db
      
        $cartItems =  CartItem::findBySql("
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
                        ['userId' => $currUserId])
                        ->asArray()
                        ->all();
        }
        return   $cartItems;

    }

     public static function clearCartItems($currUserId){

        if(isGuest()){
            Yii::$app->session->remove(CartItem::SESSION_KEY);
        }else{
            CartItem::deleteAll(['user_id' => $currUserId]);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'quantity', 'user_id'], 'required'],
            [['product_id', 'quantity', 'user_id'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProductQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\CartItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CartItemQuery(get_called_class());
    }
   
}
