<?php

/** @var  \common\models\Order $order*/

$orderAddress = $order->orderAdress[0];
?>

<style>
    .row{
        display : flex;
    }

    .col {
        flex : 1;
    }
</style>
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
</div>

</div>