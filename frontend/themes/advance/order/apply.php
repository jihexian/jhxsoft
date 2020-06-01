<?php
use yii\helpers\Url;
?>
<<style>
.mu{
     position:absolute;
     right:.2rem;
     z-index:1;
     font-size: .28rem
}
.yuanyin{
 z-index:100;

}
</style>
    <header class="top-fixed">
        <div class="weui-flex top-box">
            <div onclick="window.open('<?=url::to(['/member/index'])?>','_self');">
                <i class="iconfont icon-fanhui"></i>
            </div>
            <div class="weui-flex__item">退款申请</div>
            <div>
                <i class="iconfont icon-mulu" id="mulu-bt"></i>
            </div>
        </div>
    </header>
    <?=$this->render('../layouts/cart_menu')?>
    <div class="wrap" style="margin-top: .8rem;">
        <div class="main">
       
	
		<div class="weui-panel weui-panel_access gd-list">
			<div class="weui-panel__hd fs32 lh48 cr333">商品详情</div>
			<div class="weui-panel__bd">
			<?php foreach ($data['orderSku'] as $v):?>
				<a href="javascript:void(0);"
					class="weui-media-box weui-media-box_appmsg">
					<div class="weui-media-box__hd">
						<img class="weui-media-box__thumb" src="<?=$v['sku_thumbImg']?>" alt="">
					</div>
					<div class="weui-media-box__bd">
						<h4 class="weui-media-box__title"><?=$v['goods_name']?></h4>
						<p class="weui-media-box__desc"><?=$v['sku_value']?></p>
						<p class="weui-media-box__desc"></p>
						<div class="weui-media-box__desc weui-media-box__bd__btn">
							<p>￥<?=$v['sku_sell_price_real']?>元</p><p>数量：<?=$v['num']?></p>
						</div>
					</div>
				</a> 
				<?php endforeach;?>	
			</div>
		
		</div>
		<div class="weui-cells gd-inf">
			
		<div class="weui-cell">
            <div class="weui-cell__bd">
              <p>申请原因</p>
            </div>
            <div class="weui-cell__ft" >
              <input class="yuanyin weui-input" name="note" placeholder="请选择原因" type="text" id='picker' style="text-align: right;" />
            </div>
            <!-- <label id="mu" for="picker" class="mu iconfont">&#xe62d;</label> -->
         </div>
		
		</div>
			<div class="weui-cells gd-inf">
            <div class="weui-cells__title  fs32 lh48 cr333 ">退款方式</div>
            <div class="weui-cells weui-cells_radio">
            <label class="weui-cell weui-check__label" for="x11">
              <div class="weui-cell__bd">
                <p>原路退返</p>
              </div>
              <div class="weui-cell__ft">
                <input type="radio" value="0" class="weui-check" name="type" id="x11" checked="checked">
                <span class="weui-icon-checked"></span>
              </div>
            </label>
            <label class="weui-cell weui-check__label" for="x12">
    
              <div class="weui-cell__bd">
                <p>退回账号余额</p>
              </div>
              <div class="weui-cell__ft">
                <input type="radio" name="type" value="1" class="weui-check" id="x12" >
                <span class="weui-icon-checked"></span>
              </div>
            </label>
            </div>
      </div>
      
		
      <div style="background-color: transparent; margin-top: 30px;">
        <input name="order_id" type="hidden" id="order_id" value="<?=$data['id']?>"/>
        <?php if(!in_array($data['status'],[2,11])||$data['payment_status']!=1):?>
        	<a href="javascript:history.back(-1);" class="weui-btn weui-btn_default">返回</a>
		    <?php else:?>
		      <a href="javascript:;" id="show-confirm" class="weui-btn weui-btn_warn" style="width: 90%;">申请</a>
		    <?php endif?>
      </div>
    </div>

<?php
$this->registerJs(<<<JS

   $(document).on("click", "#show-confirm", function() {
        $.confirm("您确定要申请吗?", "确认?", function() {
          var note=$('#picker').val();
          var order_id=$('#order_id').val();
          var type=$("input[name='type']:checked").val(); 
          $.ajax({
                async : false,    //表示请求是否异步处理
                type : "post",    //请求类型
                url : "refuse",//请求的 URL地址
                 data : { 'order_id' :order_id,'note':note,'type':type},
                dataType : "json",//返回的数据类型
                success: function (data) {
                   if(data.status==1){
                      $(window).attr('location',"all");
                   }
                },
                error:function (data) {
                }
            });
        }, function() {
          //取消操作
        });
      });
  $("#picker").picker({
  title: "请选择退款原因",
  cols: [
    {
      textAlign: 'center',
      values: [
       '突然不想要了',
       '买错规格了',
       '重新下单',
       '七天无理由退款'
      ]
    }
  ]
}); 

JS
);
?>  
 