<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年11月16日 下午5:21:33
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Url;
$source=Yii::$app->request->get('source','');
$type=Yii::$app->request->get('type','');
$id=Yii::$app->request->get('id','');
$address=Yii::$app->request->get('address','');
?>
<style type="text/css">
    .send-type div{border:1px solid #ececec;padding: 8px 8px;margin-right: 15px;}
    .type-box{background-color: #fff;padding: 10px 2%;}
    .send-type{display: flex;margin-top: 10px;}
    .type-title i{margin-right: 5px;}
    .point-box{display: none;}
    .wuliu{background-color: #fff;padding: 15px 2%;color: #333;margin-top:10px;}
    .active{color: #ff4400 !important;border:1px solid #ff4400 !important;}
    .addr-list li{border-bottom: 1px solid #ececec;}
    .confirm{background-color: #ff4400;color: #fff;width: 90%;margin: 0 auto;text-align: center;margin-top: 30px;padding: 10px 0;border-radius: 30px;}
</style>
<header class="top-fixed">
    <div class="weui-flex top-box">
        <div onclick="window.open('<?=$source==''?Url::to(['/member/index']):(($type==''&&$id=='')?Url::to(['/cart/confirm']):Url::to(['/cart/confirm','type'=>$type,'id'=>$id]))?>','_self');"><i class="iconfont icon-fanhui"></i></div>
        <div class="weui-flex__item">
            配送服务
        </div>
        <div>
            <i class="iconfont icon-mulu" id="mulu-bt"></i>
        </div>
    </div>
</header>
        <?=$this->render('../layouts/cart_menu')?>
        <div class="main" style="margin-top: .68rem;">
            <div class="type-box">
                <div class="type-title"><i class="iconfont icon-daishouhuo"></i>配送方式</div>
                <div class="send-type">
                    <div class="active" data-id="1">物流配送</div>
                    <div data-id="2">门店自提</div>
                </div>
            </div>
            <div class="point-box">
               <?php if($data):?>
                <ul class="addr-list">
                  <?php foreach ($data as $key=>$vo):?>
                    <a href="<?=Url::to(['cart/confirm','address'=>$address,'type'=>$type,'id'=>$id,'delivery_id'=>2,'point_id'=>$vo['id']])?>">
                    <li>
                    	<div class="list-item_bd">
                    		<div>
                    			<span class="mgr30"><i class="iconfont icon-yonghu vmd"></i><?=$vo['name']?></span>
                    			<span><i class="iconfont icon-shoujihao"></i><?=$vo['tel']?></span>
                    		</div>
                    		  <p><?php echo ($vo['province']['name'].$vo['city']['name'].$vo['area']['name']);?> <?=$vo['info']?></p>
                    	</div>
                    </li>
                    </a>
                <?php endforeach;?>
                </ul>
               <?php else:?>
                <div class="no-content">
                	<i class="iconfont icon-zanwuneirong1"></i>
                	<p>暂无自提点</p>
                </div>
                <?php endif?>
            </div>
            <div class="wuliu">
                <div>配送时间：工作日/双休日与节假日均可送货</div>
            </div>
            <div class="confirm">确定</div>
        </div>
    </div>
<?php 
$url=Url::to(['cart/confirm','address'=>$address,'type'=>$type,'id'=>$id]);
$this->registerJs(<<<JS
$(".confirm").click(function(){
$(window).attr('location',"$url");
});
$('.send-type div').click(function(){
    var id = $(this).data('id');
    $(this).siblings().removeClass('active');
    $(this).addClass('active');
    if(id == 1){
        $(".wuliu").show();
        $(".point-box").hide();
    }else{
        $(".wuliu").hide();
        $(".point-box").show();
    }
})
JS
);
?>
    