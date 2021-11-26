<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
           [
               'attribute' => 'id',
               'contentOptions' => [ 'style' => 'width : 75px']
           ],
             [
                 'label' => 'Image',
                'attribute' => 'image',
                'content' => function($model){
                    return HTML::img($model->getImageURL(),[ 'style' => 'width : 100px']);
                }
            ],
            'name',
            // 'description:ntext',
            // 'image',
           
            'price:currency',
            [
                'attribute' => 'status',
                'content' => function($model){
                    return HTML::tag('span', $model->status ? 'Active' :'Draft',[
                        'class' => $model->status ? 'badge badge-success' : 'badge badge-danger'
                    ]);
                    // var_dump($model->getImageURL());die();
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => ['datetime'],
                'contentOptions' => ['style' => 'white-space : nowrap']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['datetime'],
                'contentOptions' => ['style' => 'white-space : nowrap']
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
