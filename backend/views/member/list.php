<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '会员列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('content-header') ?>
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
                        'class' => 'yii\grid\RadioButtonColumn',
                     ],
                    'id',
                    'username',
                    'mobile',
                    //'email',
                    'sex',
                    //'age',
                    'score',
                    'user_money',
                    [
					'attribute' => 'type',
                     'value'=>function($model){
                            return $model->renderType();
                            },
					'label' => '用户类型',
					],
                 
                    //'register_time:datetime',
                    //'last_login:datetime',
                 
                ],
            ]); ?>
        </div>
        <div class="form-group save-box">
                    <button type="submit" class="btn btn-primary btn-flat" onclick="select_member();">确定</button>              
                </div>
    </div>
    
    <?php $this->beginBlock('specification') ?>  
     var user = null;
     function select_member(){
      var input = $("input[type='radio']:checked");
        if (input.length == 0) {
           alert('请选择用户'); 
           return false;
        }
        window.parent.call_back(user);
    }
       //商品product对象
    function User(user_id, name) {
        this.user_id = user_id;
        this.name = name;

    }
       $("input[type='radio']").click(function(){
    	var user_id = $(this).val();
    	if(user==null||user.user_id!=user_id){
    		var name = $(this).parent('td').siblings('td').eq(1).text();
    		user = new User(user_id, name);
    	}     
    })   
   

    <?php $this->endBlock() ?> 
	<?php $this->registerJs($this->blocks['specification'], \yii\web\View::POS_END); ?>
