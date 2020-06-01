<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年11月16日 下午5:21:49
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Url;
use common\logic\OrderLogic;

?>
<style type="text/css">
    .top-img-box img{width: 2rem;border-radius: 50%;height: 2rem !important;}
</style>
<div class="main">
		 	<span class="member-info" onclick="window.open('<?=Url::to(['/member/account'])?>','_self');">设置</span>
            <div class="bg6ec7c1 crfff">
                <div class="weui-flex ct-top">
                    <div class="top-img-box">
                    <?php if(empty($item['avatarUrl'])):?> 
                        <img src="https://image.jihexian.com/avator.jpg" >
                      <?php else:?> 
                       <img src="<?=$item['avatarUrl']?>"  >
                      
                      <?php endif;?>
                    </div>
                    <div class="weui-flex__item">
                        <p class="fs34"><?=$item['username']?></p>
                    </div>
                </div>
            </div>
            <div class="weui-cells">
                <a class="weui-cell weui-cell_access" href="<?=Url::to(['order/all'])?>">
                   
                    <div class="weui-cell__bd">
                        <p class="fs34 lh48">我的订单</p>
                    </div>
                    <div class="weui-cell__ft fs30">
                        全部订单
                    </div>
                </a>
                <div class="weui-grids order-grids">
                <?php         $model=new OrderLogic();?>
                    <a href="<?=Url::to(['order/all','status'=>1])?>" class="weui-grid js_grid">
                        <div class="weui-grid__icon">
                            <i class="iconfont icon-weibiaoti2fuzhi04 hint-num">
                            <em><?php $unpay=sizeof($model->get_data($item['id'],'1')['items']); echo $unpay > 0 ? $unpay : '';  ?></em></i>
                        </div>
                        <p class="weui-grid__label fs28 cr888">
                            待付款
                        </p>
                    </a>
                    <a href="<?=Url::to(['order/all','status'=>3])?>" class="weui-grid js_grid">
                        <div class="weui-grid__icon">
                            <i class="iconfont icon-daishouhuo hint-num">
                            <em><?php $readyReceive = sizeof($model->get_data($item['id'],'3')['items']); echo $readyReceive > 0 ? $readyReceive : ''; ?></em></i>
                        </div>
                        <p class="weui-grid__label fs28 cr888">
                            待收货
                        </p>
                    </a>
                    <a href="<?=Url::to(['order/all','status'=>4])?>" class="weui-grid js_grid">
                        <div class="weui-grid__icon">
                            <i class="iconfont icon-daipingjia hint-num">
                            <em><?php $evalue=sizeof($model->get_data($item['id'],'4')['items']); echo $evalue > 0 ? $evalue : ''; ?></em></i>
                        </div>
                        <p class="weui-grid__label fs28 cr888">
                            待评价
                        </p>
                    </a>
                    <a href="<?=Url::to(['order/all','status'=>10])?>" class="weui-grid js_grid">
                        <div class="weui-grid__icon">
                            <i class="iconfont icon-tuihuanhuo1 hint-num">
                            <!-- <em><?=sizeof($model->get_data($item['id'],'10')['items'])?></em> --></i>
                        </div>
                        <p class="weui-grid__label fs28 cr888">
                            退换/售后
                        </p>
                    </a>
                </div>
            </div>
            <div class="center-grids mgt20 bgfff">
                <div class="weui-grids">
                     <a href="<?php echo  Url::to(['member/pintuan'])?>" class="weui-grid">
                        <div class="weui-grid__icon">
                            <img src="<?=Url::to('@web/storage/images/icon24.png')?>" alt="">
                        </div>
                        <p class="weui-grid__label cr888">我的拼团</p>
                    </a>
                    <a href="<?php echo  Url::to(['member/collection-product'])?>" class="weui-grid">
                        <div class="weui-grid__icon">
                            <img src="<?=Url::to('@web/storage/images/icon24.png')?>" alt="">
                        </div>
                        <p class="weui-grid__label cr888">收藏宝贝</p>
                    </a>
                    <a href="<?php echo  Url::to(['member/collection-shop'])?>" class="weui-grid">
                        <div class="weui-grid__icon">
                            <img src="<?=Url::to('@web/storage/images/icon25.png')?>" alt="">
                        </div>
                        <p class="weui-grid__label cr888">收藏店铺</p>
                    </a>
                    <a href="<?php echo  Url::to(['member/wallet'])?>" class="weui-grid">
                        <div class="weui-grid__icon">
                            <img src="<?=Url::to('@web/storage/images/icon26.png')?>" alt="">
                        </div>
                        <p class="weui-grid__label cr888">钱包</p>
                    </a>
                    <a href="<?php echo  Url::to(['member/score-log'])?>" class="weui-grid">
                        <div class="weui-grid__icon">
                            <img src="<?=Url::to('@web/storage/images/jifen.png')?>" alt="">
                        </div>
                        <p class="weui-grid__label cr888">积分账户</p>
                    </a>
              <!--       <a href="javascript:;" class="weui-grid">
                        <div class="weui-grid__icon">
                            <img src="./storage/images/coupons.png" alt="">
                        </div>
                        <p class="weui-grid__label cr888">优惠券</p>
                    </a> -->
                    <a href="<?=Url::to('address/index')?>" class="weui-grid">
                        <div class="weui-grid__icon">
                            <img src="<?=Url::to('@web/storage/images/icon27.png')?>" alt="">
                        </div>
                        <p class="weui-grid__label cr888">地址管理</p>
                    </a>
              <!--       <a href="<?php echo  Url::to(['member/anquan'])?>" class="weui-grid">
                        <div class="weui-grid__icon">
                            <img src="./storage/images/icon29.png" alt="">
                        </div>
                        <p class="weui-grid__label cr888">账户安全</p>
                    </a> -->
                    <a href="javascript:;" class="weui-grid" id="showIOSDialog2">
                        <div class="weui-grid__icon">
                            <img src="<?=Url::to('@web/storage/images/icon34.png')?>" alt="">
                        </div>
                        <p class="weui-grid__label cr888">客服</p>
                    </a>
                    <a href="<?=Url::to(['/distribut/index'])?>" class="weui-grid" id="showIOSDialog2">
                        <div class="weui-grid__icon">
                            <img src="<?=Url::to('@web/storage/images/icon30.png')?>" alt="">
                        </div>
                        <p class="weui-grid__label cr888">分销商</p>
                    </a>
                    <?php if(empty($shop)):?>
                        <a href="<?=Url::to(['/member/apply'])?>" class="weui-grid" id="showIOSDialog2">
                            <div class="weui-grid__icon">
                                <img src="<?=Url::to('@web/storage/images/icon40.png')?>" alt="">
                            </div>
                            <p class="weui-grid__label cr888">申请店铺</p>
                        </a>
                    <?php else:?>
                        <a href="<?=Url::to(['../seller'])?>" class="weui-grid" id="showIOSDialog2">
                            <div class="weui-grid__icon">
                                <img src="<?=Url::to('@web/storage/images/icon25.png')?>" alt="">
                            </div>
                        <p class="weui-grid__label cr888">店铺信息</p>
                    </a>
                    <?php endif;?>
                </div>
            </div>
        </div>
<?php $this->beginBlock('block1') ?> 
    $(function(){
        var url = "<?=Url::to(['member/bind-mobile','type'=>'apply'])?>";
        $("#bind").click(function(){
            $.confirm("为了您账户安全，请先绑定手机号码", function() {
              window.location.href = url
            }, function() {
              //点击取消后的回调函数
            });
        })
    
    });
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?>   