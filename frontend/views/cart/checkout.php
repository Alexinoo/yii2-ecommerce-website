<?php

/** @var \common\models\Order $order */
/** @var \common\models\OrderAdress $orderAddress */
/** @var array $cartItems  */
/** @var int $productQuantity  */
/** @var float $totalPrice  */

 use yii\bootstrap4\ActiveForm;
 use yii\helpers\Html;
 use yii\helpers\Url;
 use common\models\Product;
 ?>


      <?php $form = ActiveForm::begin([  
        'id' => 'checkout-form',
        // 'action' => ['/cart/submit-order']
         ])?>

<div class="row">

    <div class="col">

           <div class="card mb-3">
               <div class="card-header">
                 <h5>  Account Information</h5>
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
            <h5> Order Summary </h5>
        </div>
        <div class="card-body">

        <table class="table table-sm">
            <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            </thead>
          <tbody>
        <?php foreach( $cartItems as $item) { ?>
            <tr>             
                <td> 
                    <img
                     src="<?= Product::formatImageUrl($item['image'] )?>"
                     style="width:70px"
                     alt="<?= $item['name'] ?>">
                    </td>
                <td><?= $item['name'] ?></td>
                <td><?= $item['quantity'] ?></td>

                <td><?= Yii::$app->formatter->asCurrency($item['total_price']) ?></td>


            </tr>

       <?php  } ?>
    </tbody>
        </table>    
            <hr>
            <table class="table">
                <tr>
                    <td>Total Items </td>
                    <td class="text-right"> <?= $productQuantity ?></td>
                </tr>
                <tr>
                    <td> Total Price</td>
                    <td class='text-right'><?= Yii::$app->formatter->asCurrency($totalPrice) ?></td>
                </tr>
            </table>
                      
            <p class="text-right mt-3">
                 <button class="btn btn-secondary">Checkout</button>
            </p>
        </div>
    </div>
</div>

</div>
<?php $form = ActiveForm::end()?>

