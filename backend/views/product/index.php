<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' . Html::a('添加商品', ['step1'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>    
 	<div class="box box-primary">
        <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
    </div>
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
                        return $model->renderStatus();
                    }
                	],
                	[
                	    'class' => 'backend\widgets\grid\SwitcherColumn',
                	    'attribute' => 'hot'
                	],
                	[
                	    'class' => 'backend\widgets\grid\SwitcherColumn',
                	    'attribute' => 'is_new'
                	],
                	[
                	    'class' => 'backend\widgets\grid\SwitcherColumn',
                	    'attribute' => 'is_top'
                	],
                    'sort',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{png}{prev} {copy} {update} {delete}',
                    		'buttons' => [
                    				'copy' => function($url, $model, $key) {
                    					return Html::a('<i class="fa fa-copy"></i>', null, ['class' => 'btn btn-xs btn-default','onclick'=>"copyStr($model->product_id)",'data-toggle' => 'tooltip', 'title' => '复制链接 ']);
                    				},
                    				'png' => function($url, $model, $key) {
                    				return Html::a('<i class="fa fa-barcode"></i>',Url::to(['product/png','id'=>$model->product_id]), ['class' => 'btn btn-xs btn-default','data-toggle' => 'tooltip', 'title' => '查看二维码 ']);
                    				},
                    				'prev' => function($url, $model, $key) {
                    				return Html::a('<i class="fa fa-eye"></i>',Url::to(['../product/detail','id'=>$model->product_id]), ['class' => 'btn btn-xs btn-default','data-toggle' => 'tooltip', 'title' => '查看预览 ']);
                    				}
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