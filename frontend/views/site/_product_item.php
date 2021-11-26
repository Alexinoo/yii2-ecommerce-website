<?php
/**
 *@var \common\models\Product $model 
 */
?>

          <div class="card h-100">
                            <!-- Product image-->
                            <img class="card-img-top" src="<?php echo $model->getImageURL() ?>" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder"><?php echo $model->name ?></h5>
                                    <!-- Product price-->
                                   <?php echo Yii::$app->formatter->asCurrency($model->price )?>
                                   <div class="card-text p-2">
                                         <?php echo $model->getShortDescription() ?>
                                   </div>
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-2 pt-0 border-top-0 bg-transparent text-right">
                                <a class="btn btn-outline-dark mt-auto add-to-cart-btn" href="<?= \yii\helpers\Url::to(['/cart/add'])?>">Add to Cart</a>
                            </div>
                        </div>