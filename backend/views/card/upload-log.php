<?php

use yii\grid\GridView;
use yii\helpers\Html;
use backend\widgets\ActiveForm;
use common\helpers\Util;
use yii\helpers\Json;
use common\assets\LayerAsset;
LayerAsset::register($this);
/* @var $this yii\web\View */
/* @var $model common\modules\coupon\models\Coupon */
/* @var $form yii\widgets\ActiveForm */
$this->title = '上传信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' ?>
<?php $this->endBlock() ?>

<div class="box box-primary">
    <div class="box-body">
		<?= GridView::widget([
		    'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label'=>'卡号',
                        'attribute'=>'card_no',
                    ],
                    [
                        'label'=>'密码',
                        'attribute'=>'password',
                    ],
                    [
                        'label'=>'状态',
                        'attribute'=>'info',
                    ],
                    [
                        'label'=>'错误信息',
                        'attribute'=>'message',
                    ],
                ]
		]);?>
    </div>
</div>
