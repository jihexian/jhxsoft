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
	body{background-color: #fff;}
	.pro-content{padding:10px 2%;margin-top:.8rem;}
</style>
<header class="top-fixed">
	<div class="weui-flex top-box">
		<div onclick="javascript:history.back(-1);">
			<i class="iconfont icon-fanhui"></i>
		</div>
		<div class="weui-flex__item">
			<div class="weui-flex">
				<div class="weui-flex__item">拼团规则</div>
<!-- 				<div class="weui-flex__item">详情</div>
				<div class="weui-flex__item">推荐</div>
				<div class="weui-flex__item">
					<a href="fupinbang.html">扶贫榜</a>
				</div> -->
			</div>
		</div>
		<div class="weui-flex mgr20">
			<i class="iconfont icon-mulu1 weui-flex__item" id="mulu-bt"></i>
		</div>
	</div>
</header>
<div class="pro-content">
	一、拼团有效期

	请在拼团有效期内完成拼团，如果在有效期内，未达到相应参团人数，则拼团失败。如果距离活动结束时间小于拼团有效期时，则以拼团有效期为准

	二、拼团成功

	拼团有效期内，支付的用户达到参团人数，则拼团成功

	三、拼团失败

	拼团有效期后，未达成相应参团人数的团，则该团失败，拼团失败的课程订单，系统会在1-7个 工作日内处理退款，系统处理后1-10个工作日内原路退回原支付账户中

	四、等待拼团如何退款？

	拼团中状态暂不支持退款申请，若拼团失败后会自动退回
</div>