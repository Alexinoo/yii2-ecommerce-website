<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'id' => 'ordersTable',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
           [
                'attribute' =>  'id',
                'contentOptions' => [
                    'style' => 'width : 80px'
                ]
           ],
            [
                'attribute' =>  'fullname',
                'content' => function($model){
                    return $model->firstname.' '. $model->lastname;
                }
            ],
            // 'firstname',
            // 'lastname',
            'total_price:currency',
            //'email:email',
            //'transaction_id',
            //'paypal_order_id',
            
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
            'created_at:datetime',
            //'created_by',

            [
                'class' => 'yii\grid\ActionColumn' ,
                'template' => ' {view} {delete}'
            ],
        ],
    ]); ?>


</div>
