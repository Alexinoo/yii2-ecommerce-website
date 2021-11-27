<?php

namespace common\models;

use Yii;

use yii\db\Exception;
use common\models\OrderItem;
use common\models\CartItem;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property int $id
 * @property float $total_price
 * @property int $status
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string|null $transaction_id
 * @property int|null $created_at
 * @property int|null $created_by
 *
 * @property User $createdBy
 * @property OrderAdress[] $OrderAdresses
 * @property OrderItem[] $orderItems
 */
class Order extends \yii\db\ActiveRecord
{
    CONST STATUS_DRAFT = 0;
    CONST STATUS_COMPLETED = 1;
    CONST STATUS_FAILED = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total_price', 'status', 'firstname', 'lastname', 'email'], 'required'],
            [['total_price'], 'number'],
            [['status', 'created_at', 'created_by'], 'integer'],
            [['firstname', 'lastname'], 'string', 'max' => 45],
            [['email', 'transaction_id'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'total_price' => 'Total Price',
            'status' => 'Status',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'email' => 'Email',
            'transaction_id' => 'Transaction ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[OrderAdress]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrderAdressQuery
     */
    public function getOrderAdress()
    {
        return $this->hasMany(OrderAdress::className(), ['order_id' => 'id']);
    }

    /**
     * Gets query for [[OrderItem]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrderItemQuery
     */
    public function getOrderItem()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\OrderQuery(get_called_class());
    }

    public  function saveAddress($postData)
        {
            $orderAddress = new OrderAdress();

            $orderAddress->order_id = $this->id;

           if ($orderAddress->load($postData) && $orderAddress->save()){
               return true;
           }
            throw new Exception("Could not save order address :".implode('<br>',$orderAddress->getFirstErrors()));

        }

      public  function saveOrderItems()
        {

         $cartItems = CartItem::getItemsForUser(currUserId());

        foreach( $cartItems as $cartItem){
            $orderItem = new OrderItem();
            $orderItem->product_name = $cartItem['name'];
            $orderItem->product_id = $cartItem['id'];
            $orderItem->unit_price = $cartItem['price'];
            $orderItem->order_id = $this->id;
            $orderItem->quantity = $cartItem['quantity'];

        if(!$orderItem->save()){

                throw new Exception("Order Item was not saved :".implode('<br>',$orderItem->getFirstErrors()));
            }

        }
        return true;
    }

    public function getItemsQuantity(){

         $sum =  CartItem::findBySql("
                                                        SELECT SUM(quantity)
                                                            FROM order_items 
                                                        WHERE order_id = :orderId ",
                                                        ['orderId'  => $this->id ])
                                                         ->scalar();
        return $sum;
     }
  
}
