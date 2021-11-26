<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

            <?php echo \yii\widgets\ListView::widget([
                'dataProvider' => $dataProvider,                
                 'itemView' => '_product_item',
                 'layout' => '{summary}<div class="row">{items} </div>{pager}',               
                 'itemOptions' => [
                     'class' => 'col-lg-4 col-md-6 mb-5 product-item'
                 ],
                 'pager' => [
                     'class' => \yii\bootstrap4\LinkPager::class
                 ]
            ])?>

    </div>
</div>
