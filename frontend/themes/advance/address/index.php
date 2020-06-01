<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年11月16日 下午5:21:33
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Url;
$source=Yii::$app->request->get('source','');
$type=Yii::$app->request->get('type','');
$id=Yii::$app->request->get('id','');
?>
<header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="window.open('<?=$source==''?Url::to(['/member/index']):(($type==''&&$id=='')?Url::to(['/cart/confirm']):Url::to(['/cart/confirm','type'=>$type,'id'=>$id]))?>','_self');"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                    收货信息
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
          <?=$this->render('../layouts/cart_menu')?>
        <section class="bottom-fixed">
        	<div class="tc pd20 fs32 bg04BE02 crfff"><i class="iconfont icon-xinzeng in-block mgr20 fs40"></i>新增收货地址</div>
        </section>
        <div class="main" style="margin-top: .88rem;">
            <ul class="addr-list">
         
              <?php foreach ($data as $key=>$vo):?>
               
                
                <?=$source==''?'':'<a href="'.Url::to(['cart/confirm','address'=>$vo['id'],'type'=>$type,'id'=>$id]).'">'?>
                <li>
                	<div class="list-item_bd">
                		<div>
                			<span class="mgr30"><i class="iconfont icon-yonghu vmd"></i><?=$vo['userName']?></span>
                			<span><i class="iconfont icon-shoujihao"></i><?=$vo['telNumber']?></span>
                		</div>
                		<p><?php echo $vo->province->name.$vo->city->name.$vo->county->name;?> <?=$vo['detailInfo']?></p>
                	</div>
                	 <?php if($source==''):?>
                	<div class="list-item_bt">
                		<div class="bt-l" style="">
                			<i name="<?=$vo['id']?>" class="sel-bt mgr20 <?=$vo['is_default']==1?'sel-bt-active':''?>"></i>
                			设置为默认地址
                		</div>
                		 
                          <div class="bt-r">
                			<a href="<?=Url::to(['address/update','id'=>$vo['id']])?>"><i class="iconfont icon-bianji in-block"></i>编辑</a>
                			<a href="javascript:;" class="del" name="<?=$vo['id']?>"><i  class="iconfont icon-shanchu in-block"></i>删除</a>
                		</div>	
                	</div>
                	  <?php endif;?>
                </li>
                <?=$source==''?'</a>':''?>
                
            <?php endforeach;?>
            </ul>
            <div class="no-content hide">
            	<i class="iconfont icon-zanwuneirong1"></i>
            	<p>暂无内容</p>
            </div>
        </div>
    </div>
<?php 
if(empty($type)){
         $url=Url::to(['address/add','source'=>$source]);
     }else{
         $url=Url::to(['address/add','source'=>$source,'type'=>$type,'id'=>$id]);
     }
$this->registerJs(<<<JS
 $(".bottom-fixed").click(function(){
 $(window).attr('location',"$url");
});
$('.del').click(function(){
           var id;
           var li
           li = $(this).parent().parent().parent();
          id=$(this).attr('name');
      
  $.confirm("你确定要删除吗？", function() {
    	  //点击确认后的回调函数
         
    	 $.ajax({
             type:"POST",
             dataType:"json",
             url:'/address/delete?id='+id,
             success:function(e){
               if(e.status===1){
                 li.remove();
                 $.alert(e.msg);
                }else{
                 $.alert(e.msg);
                }  
             }
         });
    	  }, function() {
    	  //点击取消后的回调函数
    });
});


$(".sel-bt").on("click", function() {
    if ($(this).hasClass("sel-bt-active")) {
        $(this).removeClass("sel-bt-active");
    } else {
        $(this).addClass("sel-bt-active").parents("li").siblings("li").find(".sel-bt").removeClass("sel-bt-active");
    }
    var id = $(this).attr("name");
    $.ajax({
       type:'post',
       dataType:'json',
       url:'/address/default',
       data:{'id' :id},
       success:function(msg){
           console.log(msg)
       },  
    });
})

JS
);
?>
    