<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年12月25日 下午3:42:38
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Url;
?>
  <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    余额提现
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
     <?=$this->render('../layouts/cart_menu')?>
        <div class="main" style="margin-top:.88rem;">
            <div class="withdraw-deposit-bd">
                <div class="withdraw-deposit-bd-content">
                    <p class="fs28">提现金额</p>
                    <input type="text" id="money" name="money" value="￥">
                    <input type="hidden" id="total" value="<?=$member['user_money']?$member['user_money']:0.00?>"/>
                    <p class="fs28 mgt20">余额￥<?=$member['user_money']?$member['user_money']:0.00?>，<a id="check"  href="javascript:;">全部提现</a></p>
                    <div class="weui-btn-area">
                        <a class="weui-btn weui-btn_primary fs28" href="javascript:" id="showTooltips">确认提现</a>
                    </div>
                </div>
            </div>
            
              <div class="withdrawal-box bgfff">
						<div class="ponit-mian">
						    <div class="distribut">
								<div class="distribut-status">
									<p>编号</p>
								</div>
								<div class="distribut-status">
									<p>申请金额</p>
								</div>
								<div class="distribut-status">
									<p>申请时间</p>
								</div>
								<div class="distribut-status">
									<p>状态</p>
								</div>
							</div>
							<?php foreach ($log as $v):?>
							<div class="distribut-items">
								<li>
									<?=$v->id?>
								</li>
								<li>
									<?=$v->pay_amount?>
								</li>
								<li>
									
							    <?=date('Y-m-d',$v->created_at)?>
								</li>
								<li>
									<?=$v->getStatus($v->status);?>
								</li>
							</div>
							<?php endforeach;?>	
							
						</div>
            </div>
        </div>
<?php

$this->registerJs(<<<JS
  var total=$('#total').val();
  $('#check').click(function() {
     $('#money').val(total)
  });

JS
);
?>   