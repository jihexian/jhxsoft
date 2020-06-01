<?php
use common\helpers\Tools;

?>
<header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    我的积分
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
                            <p class="fs28">可用积分</p>
                            <p class="yue">￥<?=$score?></p>
                        </div>
                     
                    </div>
                    <div class="ponit-mian">
                    <div class="title">积分明细</div>
                    <div class="log">
                    <?php if(!empty($log)):?>
                    <?php foreach ($log as $v):?>
                        <div class="ponit-item">
                            <div class="item-l">
                                <div class="item-l__tit"><?=Tools::get_account_log($v['type'])?></div>
                                <div class="item-l__time"><?=$v['desc']?></div>
                                <div class="item-l__time"><?=date('Y-m-d H:i:s',$v['created_at'])?></div>
                            </div>
                            <div <?=$v['change_score']>0?'class="item-r crf4"':'class="item-r"'?>>
                            <?=$v['change_score']>0?'+'.$v['change_score']:$v['change_score']?></div>
                        </div>
                        <?php endforeach;?>
                        <?php else:?>
                       
                          <p style="text-align: center;margin:20px 0px 0px;" >暂无数据</p>
                       
                        <?php endif;?>
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
               url: "/member/score-log?page="+pages,
               data: {},
               dataType: "json",
               error: function (request) {
               },
               success: function (data) {
                    count = data.pagecount;
                   if(data.pagecount==1){
                        $.hideLoading();
                        html += '<div class="no-product"><div class="iconfont icon-empty"></div><div>暂无商品~</div></div>';
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
                          			 html += ' <div class="item-l__time">'
                          			 html += getLocalTime(data.items[i].created_at);
                          			 html +='</div>';
                        			 html += ' </div>';
                        		     html += getchange(data.items[i].change_score);
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
   	 			str='<div class="item-r crf4">+'+score+'</div>';
   	 			return str;
   	 		}
   	 }

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?>  