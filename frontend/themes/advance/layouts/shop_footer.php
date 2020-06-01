<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com  pengpeng 617191460@qq.com
 * Time:2018年11月16日 上午10:10:19
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Url;
use common\models\Module;
use common\models\Shop;
$shop_id=yii::$app->request->get('shop_id');
$data=Shop::findOne(['id'=>$shop_id]);
$controller=$this->context->id;
$module=Module::find()->where(['id'=>'coupon'])->one();
$action=$this->context->action->id;
?>
   <footer class="weui-tabbar">      
            <a href="<?=Url::to(['shop/index','shop_id'=>$shop_id])?>" class="weui-tabbar__item <?php if($controller=='shop'&&$action=='index'):?> weui-bar__item_on <?php endif;?>">
                    <i class="iconfont icon-shouye"></i>
                    <p class="weui-tabbar__label">店铺</p>
            </a>  
            <?php if(isset($module['status'])&&$module['status']==1):?>
            <a href="<?=Url::to(['/coupon/coupon/list','id'=>$shop_id])?>" class="weui-tabbar__item <?php if($controller=='shop'&&$action=='coupon'):?> weui-bar__item_on <?php endif;?>">
                    <i class="iconfont icon-youhuiquan"></i>
                    <p class="weui-tabbar__label">优惠券</p>
            </a>
            <?php endif;?>
            <a href="<?=Url::to(['shop/category','shop_id'=>Yii::$app->request->get('shop_id')])?>" class="weui-tabbar__item <?php if($controller=='shop'&&$action=='category'):?> weui-bar__item_on <?php endif;?>">
                    <i class="iconfont icon-leimupinleifenleileibie"></i>
                    <p class="weui-tabbar__label">分类</p>
            </a>
            <a href="tel:<?=$data['tel']?>" class="weui-tabbar__item">
                    <i class="iconfont icon-kefu"></i>
                    <p class="weui-tabbar__label">联系客服</p>
            </a>
    </footer>