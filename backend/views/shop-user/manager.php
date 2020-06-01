<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\assets\LayerAsset;
LayerAsset::register($this);
$this->title = '设置管理帐号 ' . ' ' . $shop->name;
$this->params['breadcrumbs'][] = ['label' => '店铺列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $shop->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '设置关联用户';
?>
<style>
.padding0{
   padding:0px;
   margin-bottom:10px;
}
</style>
<div class="box box-primary">
    <div class="box-body">
     <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?=$form ->field($model, 'shop_id')->hiddenInput(['value'=>$shop['id']])->label(false)?>
        <div class="form-group">
 
                    <label class="col-xs-12 padding0">选择关联小程序用户：</label>
                    <div class="col-xs-12 padding0">
                        <div>
                           <input type="text" id="member-name" class="form-control" name="member-name" aria-required="true"  readonly="readonly" aria-invalid="true">
                             <?= $form->field($model, 'm_id', ['template' => '{input}{error}','options' => ['tag=>false']])->hiddenInput(['maxlength' => true,'class'=>'form-control width200']) ?>
                        </div>   
                            <a onclick="selectMap()" class="ncap-btn"><i class="fa fa-search"></i>选择小程序用户</a>                 
                        <small style=""></small>
                    </div>     
    </div>
     
    <?= $form->field($model, 'level')->dropDownList(['0'=>'管理员','1'=>'店员'])?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-flat' : 'btn btn-primary btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
   <div class="box box-primary">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
        'columns' => [
                    'id',
                    'username',
                   [
                        'attribute' => 'level',
                        'value' => function ($model) {
                        return $model->level==0?'管理员':'店员';
                        },
                    ],
                    [
                        'label'=>'小程序用户',
                        'attribute' => 'm_id',
                        'value' => function ($model) {
                        return isset($model->member->username)?$model->member->username:'';
                        },
                        ],
                   [
                        'attribute' => 'shop_id',
                        'value' => function ($model) {
                        return isset($model->shop->name)?$model->shop->name:'';
                        },
                    ],
                    
                
                     'created_at:datetime',
                     'login_at',
                     [
                         'header'=>'操作',
                         'headerOptions'=>['width'=>'200'],
                         'class' => 'yii\grid\ActionColumn',
                         'template' => '{delete} {pwd}',
                         'buttons' => [
                             'pwd' => function($url, $model, $key) {
                             return Html::a('改密', null, ['class' => 'btn btn-xs btn-default','href'=>Url::to(['shop-user/reset-password','id'=>$model->id]),'title' => '改密 ']);
                             },
                             'delete' => function($url, $model, $key) {
                             return Html::a('删除', null, ['class' => 'btn btn-xs btn-default','href'=>Url::to(['shop-user/delete-user','id'=>$model->id,'shop_id'=>$model->shop_id]),'title' => '删除 ']);
                             }        
                             ]
                       ],
  
                ],
            ]); ?>
        </div>
    </div>
  <?php $this->beginBlock('member') ?>  
     
    function selectMap(){
        var url = "/admin/member/list";
        layer.open({
            type: 2,
            title: '选择用户',
            shadeClose: true,
            shade: 0.3,
            area: ['70%', '80%'],
            content: url,
        });
    }
     function call_back(user){
        $('#shopuser-m_id').val(user.user_id);
        $('#member-name').val(user.name);
        layer.closeAll('iframe');
       
    }

    <?php $this->endBlock() ?>  
    <?php $this->registerJs($this->blocks['member'], \yii\web\View::POS_END); ?>
  