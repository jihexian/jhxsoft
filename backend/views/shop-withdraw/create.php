<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ShopWithdraw */

$this->title = '提现';
$this->params['breadcrumbs'][] = ['label' => 'Shop Withdraws', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-withdraw-create">

    <?= $this->render('_form', [
        'model' => $model,
          'shop'=>$shop,
    ]) ?>

</div>
