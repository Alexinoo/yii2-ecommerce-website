<?php

 use yii\bootstrap4\ActiveForm;
 use yii\helpers\Html;

 /**
 * @var \yii\web\View $this
 * @var \common\models\UserAdress $userAddress
 */

 ?>


<?php if( isset($success) && $success ): ?>
  <div class="alert alert-success">
        Your address was successfuly updated
  </div>
  <?php endif ?>

<?php $addressForm = ActiveForm::begin([
               'action' => ['/profile/update-address'],
               'options' => [
                   'data-pjax' => 1
               ]
           ]); ?>
            <?= $addressForm->field($userAddress , 'address') ?>
            <?= $addressForm->field($userAddress , 'city') ?>
            <?= $addressForm->field($userAddress , 'state') ?>
            <?= $addressForm->field($userAddress , 'country') ?>
            <?= $addressForm->field($userAddress , 'zipcode') ?>
            <div class="form-group">
                    <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
                </div>

           <?php ActiveForm::end(); ?>