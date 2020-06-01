<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ProductTheme */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Product Themes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'carousels',
                'value' =>function($model) {
                $images = $model->carousels;
                $html = '';
                if(is_array($images)){
                    foreach ($images as $img){
                        $imgHtml = Html::img($img['url'],['width'=>'100px']);
                        $html = $html.$imgHtml;
                    }}
                    return $html;
                },
                'format' => ['html'],
                ],
                [
                    'attribute' => 'bgim',
                    'value' =>function($model) {
                        $images = $model->bgim;
                        $html = '';
                        $imgHtml = Html::img($images,['width'=>'100px']);
                        $html = $html.$imgHtml;
                        return $html;
                    },
                    'format' => ['html'],
                    ],
                [
                    'attribute' => 'image',
                    'value' =>function($model) {
                    $images = $model->image;
                    $html = '';
                    $imgHtml = Html::img($images,['width'=>'100px']);
                    $html = $html.$imgHtml;
                    return $html;
                    },
                    'format' => ['html'],
                    ],
                [
                    'label'=>'所属省份',
                    'attribute'=>'province.name'
                ],
                [
                    'label'=>'所属城市',
                    'attribute'=>'city.name'
                ],
                [
                    'label'=>'所属地区',
                    'attribute'=>'district.name'
                ],
                [
                    'label'=>'所属乡镇',
                    'attribute'=>'town.name'
                ],
                [
                    'label'=>'所属村点',
                    'attribute'=>'village.name'
                ],
                            
            'created_at:datetime',
            'updated_at:datetime',
            'sort',
            'status',
        ],
    ]) ?>
    </div>
</div>
