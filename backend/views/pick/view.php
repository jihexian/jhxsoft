<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Picks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            ['label'=>'地址','value'=>$model['province']['name'].$model['city']['name'].$model['area']['name'].$model->info],
            'master',
            'tel',
            ['label'=>'状态','value'=>$model->status==1?'启用':'禁用'],
            'created_at:datetime',
            'updated_at:datetime',
            'sort',
        ],
    ]) ?>
    </div>
</div>
