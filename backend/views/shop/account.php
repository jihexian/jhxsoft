<?php

use common\helpers\Tools;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Shops', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.content-15{
padding:15px;
}

.shop-img img {
    float: left;
    width: 80px;
    height: 80px;
    line-height: 100px;
}
.shop-text{
  margin-left: 100px;
}
</style>
<div class="box box-primary">
  <div class="content-15">  
    <div class="row">
		<div class="col-xs-12">
			<div class="shop-img">				
				<img src="<?=$model['logo']?>"  class="count-shop-logo">
			</div>
			<div class="shop-text">
				<p>店铺名称：<?=$model['name']?></p>
				<p>可用余额：<span style="color:#f89406;"><?=$model['money']?></span>元</p>
				<button class="count-button" onclick="window.location.href='<?=Url::to(['shop-withdraw/create'])?>'">提现</button>			
			</div>
        </div>
      </div>
    </div>
    <div class="content-15">  
	<div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?=$total?></h3>
              <p>营业总额</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
          <!--   <a href="#" class="small-box-footer">查看详情 <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?=$ready?></h3>

              <p>待结算</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
          <!--   <a href="#" class="small-box-footer">查看详情 <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        
         <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $withdraw==''?'0.00':$withdraw?></h3>
              <p>已提现</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
       <!--      <a href="#" class="small-box-footer">查看详情<i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
      </div>
      </div>

      <div class="row content-15">


      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#order" aria-controls="order" role="tab" data-toggle="tab">订单记录</a></li>
        <li role="presentation"><a href="#withdraw" aria-controls="withdraw" role="tab" data-toggle="tab">提现记录</a></li>
        <li role="presentation"><a href="#log" aria-controls="log" role="tab" data-toggle="tab">帐号记录</a></li>
      </ul>
    
      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="order">
          <?= GridView::widget([
                'dataProvider' => $order,
                //'filterModel' => $searchModel,
                    'showFooter' => true,  //设置显示最下面的footer
                     'id' => 'grid',

                 'columns' => [             
                    'id',
                     [
                      'label'=>'用户姓名',  
                      'attribute' => 'm_id',
                      'value' => function($model) {
                          return Tools::get_user_name($model->m_id);
                      }
                    ],
                    'parent_sn',
                    'order_no',
                      'full_name',
                      'tel',
     
                  [
                      'attribute' => 'payment_status',
                      'value' => function($model) {
                          return Tools::pay_status($model->payment_status);
                      }
                  ],
                     
           
                  [
                      'attribute' => 'status',
                      'value' => function($model) {
                          return Tools::get_status($model->status);
                      }
                  ],

              
                     'pay_amount',
                  
                     'create_time:datetime',
                     'paytime:datetime',
                    // 'sendtime:datetime',
                    // 'completetime:datetime',
                    // 'is_del',
                    // 'update_time:datetime',

                
                ],
            ]); ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="withdraw">
            <?= GridView::widget([
                'dataProvider' => $withdrawList,
               // 'filterModel' => $searchModel,
        'columns' => [
                    'id',
                    'money',
                    'account',
                    'name',
                    'created_at:datetime',  
                    'mark', 
                  
                 [
                'attribute' => 'status',
                'value' => function($model) {
                   return $model->renderStatus($model->status);
                }
                ],
                ],
            ]); ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="log">
          <?= GridView::widget([
              'dataProvider' => $shopAccountLog,
               // 'filterModel' => $searchModel,
            'columns' => [
                        'id', 
                        'order_no',
                       // 'money',
                        'change_money',
                [
                    'attribute' => 'type',
                    'value' => function($model) {
                    return $model->getType($model->type);
                    }
                    ],
                        'comment', 
                        'created_at:datetime',  
                        
                   
                    ],
                ]); ?>
        </div>
  
      </div>
    
       </div>
      </div>

</div>

