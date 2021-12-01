<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var $this yii\web\View */
/** @var $model common\models\Order */  

$orderAddress = $model ->orderAdress[0];

$this->title = 'Order # '.$model->id.' details';
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'total_price:currency',
           [
                'attribute' =>  'status',
                'content' => function($model){
                    if( $model->status == \common\models\Order::STATUS_COMPLETED){
                        return HTML::tag('span','Paid',[ 'class' => 'badge badge-success'
                        ]);
                    }else if( $model->status == \common\models\Order::STATUS_DRAFT){
                          return HTML::tag('span','Unpaid',[ 'class' => 'badge badge-secondary'
                        ]);
                    }else{
                          return HTML::tag('span','Failed',[ 'class' => 'badge badge-danger'
                        ]);
                    }
                }

           ],
            'firstname',
            'lastname',
            'email:email',
            'transaction_id',
            'paypal_order_id',
            'created_at:datetime',
            // 'created_by',
        ],
    ]) ?>
    <h4>Address</h4>
    <?= DetailView::widget([
        'model' => $orderAddress,
        'attributes' => [
            'adresses',
            'city',
            'state ',
            'country',
            'zipcode'
        ],
    ]) ?>
    <h4>Order Items</h4>
       <table class="table table-sm">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
           <?php foreach( $model->orderItem as $item): ?>
             <tr>
            <td><img src="<?= $item->product->getImageUrl() ?>" alt="" style="width:50px;"></td>
            <td><?= $item->product_name ?></td>
            <td><?= $item->quantity ?></td>
            <td><?= Yii::$app->formatter->asCurrency($item->unit_price) ?></td>
            <td><?= Yii::$app->formatter->asCurrency($item->quantity * $item->unit_price ) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>        
    </table>    

</div>
