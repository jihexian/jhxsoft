<?php
use yii\helpers\Url;
use common\helpers\Tools;
?>
<style type="text/css">
  .village_box .village_list .avatar img{width: 100%;height: 100% !important;}
</style>
        <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    扶贫排行榜
                </div>
                <div>
                    <i class="iconfont icon-mulu1" id="mulu-bt"></i>
                </div>
            </div>
        </header>

        <aside class="goods-nav hide">
            <ul>
                <li><a href="<?=Url::to(['site/index'])?>"><i class="iconfont icon-shouye"></i>首页</a></li>
                <!-- <li><a href=""><i class="iconfont icon-kefu1"></i>搜索</a></li> -->
                <li><a href="<?=Url::to(['member/index'])?>"><i class="iconfont icon-renwu"></i>个人中心</a></li>
                <li><a href="<?=Url::to(['order/all'])?>"><i class="iconfont icon-dingdan"></i>全部订单</a></li>
            </ul>

        </aside> 
<div class="main">
	<div class="order_tab">
		<a id="shop" class="active">店铺扶贫总排行</a>
		<a id="member">个人扶贫总排行</a>
	</div>
	<div class="village_rank">
		<div><i class="fa fa-bar-chart fa-lg"></i><span>帮扶排行榜</span></div>
	</div>
	<div class="village_box">
		<?php foreach ($model as $k=>$v):?>

		<div class="village_list" onclick="window.location.href=<?="'".Url::to(['/shop/index','shop_id'=>$v['id']])."'" ?>">
    		<div class="avatar">
    			<img alt="" src="<?=$v['logo']?$v['logo']:Yii::$app->params['defaultImg']['default']?>">
    		</div>
    		<p class="village_ranking">NO.<?=$k+1?></p>
    		<p class="village_name"><?=$v['name']?></p>
    		<p class="village_money"><?=$v['total']?$v['total']:'0'?> 元</p>
		</div>
		<?php endforeach;?>
	</div>
	
</div>
<?php $this->beginBlock('block1') ?>

  var defaultImg = "<?php echo Yii::$app->params['defaultImg']['default']; ?>";
	var save_flag = true;
	var loading = false;  //状态标记
	var page=1;
	var index="shop";
	$('.order_tab>a').click(function(){
		if(save_flag){
			save_flag = false;
			loading=false;
			page=1;
    		$(this).addClass('active').siblings().removeClass('active');
    		$('.village_list').remove();
    		//判断选择的是平台还是个人
    		index=$(this).attr('id');
    		console.log(index);
    		if(index=="member"){
    			loadmember();
    		}
    		if(index=="shop"){
    			loadshop();
    		}
    	}
	});
	 $(document.body).infinite().on("infinite", function() {
      if(loading) return;
      		page++;
      		loading = true;
          	setTimeout(function() {
                if(index=="member"){
        			loadmember();
        		}
        		if(index=="shop"){
        			loadshop();
        		}
          	}, 1500);   //模拟延迟
       
    });
	function loadshop() {
	var html = "";
		      $.ajax({
               type: "POST",
               url: "<?=Url::to(['rank/shop-ajax'])?>?page="+page,
               data: {'page': page },
               dataType: "json",
               beforeSend: function(){
               			loading=true;
    		          	html += '<div class="weui-loadmore">';
    					html += '<i class="weui-loading"></i>';
    					html += '<span class="weui-loadmore__tips">正在加载</span>';
    					html += '</div>';
    					$(".village_box").append(html);
    				  },
    		  complete:function(XMLHttpRequest,textStatus){
                      // alert('远程调用成功，状态文本值：'+textStatus);
                     $(".weui-loadmore").remove();
          		 },
               error: function () {
 					loading=false;
               },
               success: function (data) {
               		if(data.pages==0){
               			loading=true;
               			$.toast("暂无记录", "forbidden");
               		}else if(data.pages <= page){
               			loading=true;
               		}else{
               			loading=false;
               		}
               		var n=(page-1)*20;
               		
               		for (var i = 0; i < data.items.length; i++) {
               			n++;
               			var total=(data.items[i].total==""||data.items[i].total==null)?0:data.items[i].total;
    					html += '<div class="village_list" onclick="window.location.href=&quot/shop/index?id='+data.items[i].id+'&quot">';
                    	html += '<div class="avatar">';
                    	html += '<img alt="" src="'+data.items[i].logo+'">';
                    	html += '</div>';
                    	html += '<p class="village_ranking">NO.'+n+'</p>';
                    	html += '<p class="village_name">'+data.items[i].name+'</p>';
                    	html += '<p class="village_money">'+total+' 元</p>';
                		html += '</div>';
                   		}
                   $(".village_box").append(html);
                   save_flag = true;
               }
           });
	};
	function loadmember() {
	var html = "";
		      $.ajax({
               type: "POST",
               url: "<?=Url::to(['rank/member-ajax'])?>?page="+page,
               data: {'page': page },
               dataType: "json",
               beforeSend: function(){
               			loading=true;
    		          	html += '<div class="weui-loadmore">';
    					html += '<i class="weui-loading"></i>';
    					html += '<span class="weui-loadmore__tips">正在加载</span>';
    					html += '</div>';
    					$(".village_box").append(html);
    				  },
    		  complete:function(XMLHttpRequest,textStatus){
                      // alert('远程调用成功，状态文本值：'+textStatus);
                     $(".weui-loadmore").remove();
          		 },
               error: function () {
 					loading=false;
 					save_flag = true;
               },
               success: function (data) {
               		if(data.pages==0){
               			loading=true;
               			$.toast("暂无记录", "forbidden");
               		}else if(data.pages <= page){
               			loading=true;
               		}else{
               			loading=false;
               		}
               		var n=(page-1)*20
               		for (var i = 0; i < data.items.length; i++) {

                    var img = data.items[i].avatar ? data.items[i].avatar : defaultImg
               			n++;
               			var total=(data.items[i].total==""||data.items[i].total==null)?0:data.items[i].total;
    					html += '<div class="village_list">';
                    	html += '<div class="avatar">';
                    	html += '<img alt="" src="'+img+'"/>';
                    	html += '</div>';
                    	html += '<p class="village_ranking">NO.'+n+'</p>';
                    	html += '<p class="village_name">'+data.items[i].username+'</p>';
                    	html += '<p class="village_money">'+total+' 元</p>';
                		html += '</div>';
                   		}
                   $(".village_box").append(html);
                   save_flag = true;
               }
           });
	};
     $("#mulu-bt").click(function() {
        $("#mulu-more").toggle(500)
     })
	 $("#mulu-bt").click(function() {
        var mulu = $(".goods-nav");
        if (mulu.is(":hidden")) {
            mulu.show();
        } else {
            mulu.hide();
        }
    });
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?> 