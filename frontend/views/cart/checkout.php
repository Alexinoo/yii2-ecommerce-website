<?php

/** @var \common\models\Order $order */
/** @var \common\models\OrderAdress $orderAddress */
/** @var array $cartItems  */
/** @var int $productQuantity  */
/** @var float $totalPrice  */

 use yii\bootstrap4\ActiveForm;
 use yii\helpers\Html;
 ?>
<?php $form = ActiveForm::begin([
    'action' => [' ']
])?>

<div class="row">

    <div class="col">
           <div class="card">
               <div class="card-header">
                   Account Information
               </div>
               <div class="card-body">
                         <div class="row">
                <div class="col-md-6">
                    <?= $form->field($order, 'firstname')->textInput(['autofocus' => true]) ?>
                </div>
        
                  <div class="col-md-6">
                    <?= $form->field($order, 'lastname')->textInput(['autofocus' => true]) ?>
                </div>

                </div>

                <?= $form->field($order, 'email') ?>
               </div>
           </div>
     <div class="card">
        <div class="card-header">
             Address information
        </div>
     <div class="card-body">
         <?= $form->field($orderAddress , 'adresses') ?>
         <?= $form->field($orderAddress , 'city') ?>
         <?= $form->field($orderAddress , 'state') ?>
         <?= $form->field($orderAddress , 'country') ?>
         <?= $form->field($orderAddress , 'zipcode') ?>
        </div>
    </div>
</div>

<div class="col">
    <div class="card">
        <div class="card-header">
            <h4> Order Summary </h4>
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <td> <?= $productQuantity ?> Products</td>
                </tr>
                <tr>
                    <td> Total Price</td>
                    <td class='text-right'><?= Yii::$app->formatter->asCurrency($totalPrice) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

</div>
<p class="text-right mt-3">
    <button class="btn btn-success">Continue</button>
</p>

<?php $form = ActiveForm::end()?>