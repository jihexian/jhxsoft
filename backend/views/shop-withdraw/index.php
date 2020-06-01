<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\ShopWithdraw;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ShopWithdrawSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '提现审核';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
<?= $this->title . ' ' //. Html::a('Create Shop Withdraw', ['create'], ['class' => 'btn btn-primary btn-flat btn-xs']) ?>
<?php $this->endBlock() ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
        'columns' => [
                    'id',
                    'money',
                     [
                        'label'=>'店铺名 ',
                        'value'=>function($model){
                        return $model['shop']['name'];
                        }
                     ],
               
         
                    'account',
                     'name',
                     'bank',
                     [
                         'label'=>'状态 ',
                         'value'=>function($model){
                         return $model->renderStatus($model->status);
                         }
                         ],
                    // 'mark',
                    // 'updated_at',
                    // 'created_at',
         /*         [
                        'attribute' => 'status',
                        'enableSorting'=>false,
                        'label' => '操作',
                        'value' => function ($model) {
                        $html='';
                        if ($model->status==0){
                            $options = [
                                    'title' => Yii::t('yii','审核'),      // yii 的 t() 方法用于翻译多种语言
                                    'aria-label' => Yii::t('yii','审核'),
                                    'data-confirm' => Yii::t('yii','你确定通过这条申请吗？'),  // data-confirm 用于弹出一个确认对话框
                                    'data-method' => 'post',
                                    //'data-pjax' => '1',
                                    'title'=>'打款完成'
                            ];
                            $optionsUncheck = [
                                    'title' => Yii::t('yii','审核'),      // yii 的 t() 方法用于翻译多种语言
                                    'aria-label' => Yii::t('yii','审核'),
                                    'data-confirm' => Yii::t('yii','你确定拒绝这条申请吗？'),  // data-confirm 用于弹出一个确认对话框
                                    'data-method' => 'post',
                                    //'data-pjax' => '1',
                                    'title'=>'拒绝'
                            ];
                            // glyphicon glyphicon-check 这个图标在 bootstrap 中文官网 -> 组件 -> Glyphicons字体图标
                            $url = Url::to(['/shop-withdraw/pass','id'=>$model->id,]);
                            $url2 = Url::to(['/shop-withdraw/refuse','id'=>$model->id,'version'=>$model['shop']['version']]);
                            //$html =Html::a('<span class="glyphicon glyphicon-check">通过</span>',$url,$options)."".Html::a('<span class="glyphicon glyphicon-remove">拒绝</span>',$url2,$optionsUncheck);
                             $html =Html::a('<span class="glyphicon glyphicon-check">通过</span>',$url,$options).Html::a('<span class="glyphicon glyphicon-remove">拒绝</span>',$url2, [
                                'data' => [
                                    'method' => 'post',
                                    'params' => [
                                        'id' => $model->id,
                                        'version'=>$model['shop']['version'],
                                    ]
                                ]
                            ]); 
                        }else{
                            $html = ShopWithdraw::renderStatus($model->status);
                        }
                        
                        return $html;
                        },
                        'format' => 'raw'
                        ], */
                       ['class' => 'yii\grid\ActionColumn',
                               'template' => '{view}'],
                       ],
            ]); ?>
        </div>
    </div>
