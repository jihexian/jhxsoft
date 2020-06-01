<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年11月23日 下午5:33:50
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
use yii\helpers\Url;
?>
<style type="text/css">
.sub{width: 90%;margin: 0 auto;margin-top: 30px;}
.tc{border-radius: 30px;}
</style>
<header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                编辑地址
                </div>
                <div>
                    <i class="iconfont icon-mulu" id="mulu-bt"></i>
                </div>
            </div>
        </header>
       <?=$this->render('../layouts/cart_menu')?>
   
    
        <div class="main add-addr">
           <form name="form" method="post" id="form">
            <div class="weui-cells">
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">收货人姓名：</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" id="userName" name="userName" value="<?=$data['userName']?>"  aria-invalid="true" type="text" placeholder="必填">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">手机号：</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" id="telNumber" name="telNumber"  value="<?=$data['telNumber']?>"   type="tel" placeholder="必填">
                    </div>
                </div>
            <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label for="name" class="weui-label">省/自治区：</label>
                    </div>
                    <div class="weui-cell__bd">
                       <select name="province_id"  id="province" required  class="weui-select wp3" >
                         <option selected value="<?=$data->province->id?>"><?=$data->province->name?></option>
                         <?php foreach ($province as $vo):?>
                            <option value="<?=$vo['id']?>"><?=$vo['name']?></option>
                         <?php endforeach;?>
                        </select>
                   
                    </div>
                </div>
                    <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label for="name" class="weui-label">城市：</label>
                    </div>
                    <div class="weui-cell__bd">
                       <select name="city_id"  id="city" required  class="weui-select wp3" >
                         <option selected value="<?=$data->city->id?>"><?=$data->city->name?></option>
                        </select>
                       </select>
                    </div>
                </div>
                    <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label for="name" class="weui-label">区/县：</label>
                    </div>
                    <div class="weui-cell__bd">
                      <select name="region_id"  id="region" required  class="weui-select wp3" >
                        <option selected value="<?=$data->county->id?>"><?=$data->county->name?></option>
                       </select>
                    </div>
                </div>
           
           
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <textarea class="weui-textarea" id="detailInfo" aria-required="true" name="detailInfo" placeholder="请填写详细地址(街道、楼牌号等)" rows="3"><?=$data['detailInfo']?></textarea>
                       <!--  <div class="weui-textarea-counter"><span>0</span>/200</div> -->
                    </div>
                </div>
            </div>
             <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>"> 
             </form> 
             <section class="sub">
           
            <div class="tc pd20 fs32 bg04BE02 crfff" id="show-toast">提交</div>
            </section>
        </div>
             
    </div>   
<?php 
$url=Url::to(['address/update','id'=>$data['id']]);
$this->registerJs(<<<JS
  $("#start").cityPicker({
        title: "收货地址",

  });


  var save_flag = true;

  $('.sub').click(function(){
    if(!$('#userName').val()){
      $.toast("请填写收货人姓名", "forbidden");
      $('#userName').focus();
      return false;
    }
    if(!$('#telNumber').val()){
      $.toast("请填写手机号", "forbidden");
      $('#telNumber').focus();
      return false;
    }
    if(!$('#detailInfo').val()){
      $.toast("请填写详细地址", "forbidden");
      $('#detailInfo').focus();
      return false;
    }
    if(save_flag){
         $.ajax({
             type: "post",
             url: "$url",
             data: $('form').serialize(),
             dataType: "json",
             beforeSend: function(){
                save_flag = false;
                $("#show-toast").text("正在提交...");
             },
             success: function(data){
              if(data.status===1){
                $.alert(data.msg)
                $(window).attr('location',"/address/index");
              }else{
                $.alert('操作失败')
              }
            },
            complete:function(rs){
              $("#show-toast").text("提交");
              save_flag = true;
            }         
         });
    }
   
   });  
  
JS
);
?>