<?php
/**
 *
 * Author vamper 944969253@qq.com
 * Time:2018年11月16日 下午5:26:13
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Json;
use yii\helpers\Url;
?>
<style type="text/css">
	body{background-color: #fff;padding: 0 3%;}
	.goods-info{display: flex;border: 1px solid #ececec;padding: 20px;}
	.goods_pic{width: 30%;margin-right: 2%;}
	.pintuan-price{color: #ed494b;margin: 7px 0;}
	.pintuan-price span{font-weight: 600;font-size: 18px;}
	.orgin-price{color: #999;}
	.join{text-align: center;margin:15px 0;}
	.join-people{text-align: center;}
	.join-people img{width: 35px;height: 35px;border-radius: 50%;margin-left: 5px;}
	.join-now{line-height: 40px;background-color: #e4393c;text-align: center;color: #fff;border-radius: 5px;margin: 15px 0;}
	.rule{display: flex;justify-content: space-between;border-top: 1px solid #ececec;border-bottom: 1px solid #ececec;padding: 15px 0;margin-top: 10px;}
	.rule .left a{color: #666;}
	.goods_name{color: #333;}
	.join span{background-color: #e4393c;color: #fff;border-radius: 3px;padding:1px 3px;margin: 0 2px;}
	#hour{margin-left: 5px;}
</style>
<header class="top-fixed">
	<div class="weui-flex top-box">
		<div onclick="javascript:history.back(-1);">
			<i class="iconfont icon-fanhui"></i>
		</div>
		<div class="weui-flex__item">
			<div class="weui-flex" style="padding: 0 0 0 .65rem;">
				<div class="weui-flex__item">拼团详情</div>
<!-- 				<div class="weui-flex__item">详情</div>
				<div class="weui-flex__item">推荐</div>
				<div class="weui-flex__item">
					<a href="fupinbang.html">扶贫榜</a>
				</div> -->
			</div>
		</div>
		<div class="weui-flex mgr20">
			<i class="iconfont icon-fenxiang weui-flex__item fenxiang"></i>
			<i class="iconfont icon-mulu1 weui-flex__item" id="mulu-bt"></i>
		</div>
	</div>
</header>
<div class="main">
	<div style="width: 100%;height: 10px;"></div>
	<div class="goods-info">
		<div class="goods_pic">
			<a href="#">
				<img src="http://www.yjshop.com/storage/upload/20190628/4g8UhMMv3_C_s9MgtXhGvXtL3aQ_9pfsQWy6MJTd_500_500.jpg" alt="" class="block">
			</a>
		</div>
		<div>
			<a href="#">
				<div class="goods_name">纪梵希口红小牛皮 高定唇膏滋润 口红女</div>
				<div>
					<div class="pintuan-price">拼购价￥<span>36995.66</span></div>
					<div class="orgin-price">单买价<del>￥6653.66</del></div>
				</div>
			</a>
		</div>
	</div>
	<div class="join">
		还差<em style="color: red">1人</em>拼团成功，还剩<span id="hour">00</span>:<span id="minute">00</span>:<span id="second">00</span>
	</div>
	<div class="join-people">
		<image src="http://www.yjshop.com/storage/upload/20190703/adOgSTESqPchf6T8T0FfnZ3x1IWT_SB-EB5ET_AF.png"></image>
		<image src="http://www.yjshop.com/storage/upload/20190703/adOgSTESqPchf6T8T0FfnZ3x1IWT_SB-EB5ET_AF.png"></image>
		<image src="http://www.yjshop.com/storage/upload/20190703/adOgSTESqPchf6T8T0FfnZ3x1IWT_SB-EB5ET_AF.png"></image>
	</div>
	<div class="join-now">我要参团</div>
	<div class="rule">
		<div>拼团规则</div>
		<div>
			<div class="left"><a href="<?=Url::to(['product/pintuan-rule'])?>">好友参团-人满发货-不满退款<icon class="iconfont icon-dayuhao"></icon></a></div>
		</div>
	</div>
</div>
<?php
$this->registerJs(<<<JS
var pintuan_time = 4645;
function timer(intDiff){
	var interval = window.setInterval(function(){
	    var day=0,
	      hour=0,
	      minute=0,
	      second=0;//时间默认值        
	    if(intDiff > 0){
	      day = Math.floor(intDiff / (60 * 60 * 24));
	      hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
	      minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
	      second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
	    }
        else{
        	clearInterval(interval);
        }
        if (hour <= 9) hour = '0' + hour;
        if (minute <= 9) minute = '0' + minute;
        if (second <= 9) second = '0' + second;
        //$('#day').text(day);
        $('#hour').text(hour);
        $('#minute').text(minute);
        $('#second').text(second);
        intDiff--;
    }, 1000);
} 
timer(pintuan_time);
JS
);
?>