<?php

namespace common\i18n;

/** @package common\i18n */

class Formatter extends \yii\i18n\Formatter {

    public function asOrderStatus($status){

           if( $status == \common\models\Order::STATUS_COMPLETED){
                        return HTML::tag('span','Paid',[ 'class' => 'badge badge-success'
                        ]);
                    }else if( $status == \common\models\Order::STATUS_DRAFT){
                          return HTML::tag('span','Unpaid',[ 'class' => 'badge badge-secondary'
                        ]);
                    }else{
                          return HTML::tag('span','Failed',[ 'class' => 'badge badge-danger'
                        ]);
                    }
    }
}