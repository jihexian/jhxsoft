<?php
use yii\helpers\Url;

?>
<style type="text/css">
	.sign-out{margin-top: 20px;width: 90%;}
</style>
<div class="main mgt68">
	<header class="top-fixed">
		<div class="weui-flex top-box">
			<div onclick="javascript:history.back(-1);">
				<i class="iconfont icon-fanhui"></i>
			</div>
			<div class="weui-flex__item mgr9">设置</div>
			<div></div>
		</div>
	</header>
	<div class="weui-cells">
		<a class="weui-cell weui-cell_access" style="margin-top: 6px;" href="<?=Url::to(['member/info'])?>">
			<div class="weui-cell__bd weui-cell_primary">
				<p>个人信息</p>
			</div>
			<span class="weui-cell__ft"></span>
		</a>
		<?php if(!empty(Yii::$app->user->identity->mobile)):?>
		<a class="weui-cell weui-cell_access" href="<?=Url::to(['member/unbind-mobile'])?>">
			<div class="weui-cell__bd weui-cell_primary">
				<p>手机解绑</p>
			</div>
			<span class="weui-cell__ft"></span>
		</a>
		<?php else:?>
		<a class="weui-cell weui-cell_access" href="<?=Url::to(['member/bind-mobile'])?>">
			<div class="weui-cell__bd weui-cell_primary">
				<p>绑定手机号</p>
			</div>
			<span class="weui-cell__ft"></span>
		</a>
		<?php endif;?>
		<a class="weui-cell weui-cell_access" href="<?=Url::to(['member/reset-password'])?>">
			<div class="weui-cell__hd"></div>
			<div class="weui-cell__bd weui-cell_primary">
				<p>重置密码</p>
			</div>
			<span class="weui-cell__ft"></span>
		</a>
				<a class="weui-cell weui-cell_access" href="<?=Url::to(['member/reset-pay-password'])?>">
			<div class="weui-cell__hd"></div>
			<div class="weui-cell__bd weui-cell_primary">
				<p>重置支付密码</p>
			</div>
			<span class="weui-cell__ft"></span>
		</a>
	</div>
	<a data-href="<?=Url::to(['site/logout'])?>" class="weui-btn weui-btn_primary sign-out">退出账户</a>
</div>
<?php 
$this->registerJs(<<<JS
	
    //退出登录
    $(".main").on('click','.sign-out',function(e){
        var href = $(this).data('href');
        logout(href);
    });
    function logout(href){
      $.confirm("您确定要退出登录吗？", function() {
         window.location.href = href;
        }, function() {
         
        }
      );
    }
JS
);
?>   
