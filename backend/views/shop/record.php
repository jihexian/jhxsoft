<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\helpers\Json;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Shop */
?>

<div class="box box-primary">
    <div class="box-body">
     <section class="scrollable">


<div class="bg-white panel-body border-solid border-gray">
	<div class="col-sm-12">店铺信息</div>
	<div class="row panel-body">
		<div class="col-sm-2">店铺名称：<?=$model['name']?></div>
	<!-- 	<div class="col-sm-2">店铺等级：旗舰店</div> -->
			<div class="col-sm-2">店铺状态：<?php echo $model['status']==0?'关闭':'开启';?>
			</div>
	</div>
	<div class="row b-t border-gray" style="margin-bottom: 12px;"></div>
	<div class="col-sm-12">账户信息</div>
	<div class="row panel-body">
		<div class="col-sm-2">总营业额：<?php echo $total==''?'0.00':$total;?></div>
		<div class="col-sm-2">待结算：<?php echo $waiting==''?'0.00':$waiting;?></div>
		<div class="col-sm-2">店铺余额：<?php echo $model['money']==''?'0.00':$model['money'];?></div>
		<div class="col-sm-2">已提现总额：<?php echo $finish==''?'0.00':$finish;?></div>
		<div class="col-sm-2">提现待审核：<?php echo $ready==''?'0.00':$ready;?></div>
	</div>
	

	<div class="row panel-body">
		
		<div class="col-sm-2">平台服务费：<?php echo $service==''?'0.00':$service;?></div>
	
	</div>
	<div class="row b-t border-gray" style="margin-bottom: 12px;"></div>
</div>

<form action="" method="post">
<div class="row panel-body">
	<div class="col-sm-9"></div>

	<div class="col-sm-2">
<?php
echo DateRangePicker::widget([
     'model'=>$shopAccout,
    'attribute'=>'created_at',
    'convertFormat'=>true,
    'pluginOptions'=>[
        'timePicker'=>true,
        'timePickerIncrement'=>30,
        'locale'=>[
            'format'=>'Y-m-d'
        ],
     
    ]
]);
?>	
	</div>
   <input type="hidden" name="_csrfBackend" value="<?=yii::$app->request->csrfToken?>">
	<div class="col-sm-1">
		<button class="btn btn-sm btn-default" type="submit" >搜索</button>
	</div>
</div>
</form>

<section class="panel panel-default">

<?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
        'columns' => [
                    'id',
                    'order_no',
                 //   'money',
                    'change_money',
                   [
                        'label'=>'类型',
                        'attribute' => 'type',
                        'value' => function ($model) {
                       
                        return $model->getType($model->type);
                        },
                        'headerOptions' => ['width' => '120']
                        ],
                    'comment',
                    'created_at:date',
                     //'updated_at',                    	

            
                ],
            ]); ?>
	 

    </section>
</div>
</div>
