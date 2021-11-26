<?php

namespace common\models;

use Yii;
use common\models\Order;

/**
 * This is the model class for table "{{%order_adresses}}".
 *
 * @property int $id
 * @property int $order_id
 * @property string $adresses
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $zipcode
 *
 * @property Order $order
 */
class OrderAdress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_adresses}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'adresses', 'city', 'state', 'country', 'zipcode'], 'required'],
            [['order_id'], 'integer'],
            [['adresses', 'city', 'state', 'country', 'zipcode'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'adresses' => 'Adresses',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'zipcode' => 'Zipcode',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrderQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\OrderAdressQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\OrderAdressQuery(get_called_class());
    }
}
