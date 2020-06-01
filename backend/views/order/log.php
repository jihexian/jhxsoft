<?php
use yii\helpers\Html;
use common\helpers\Tools;
use yii\helpers\HtmlPurifier;
?>
             <tr>
                   <th><?= $model->action_user ?></th>
                   <th><?= Html::encode(Yii::$app->formatter->asDatetime($model->create_time)) ?></th>
                   <!-- <th><? // echo  Tools::get_status($model->order_status) ?></th>
                   <th><? // echo Tools::pay_status($model->pay_status) ?></th>
                   <th><? //echo Tools::shipping_status($model->shipping_status) ?></th>
                    -->
                   <th><?= Html::encode($model->status_desc) ?></th>
                   <th><?= Html::encode($model->action_note) ?></th>
               </tr>
       