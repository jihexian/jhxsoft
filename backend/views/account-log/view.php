<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\AccountLog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Account Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'member',
            'money',
            'change_money',
            'score',
            'change_score',           
            
            [
               'attribute' => 'type',
               'value' => function($model) {
                	return $model->renderType();
              	}
            ],
            'desc',
         
            'user',
            'created_at:datetime',
            //'updated_at',
        ],
    ]) ?>
    </div>
</div>
