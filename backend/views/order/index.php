<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\Tools;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' . Html::a(Yii::t('common', 'Create Order'), ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
              //  'filterModel' => $searchModel,
                    'showFooter' => true,  //设置显示最下面的footer
                 /*     'id' => 'grid', */


                 'columns' => [             
                    'id',
                     [
                      'label'=>'用户姓名',  
                      'attribute' => 'm_id',
                      'value' => function($model) {
                          return $model['member']['username'];
                      }
                    ],
                    'parent_sn',
                    'order_no',
                      'full_name',
                      'tel',
     
             /*      [
                      'attribute' => 'payment_status',
                      'value' => function($model) {
                          return Tools::pay_status($model->payment_status);
                      }
                  ], */
                     
                  [
                      'attribute' => 'delivery_id',
                      'value' => function($model) {
                        return Tools::getDelivery($model->delivery_id);
                      }
                      ],
                    // 'delivery_time',
                    // 'delivery_status',
                    // 'shop_id',
                    // 'is_shop_checkout',
                  [
                      'attribute' => 'status',
                      'value' => function($model) {
                          return Tools::get_status($model->status);
                      }
                  ],

                    // 'prov',
                    // 'city',
                    // 'area',
                    // 'address',
                    // 'sku_price',
                    // 'sku_price_real',
                    // 'delivery_price',
                    // 'delivery_price_real',
                    // 'discount_price',
                    // 'promotion_price',
                    // 'coupons_price',
                     'pay_amount',
                    // 'coupons_id',
                    // 'm_desc',
                    // 'admin_desc',
                     'create_time:datetime',
                    // 'paytime:datetime',
                    // 'sendtime:datetime',
                    // 'completetime:datetime',
                    // 'is_del',
                    // 'update_time:datetime',

                   [
                       'class' => 'yii\grid\ActionColumn',
                       'template'=>'{view} '
                   ],
                ],
            ]); ?>
          
        </div>
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
            请确认
            </div>
            <div class="modal-body">
    </div>

<?php
$this->registerJs(<<<JS

$(".gridview").on("click", function () {
    var ids = $("#grid").yiiGridView("getSelectedRows");
    var ids_str = ids.join(",");
    if(confirm("确认删除么？")){
        $.ajax({
           type: "POST",
           url: "/admin/index.php?r=order"+"%2Fajax-deleteall",
           data: "ids_str="+ids_str,
           dataType: "json",
           success: function(msg){
             window.location.reload();
           }
        });
    }

});
$(".export").on("click", function () {
   	var order_no = $("#ordersearch-order_no").val();
	var payment_no = $("#ordersearch-payment_no").val();
	var status = $("#ordersearch-status").val();
	var full_name = $("#ordersearch-full_name").val();
	var create_time = $("#ordersearch-create_time").val();
   	window.location.href="/admin/order/export?create_time="+create_time+"&status="+status+"&order_no="+order_no+"&full_name="+full_name; 	


});
JS
);
?>
    