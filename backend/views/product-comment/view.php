<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ProductComment */

$this->title = '评价详情';
$this->params['breadcrumbs'][] = ['label' => '评价列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'comment_id',
            'order_no',
            [
            'attribute' => 'member.username',
            'value' =>function($model) {
            	if (empty($model->member)){
            		return '';
            	}else{
            		return $model->member->username;
            	}
            	
            },            
            ],
            //'member.username',
            'orderSku.goods_name',
            'content:ntext',
            [
            	'attribute' => 'status',
           		'value' => function($model) {
            		return $model->renderStatus();
             	}
            ],
            'created_at:datetime',
            [
            'attribute' => 'image',
            'value' =>function($model) {
            	$images = $model->image;
            	$html = '';
            	if(is_array($images)){
            	foreach ($images as $img){
            		$imgHtml = Html::img($img,['width'=>'100px']);
            		$html = $html.$imgHtml;
            	}}
            	return $html;
             },
             'format' => ['html'],
            ],
            //'image:ntext',
            'total_stars',
            'des_stars',
            'delivery_stars',
            'service_stars',
            //'replys',
             [
             'attribute' => 'replys',
             'value' =>function($model) {
             	$replys = $model->replys;
             	$html = '';
             	foreach ($replys as $key=>$reply){
             		if (!empty($reply->member)){
             			$replyHtml = '<li>'.$reply->member->username.':<span>'.$reply->content.'</span></li><br>';
             			
             		}else{
             			$replyHtml = '<li>'.$reply->user->username.':<span>'.$reply->content.'</span></li><br>';
             		}
             		$html = $html.$replyHtml;
             		
             	}
             	return $html;
             },
             'format' => ['html'],
             ],
        ],
    ]) ?>
    </div>
</div>
