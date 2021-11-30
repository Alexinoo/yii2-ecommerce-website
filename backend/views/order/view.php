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
            'total_price',
            'status',
            'firstname',
            'lastname',
            'email:email',
            'transaction_id',
            'paypal_order_id',
            'created_at',
            'created_by',
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

</div>
