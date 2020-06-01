<?php
use yii\helpers\Url;
?>
<style type="text/css">
	.shop-status{
		text-align: center;
    padding: 30px 0px;
}   font-size: .28rem;
line-height: .38rem; 
height: .76rem;
	}
</style>
<header class="top-fixed">
	<div class="weui-flex top-box">
		<div onclick="javascript:history.back(-1);">
			<i class="iconfont icon-fanhui"></i>
		</div>
		<div class="weui-flex__item">店铺申请</div>
		<div>
			<i class="iconfont icon-mulu" id="mulu-bt"></i>
		</div>
	</div>
</header>
<div class="mgt68 apply-shop">
	<div class="weui-cells weui-cells_form">
       <p class="shop-status"><?=$message?></p>
    </div>     
</div>

