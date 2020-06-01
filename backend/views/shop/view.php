<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Json;
use common\models\RegionLocal;

/* @var $this yii\web\View */
/* @var $model common\models\Shop */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Shops', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    img{max-width: 600px;}
</style>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
    
            [
               'attribute' => 'logo',                
               'format' => ['image'],
            ],
            [
                'attribute' => 'image',
                'format' => ['image'],
            ],
                [
                        'attribute' => 'village_id',
                        'label' => '扶贫地点',
                        'value' => $model->village->name,
                        'format' => 'raw',
                ],
            'created_at:datetime',
            'updated_at:datetime',
            [
                'attribute' => 'status',
                'value' =>function($model) {
                $statusList = [0=>'禁用',1=>'启用'];
                return $statusList[$model->status];
                },
                
            ],
            'address',            
            'description',
            [
                'attribute' => 'license',
                'format' => ['image'],
            ],
            'comment',
            [

                    'attribute' => 'idcard',
                    'value' =>function($model) {
                    if(is_array($model->idcard)){
                        $images = Json::decode($model->idcard);
                    }else{
                        $images='';
                    }
                    $html = '';
                    if($images==''){
                        return $html='空';
                    }
                    foreach ($images as $img){
                        $imgHtml = Html::img($img);
                        $html = $html.$imgHtml;
                    }
                    return $html;
                    },
                    'format' => ['html'],
            ],
            'money',
            
        ],
    ]) ?>
    </div>
</div>
