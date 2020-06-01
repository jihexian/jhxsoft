<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2019年1月8日 下午3:57:28
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Url;
use frontend\widgets\pay\PayWidget;
?>
        <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item mgr9">提交订单</div>
                <div>
                </div>
            </div>
        </header>


    	<div class="pay mgt68">
    		<!-- <div class="title"><i class="weui-icon-success-no-circle"></i>订单已提交成功！</div> -->
    		<div class="desc">
    			<p>请您在<span><?php echo date('Y-m-d H:i:s',$order['create_time']+24*3600);?></span>前完成支付，否则订单将自动取消！</p>
    			<p>订单号：<?php echo $order['order_no'];?></p>
    			<p>支付金额：￥<?php echo $order['pay_amount'];?>元</p>
    		</div>
    		<div class="pay-method">
    			<span class="bold">支付方式</span>
    			<span class="select">必选</span>
    			<span class="cr999">请选择支付方式</span>

    	    </div>

<?= PayWidget::widget(['order_no'=>$order['order_no']]);?>

  
