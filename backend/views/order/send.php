<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年12月18日 上午10:44:55
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Html;
use common\helpers\Tools;
use yii\helpers\HtmlPurifier;
?>
             <tr>
                
                   <th><?= Tools::get_admin_name($model->admin_user) ?></th>
                   <th><?= Html::encode($model->shippingCompany['company_name']) ?></th>
                   <th><?= Html::encode($model->delivery_code) ?></th>
                    <th><?= Html::encode(Yii::$app->formatter->asDatetime($model->addtime)) ?></th>        
                   <th><?= Html::encode($model->note) ?></th>
               </tr>
       