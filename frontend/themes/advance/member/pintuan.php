<?php
use yii\helpers\Url;
use common\helpers\Tools;
use yii\bootstrap\Alert;
?>
<style type="text/css">
body{background-color: #f2f2f2;}
.cell-box{margin-top: .32rem;}
.cell-box-bt a{padding: .18rem .3rem;font-size: .28rem;}
.cell-box>p{line-height: .8rem;}
.cell-box>a{background-color: #fbfbfb}
</style>
<header class="top-fixed">
	<div class="weui-flex top-box">
		<div onclick="window.open('<?=url::to(['/member/index'])?>','_self');">
			<i class="iconfont icon-fanhui"></i>
		</div>
		<div class="weui-flex__item">我的拼团</div>
		<div>
			<i class="iconfont icon-mulu" id="mulu-bt"></i>
		</div>
	</div>
</header>
<div class="order_tab">
    <a href="<?=Url::to([''])?>" <?=$status==''?'class="active"':''?>>全部</a>
    <a href="<?=Url::to(['','status'=>1])?>" <?=$status==1?'class="active"':''?>>进行中</a>
    <a href="<?=Url::to(['','status'=>2])?>" <?=$status==2?'class="active"':''?>>成功</a>
    <a href="<?=Url::to(['','status'=>3])?>" <?=$status==3?'class="active"':''?>>失败</a> 
  </div>
<?=$this->render('../layouts/cart_menu')?>
<div class="main" style="margin-top: 2rem;">
    <?php  if(!empty($data)): ?>
  	<?php foreach ($data as $key): ?>
    	<div class="cell-box">
        <?php $num=0;?>
        	<?php if(count($key['orderSku'])>1):?>
    		<a href="<?=Url::to(['order/detail','order_id'=>$key['id']])?>" class="weui-flex many_img" style="">
    		
    		  <?php foreach ($key['orderSku'] as $kk=>$vo):?>	
    		     <?php $num+=$vo['num']?>
    		    <div  class='sku_img_5'>
    		          <img src="<?=$vo['sku_thumbImg']?>">
    			</div>
    		

    		  <?php endforeach;?>
    		</a>
    		
    		<?php else:?>
    		<a href="<?=Url::to(['order/detail','order_id'=>$key['id']])?>" class="weui-flex" style="">
    			<div class='sku_img'>
    				<img src="<?=$key['orderSku'][0]['sku_image']?>">
    			</div>
    			<div class="weui-flex__item order-name">
    				<p>
    					<span><?=$key['orderSku'][0]['goods_name']?></span>
    				</p>
    			</div>
    		</a>
    		<?php $num=$key['orderSku'][0]['num']?>
    		<?php endif;?>

    		<p class="">
    			共计<?=$num?>件商品  合计：<em>￥<?=$key['pay_amount']?></em> （含运费<?=$key['delivery_price']?>元）
    		</p>
    		<div class="cell-box-bt">
    		<?=Tools::get_status_bottom($key['status'],$key['id']) ?>
    		</div>
      </div>
    	<?php endforeach; ?>
    <?php else: ?>
      <div class="null-data">
        <i class="iconfont icon-Null-data"></i>
        <div>没有相关拼团信息</div>
        <div class="gohome"><a href="<?=url::to(['/site/index'])?>">去逛逛吧</a></div>
      </div>
    <?php endif; ?>
    </div>
    <div class="nomore">加载更多...</div>
<?php $this->beginBlock('block1') ?>
      // 滚动加载
       var pages = 1;
       var loading = false;  //状态标记
       var count = 2;
       var status=<?=Yii::$app->request->get('status','0')?>
       
    $(document.body).infinite().on("infinite", function() {
    
      if(loading){
        return false;
      }
      loading = true;
      pages++; //页数
      loadlist();          
    });		
		function getstatus(status){
			var str="";
		
            switch (status)
            {
                case '1':
                    str="待支付";
                    break;
                case '2':
                    str="待发货";
                    break;
                case '3':
                    str="已发货";
                    break;
                case '4':
                    str="完成订单";
                    break;
                case '5':
                    str="已评价";
                    break;
                case '6':
                    str="已退款";
                    break;
                case '7':
                    str="部分退款";
                    break;
                case '8':
                    str="用户取消";
                    break;
                case '9':
                    str="超时作废";
                    break;
                case '10':
                    str="退款中";
                    break;
                case '11':
                    str="拒绝退款";
                    break;
                default:
                    str="未知状态";
                }
                return str;
    		}
		   function get_status_bottom(status,order_id,payment_id){
            str='';
            switch (status)
            {
                
                case '1':
                    str='<a class="cancel"  data-href="/order/cancel?order_id='+order_id+'">取消订单</a><a href="/order/pay?order_id='+order_id+'" class="crfff bgred bdn">立即支付</a>';
                    break;
                case '2':
                    str='<a href="">取消订单</a>';
                    break;
                case '3':
                    str='<a href="">申请退款</a><a href="/order/shipping?order_id='+order_id+'">查看物流</a><a href="" class="crfff bgred bdn">确认收货</a>';
                    break;
                case '4':
                    str='<a href="">申请退货/退款</a><a href="/product-comment/list?order_id='+order_id+'" class="crfff bgred bdn">点评</a>';
                    break;
                case '5':
                    str="已评价";
                    break;
                case '6':
                    str="已退款";
                    break;
                case '7':
                    str="部分退款";
                    break;
                case '8':
                    str='<a class="del_order" data-href="/order/delete?order_id='+order_id+'">删除订单</a>';
                    break;
                case '9':
                    str="超时作废";
                    break;
                case '10':
                    str="退款中";
                    break;
                case '11':
                    str='<a class="del_order" data-href="/order/result?order_id='+order_id+'">查看原因</a>';
                    break;
                default:
                    str="未知状态";
            }
            return str;
        }

    function loadlist() {
          $(".nomore").show();
           var html = "";
           $.ajax({
               type: "POST",
               url: "/order/ajax?page="+pages,
               data: {'status':status },
               dataType: "json",
               error: function (request) {
					
               },
               success: function (data) {
                    count = data.pagecount;
                   if(data.items.length < 1){
                        $(".nomore").text("~没有更多订单了~");
                        $(".nomore").show();
                        return false;
                   }else{
                       for (var i = 0; i < data.items.length; i++) {
                       		
                             html += ' <div class="cell-box">';
                             html += ' <p class="order-list-title">';
                             html += ' <span class="shop-name">'+ data.items[i].shop.name +'</span>';
                             html += ' <span class="crred">';
                             html += getstatus(data.items[i].status);
                             html +='</span>';
                             html += ' </p>';
                             html += ' <a href="/order/detai?id='+ data.items[i].id +'" class="weui-flex" style="">';
                        	 html += ' <div>';
                        	 html += ' <img src='+ data.items[i].orderSku[0].sku_image +'>';
                        	 html += ' </div>';
                        	 html += ' <div class="weui-flex__item">';
                        	 html += ' <p><span>'+ data.items[i].orderSku[0].goods_name +'</span></p>';
                        	 html += ' </div>';
                        	 html += ' </a>';
                        	 html += ' <p class="">共1件商品 合计：<em>￥'+ data.items[i].order_price +'</em> （含运费'+ data.items[i].delivery_price +'元）</p>';
                        	 html += ' <div class="cell-box-bt">';
                        	 html +=get_status_bottom(data.items[i].status,data.items[i].id,data.items[i].payment_id)
                        	 html +='</div>';
                       		 html += ' </div>';
                       }
                       $(".main").append(html);
                       $(".nomore").hide();
                       loading = false;
                     }
                       
               }
           });

    }
    //取消订单
    $(".main").on('click','.cancel',function(e){
        var href = $(this).data('href');
        cancel(href);
    });
    function cancel(href){
      $.confirm("您确定要取消订单吗？", function() {
         window.location.href = href;
        }, function() {
         
        }
      );
    }
    //删除订单
    $(".main").on('click','.del_order',function(e){
        var href = $(this).data('href');
        del(href);
    });
    function del(href){
      $.confirm("您确定要删除订单吗？", function() {
         window.location.href = href;
        }, function() {
         
        }
      );
    }
    //申请退款
    $(".main").on('click','.refund',function(e){
        var href = $(this).data('href');
        refund(href);
    });
    function refund(href){
      $.confirm("您确定要申请退款吗？", function() {
         window.location.href = href;
        }, function() {
         
        }
      );
    }

<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['block1'], \yii\web\View::POS_END); ?>  