<?php

/** @var \common\models\Order $order */
/** @var \common\models\OrderAdress $orderAddress */
/** @var array $cartItems  */
/** @var int $productQuantity  */
/** @var float $totalPrice  */

 use yii\bootstrap4\ActiveForm;
 use yii\helpers\Html;
 use yii\helpers\Url;
 ?>


 <script
    src="https://www.paypal.com/sdk/js?client-id=Ac0eGsFMRhxFA1hZjbK5suCphGOe8daew-3m0DIu0hx4JY3obwAPFfyzA6aGNMrFN4F1dfEC4uvZXHSC"> // Required. Replace YOUR_CLIENT_ID with your sandbox client ID.
  </script>

<div class="row">

    <div class="col">
        <?php $form = ActiveForm::begin([
            'id' => 'checkout-form',
            ])?>
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

<?php $form = ActiveForm::end()?>

<div class="col">
    <div class="card">
        <div class="card-header">
            <h5> Order Summary </h5>
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <td colspan="2"> <?= $productQuantity ?> Products</td>
                </tr>
                <tr>
                    <td> Total Price</td>
                    <td class='text-right'><?= Yii::$app->formatter->asCurrency($totalPrice) ?></td>
                </tr>
            </table>

             <div id="paypal-button-container"></div>

            <!-- <p class="text-right mt-3">
                 <button class="btn btn-secondary">Checkout</button>
            </p> -->
        </div>
    </div>
</div>

</div>



<script>
    paypal.Buttons({
         createOrder: function(data, actions) {
      // This function sets up the details of the transaction, including the amount and line item details.
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: <?php echo $totalPrice ?>
          }
        }]
      });
    },
    onApprove: function(data, actions) {
        console.log(data , actions);
      // This function captures the funds from the transaction.
      return actions.order.capture().then(function(details) {
        
        const $form = $('#checkout-form');
        let data = [];
         data = $form.serializeArray();
       
        data.push({
            name : 'transactionId',
            value : details.id
        });
        data.push({
            name : 'status',
            value : details.status
        });
        $.ajax({
            method : 'post',
            url : '<?php echo Url::to(['/cart/create-order'])?>',
            data : data ,
            success: function(response){
                console.log(response);
            }
        })
        // This function shows a transaction success message to your buyer.
        alert('Transaction completed by ' + details.payer.name.given_name);
      });
    }
    }).render('#paypal-button-container');
    // This function displays Smart Payment Buttons on your web page.
  </script>