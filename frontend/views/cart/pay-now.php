<?php

/** @var \common\models\Order $order */
/** @var \common\models\OrderAddress $orderAddress */

 use yii\helpers\Url;
?>

 <script
    src="https://www.paypal.com/sdk/js?client-id=Ac0eGsFMRhxFA1hZjbK5suCphGOe8daew-3m0DIu0hx4JY3obwAPFfyzA6aGNMrFN4F1dfEC4uvZXHSC"> // Required. Replace YOUR_CLIENT_ID with your sandbox client ID.
  </script>

<h3>Order  #<?php echo $order->id ?>  Summary :</h3>
<hr>
<div class="row">

<div class="col">
    <h5>Account Information</h5>
    <table  class="table">
        <tr>
            <th>Firstname</th>
            <td><?= $order->firstname ?></td>
        </tr>
        <tr>
            <th>lastname</th>
            <td><?= $order->lastname ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= $order->email ?></td>
        </tr>
</table>

      <h5>Adress Information</h5>
    <table class="table">
        <tr>
            <th>Address</th>
            <td><?= $orderAddress->adresses ?></td>
        </tr>
        <tr>
            <th>City</th>
            <td><?= $orderAddress->city ?></td>
        </tr>
        <tr>
            <th>State</th>
            <td><?= $orderAddress->state ?></td>
        </tr>
        <tr>
            <th>Country</th>
            <td><?= $orderAddress->country ?></td>
        </tr>
        <tr>
            <th>ZipCode</th>
            <td><?= $orderAddress->zipcode ?></td>
        </tr>
    </table>
</div>

<div class="col">
    <h5>Products</h5>
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
           <?php foreach( $order->orderItem as $item): ?>
             <tr>
            <td><img src="<?= $item->product->getImageUrl() ?>" alt="" style="width:50px;"></td>
            <td><?= $item->product_name ?></td>
            <td><?= $item->quantity ?></td>
            <td><?= Yii::$app->formatter->asCurrency($item->quantity * $item->unit_price ) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>        
    </table>     
        <hr>
      <table class="table">
          <tr>
              <th> Total Items</th>
              <td>  <?php echo $order->getItemsQuantity(); ?></td>
          </tr>
          <tr>
              <th> Total Price</th>
              <td>  <?php echo  Yii::$app->formatter->asCurrency($order->total_price) ?></td>
          </tr>
      </table>
       
         <div id="paypal-button-container"></div>

</div>

</div>


<script>
    paypal.Buttons({
         createOrder: function(data, actions) {
      // This function sets up the details of the transaction, including the amount and line item details.
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: <?php echo $order->total_price ?>
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
            url : '<?php echo Url::to(['/cart/submit-payment' , 'orderId' =>$order->id])?>',
            data : data ,
            success: function(response){
                 // This function shows a transaction success message to your buyer.
            alert('Transaction completed:' + details.payer.name.given_name);
            // Redirect to the homepage
            window.location.href = ' ';
            }
        })
       
      });
    }
    }).render('#paypal-button-container');
    // This function displays Smart Payment Buttons on your web page.
  </script>