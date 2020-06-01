<?php
use yii\helpers\Url;
?>
<style type="text/css">
  body{background-color: #f2f1f1;}
  .container{width: 92%;margin: 0 auto;}
  .shop-name{font-weight: bold;}
  .flex{display: flex;flex-wrap: wrap; margin-top: 15px;}
  .item-box{background-color: #fff;border-radius: 6px;padding:15px;}
  .shop-title{font-weight: bold;width: 24%;margin-right:1%;}
  .shop-content{width: 75%;color: #666;}

  .shop-info{display: flex;align-items: center;margin-top: 1rem;justify-content: space-between;}
  .shop-logo{width: 40px;margin-right: 10px;}
  .shop-info a{color: #000;}
  .shop-left{display: flex;align-items: center;}
  .collection{color: #04BE02;border:1px solid #04BE02;border-radius: 30px;padding: 3px 10px;}
  .collected{background-color: #ff4444;color: #fff;border-radius: 30px;padding: 3px 10px;}
  .split{width:100%;margin: 10px 0;border-top:1px solid #f9f9f9;}

  .goods{text-align: center;color: #ff5000;margin-top: 20px;}
</style>
<header class="top-fixed">
	<div class="weui-flex top-box">
		<div onclick="javascript:history.back(-1);">
			<i class="iconfont icon-fanhui"></i>
		</div>
		<div class="weui-flex__item">
			<div class="weui-flex" >
				<div class="weui-flex__item">店铺详情</div>
			</div>
		</div>
		<div class="weui-flex mgr20">
			<!-- <i class="iconfont icon-fenxiang weui-flex__item fengxiang"></i> -->
			<i class="iconfont icon-mulu1 weui-flex__item" id="mulu-bt"></i>
		</div>
	</div>
</header>
<aside class="goods-nav hide">
    <ul>
        <li><a href="<?=Url::to(['site/index'])?>"><i class="iconfont icon-shouye"></i>首页</a></li>
        <li><a href="<?=Url::to(['member/index'])?>"><i class="iconfont icon-renwu"></i>个人中心</a></li>
        <li><a href="<?=Url::to(['order/all'])?>"><i class="iconfont icon-dingdan"></i>全部订单</a></li>
    </ul>
</aside> 

<div class="container">
  <div class="shop-info item-box">
      <div class="shop-left">
        <div><img class="shop-logo" src="<?=$shop['logo']?>" /></div>
        <div class="weui-flex__item shop-name"><?=$shop['name']?></div>
      </div>
      <div>
        <span id='collection' class="<?=$isFavorite==0 ? 'collection':'collected'?>"><?=$isFavorite==0 ? '收藏':'已收藏'?></span>
      </div>
  </div>
  <div class="item-box flex">
      <div class="shop-title">
       支付二维码
      </div>
      <div class="shop-content" style="text-align: right;">
        <i class="iconfont icon icon-erweima2"></i>
      </div>
  </div>
  <div class="item-box flex">
      <div class="shop-title">
       店铺简介
      </div>
      <div class="shop-content">

        <?=$shop['description']?>
      </div>
      <div class="split"></div>
  </div>
  <a href="<?=url::to(['shop/index','shop_id'=>$shop['id']])?>">
  <div class="item-box goods">
    查看全部商品
  </div>
</a>
</div>

<?php $this->beginBlock('block1') ?>  
     $("#mulu-bt").click(function() {
        $("#mulu-more").toggle(500)
     })
    var is_distribut='<?=yii::$app->session->get('is_distribut')?>';
    var pid='<?=Yii::$app->user->id?>';
    var defaultImg="<?=Yii::$app->params['defaultImg']['default']?>";
    //收藏店铺
    <?=$isFavorite==0?'var isFavorite=false;':'var isFavorite=true;'?>
    var shop_id=<?=$shop['id']?>;
    var save_flag = true;
    $("#collection").click(function() {
        if(save_flag){
             if(isFavorite){
                 $.ajax({
                 type: 'post',
                 dataType:"json",
                 url: "/collection/del-shop",
                 data: {shop_id:shop_id},
                 beforeSend: function(){
                  save_flag = false;
            },
                 success: function(e) {
                      if(e.status==1){
                $.toast(e.msg);
                          $('#collection').removeClass('collected');
                          $('#collection').addClass('collection');
                          $('#collection').text('收藏');
                          isFavorite=!isFavorite;
                          save_flag = true;
            }else{
               $.toast(e.msg, "forbidden");
                          save_flag = true;
            }
                  },
                error:function() {
                   $.toast("操作失败", "forbidden");
                  },
                })
              }else{
                 $.ajax({
                 type: 'post',
                 dataType:"json",
                 url: "/collection/add-shop",
                 data: {shop_id:shop_id},
                 beforeSend: function(){
                  save_flag = false;
            },
                 success: function(e) {
                      if(e.status==1){
                $.toast(e.msg);
                          $('#collection').addClass('collected');
                          $('#collection').removeClass('collection');
                          $('#collection').text('已收藏');
                          isFavorite=!isFavorite;
                          save_flag = true;
            }
            else if(e.status==2){
                            $.toast(e.msg, "forbidden");
                           location.href ="/site/login";
                      }
            else{
               $.toast(e.msg, "forbidden");
                          save_flag = true;
            }
                  },
                error:function() {
                   $.toast("操作失败", "forbidden");
                  },
                })
           }
      }
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
