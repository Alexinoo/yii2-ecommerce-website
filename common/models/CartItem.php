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
