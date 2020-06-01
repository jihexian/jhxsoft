<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品回收站';
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
                    [
                        'class'=>\yii\grid\CheckboxColumn::className(),
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            return ['value'=>$model->product_id,'class'=>'checkbox'];
                        }
                    ],                    
                    'product_id',
                    'shop.name',
                    [
                        'attribute' => 'name',
//                         'value' => function($model) {
//                             return Html::a($model->name, Yii::$app->config->get('SITE_URL') . '/' . $model->product_id . '.html', ['target' => '_blank', 'no-iframe' => '1']);
//                         },
                        'format' => 'raw',
                        'enableSorting' => false
                    ],                   
                     [
                    'label'=>'分类',
                    'attribute'=>'category'
                   ],
                    [
                    'attribute' => 'status',
                    'value' => function($model) {
                        return $model->renderStatus($model->status);
                    }
                	],
                	[
                	    'class' => 'backend\widgets\grid\SwitcherColumn',
                	    'attribute' => 'is_index_show'
                	],
                   
                	
                	[
                	        'class' => 'yii\grid\ActionColumn',
                	        'template' => '{update}',
                	        'buttons' => [
                	                'update' => function($url, $model) {
                	                return Html::a('还原',['reduction'], [
                	                        'data-ajax' => 1,
                	                        'data-method' => 'post',
                	                        'data-params' => ['id' => $model->product_id],
                	                        'data-refresh' => '1'
                	                ]);
                	                },
                	            
                	                ]
                	                ],
                ],
            ]); ?>
        </div>
        <input id="copyInput" type="text" style="display: none"> 
    </div>   
    
	<?php $this->beginBlock('specification') ?>  
    var baseUrl = '<?php echo Yii::$app->config->get('SITE_URL')?>';  
    function copyStr($id){
    	
    	var input = document.getElementById("copyInput");
    	input.style.display="";
      	input.value = baseUrl+'/api/v1/product/detail?product_id='+$id; // 修改文本框的内容
      	input.select(); // 选中文本
      	document.execCommand("copy"); // 执行浏览器复制命令
      	input.style.display="none";
      	alert("复制成功!");	
    }
    <?php $this->endBlock() ?> 
	<?php $this->registerJs($this->blocks['specification'], \yii\web\View::POS_END); ?>