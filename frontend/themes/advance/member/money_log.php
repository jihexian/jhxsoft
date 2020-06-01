<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年12月25日 下午5:44:37
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use common\helpers\Tools;
use yii\helpers\Url;
?>
   <style type="text/css">
     .crGreen{color:#04BE02;}
     .no-product{padding: 0;}
   </style>
   <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    我的钱包
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
      <?=$this->render('../layouts/cart_menu')?>
        <div class="main">
            <div class="wallet-box">
                    <div class="wallet-top">
                        <div class="tc">
                            <i class="iconfont icon-yue"></i>
                            <p class="fs28">可用余额</p>
                            <p class="yue">￥<?php echo empty($money)?0:$money?></p>
                        </div>
                        <div class="weui-flex pd20 fs28">
                            <div class="weui-flex__item tc br">
                                <a href="<?php echo  url::to(['member/recharge'])?>" class="wallet-btn">账户充值</a>
                            </div>
                            <div class="weui-flex__item tc">
                                <a href="<?php echo  Url::to(['member/withdrawal'])?>" class="wallet-btn">余额提现</a>
                            </div>
                        </div>
                    </div>
                    <div class="ponit-mian">
                    <div class="title">余额明细</div>
                    <div class="log">
                      <?php if(!empty($log)): ?>
                        <?php foreach ($log as $v):?>
                        <div class="ponit-item">
                            <div class="item-l">
                                <div class="item-l__tit"><?=Tools::get_account_log($v['type'])?></div>
                                <div class="item-l__time"><?=$v['desc']?></div>
                                <div class="item-l__time"><?=date('Y-m-d H:i:s',$v['created_at'])?></div>
                            </div>
                            <div <?=$v['change_money']>0?'class="item-r crGreen"':'class="item-r"'?>>
                            <?=$v['change_money']>0?'+'.$v['change_money']:$v['change_money']?></div>
                        </div>
                        <?php endforeach;?>
                        <?php else: ?>
                          <div class="null-data">
                            <!-- <i class="iconfont icon-Null-data"></i> -->
                            <div>暂无余额的交易记录</div>
                          </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->beginBlock('block1') ?>  

      // 滚动加载
       var pages = 1;
       var loading = false;  //状态标记
       var count = 2;
       
    $(document.body).infinite().on("infinite", function() {
      if(loading) return;
      loading = true;
      pages++; //页数
      setTimeout(function() {
        if(pages<=count){
            loadlist();          
        }else if(count>=1){
           loading = true;        
        }
        loading = false;
      }, 1500);   //模拟延迟   
   		 }
	);		
      function loadlist() {
           var html = "";
           $.ajax({
               type: "POST",
               url: "/member/money-log?page="+pages,
               data: {},
               dataType: "json",
               error: function (request) {
               },
               success: function (data) {
                    count = data.pagecount;
                   if(data.pagecount==1){
                        $.hideLoading();


                        html += '<div class="null-data"><div>没有更多了~</div></div>';


                        $(".log").append(html);
                        return;
                   }else{
                       for (var i = 0; i < data.items.length; i++) {
                       			     html += ' <div class="ponit-item">';
                         			 html += ' <div class="item-l">';
                          		     html += ' <div class="item-l__tit">';
                          		     html += get_account_log(data.items[i].type);
                          		     html +='</div>';
                          		     html +='<div class="item-l__time">'+data.items[i].desc+'</div>';
                          			 html += ' <div class="item-l__time">';
                          			 html += getLocalTime(data.items[i].created_at);
                          			 html +='</div>';
                        			 html += ' </div>';
                        		     html += getchange(data.items[i].change_money);
                       			     html += ' </div>';
                       }
                       $(".log").append(html);
                       $.hideLoading();
                     }  
               }
           });
   		}
   		function get_account_log(type){
   		str = "";
        switch (type) {
            case 1:
                str = "订单消费";
                break;
            case 2:
                str = "充值";
                break;
            case 3:
                str = "活动赠送";
                break;
            case 4:
                str="管理员操作";
             break;
            case 5:
                str = "到店支付";
                break;
            case 6:
                str = "分销提成";
                break;
            case 7:
                str = "订单退回";
                break;
            case 8:
                str="提现";
                break;
            case 9:
                str="红包";
                break;   
            default:
                str="系统出错";
        }
        return str;
   		}
   	function getLocalTime(nS) { 
		var nS = new Date(nS*1000);
        var year = 1900 + nS.getYear();
        var month = "0" + (nS.getMonth() + 1);
        var date = "0" + nS.getDate();
        var hour = "0" + nS.getHours();
        var minute = "0" + nS.getMinutes();
        var second = "0" + nS.getSeconds();
        return year + "-" + month.substring(month.length-2, month.length)  + "-" + date.substring(date.length-2, date.length)
            + " " + hour.substring(hour.length-2, hour.length) + ":"
            + minute.substring(minute.length-2, minute.length) + ":"
            + second.substring(second.length-2, second.length);
   	 } 
   	 function getchange(score) { 
   	 	var str=""
   	 		if(score<0){
   	 			str='<div class="item-r">'+score+'</div>';
   	 			return str;
   	 		}else{
   	 			str='<div class="item-r crGreen">+'+score+'</div>';
   	 			return str;
   	 		}
   	 }

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?>  