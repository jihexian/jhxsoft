<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分销抽成统计';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Analysis-distribut">
 	<div class="box box-primary">
        <div class="box-body"><?php echo $this->render('//member/_search.php', ['model' => $searchModel]); ?></div>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'username',
                    'mobile',
                     [
                        'attribute' => 'distribut_money',
                        'label'=>'累计分销金额',
                     ],
                     [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{eye}',
                        'buttons' => [
                                     'eye' => function($url, $model, $key) {
                                     return Html::a('<i class="fa fa-eye"></i>', null, ['class' => 'btn btn-xs btn-default','data-toggle' => 'tooltip','href'=>Url::to(['distribut-log/index','pid'=>$model->id]),'title' => '查看详情 ']);
                                     }
                           ]
                         
                     ],
                  ],
            ]); ?>
        </div>
    </div>

</div>
