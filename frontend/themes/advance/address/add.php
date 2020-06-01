<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>     
     

        <header class="top-fixed">
            <div class="weui-flex top-box">
                <div onclick="javascript:history.back(-1);"><i class="iconfont icon-fanhui"></i></div>
                <div class="weui-flex__item">
                新增地址
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
                        <input class="weui-input" id="userName" name="userName"  aria-invalid="true" type="text" placeholder="必填" autofocus="ture">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">手机号：</label>
                    </div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" id="telNumber" name="telNumber"   type="tel" placeholder="必填">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label for="name" class="weui-label">省/自治区：</label>
                    </div>
                    <div class="weui-cell__bd">
                       <select name="province_id"  id="province" required  class="weui-select wp3" >
                         <option selected value="">请选择</option>
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
                         <option selected value="">请选择</option>
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
                        <option selected value="">请选择</option>
                       </select>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <label class="weui-label">详细地址：</label>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <textarea class="weui-textarea" id="detailInfo" aria-required="true" name="detailInfo" placeholder="必填" rows="3"></textarea>
                       <!--  <div class="weui-textarea-counter"><span>0</span>/200</div> -->
                    </div>
                </div>
            </div>
         <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>"> 
             </form> 
        </div>
             <section class="bottom-fixed">
            <div class="tc pd20 fs32 bg04BE02 crfff" id="show-toast">提交</div>
            </section>
    </div>   
<?php 
$source=Yii::$app->request->get('source');
$type=Yii::$app->request->get('type','');
$id=Yii::$app->request->get('id','');
if($type==''){
    $url=Url::to(['address/index','source'=>$source]);
}else{
    $type=Yii::$app->request->get('type','');
    $id=Yii::$app->request->get('id','');
    $url=Url::to(['address/index','source'=>$source,'type'=>$type,'id'=>$id]);
}
$this->registerJs(<<<JS
  var save_flag = true;
  $('.bottom-fixed').click(function(){
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
             url: "add",
             data: $('form').serialize(),
             dataType: "json",
             beforeSend: function(){
            save_flag = false;
                $("#show-toast").text("正在提交...");
         },
             success: function(data){
              if(data.status===1){
                $.toast(data.msg);
                $(window).attr('location',"$url");
              }else{
                $.toast(data.msg, "forbidden");
              }
            },
            complete:function(rs){
              $("#show-toast").text("提交");
              save_flag = true;
            }          
         });
        }
   });  
  
$('#province').change(function(){
    var options=$("#province");
    $("#city").html('');
    $("#region").html('');
    // 下面 是给 市 遍历值 的
    if(options.val()){
        $.ajax({
            url:"/api/v1/region/city",
            type:'POST',
            data:{
                id:options.val()
            },
            dataType:'JSON',
            success:function(data){
                var item = '<option selected value="">请选择</option>';
                for(var i = 0 ; i < data.items.length; i++) {
                    
                    item += '<option value="' +data.items[i]['id']+ '">' + data.items[i]['name'] + '</option>';
                }
                
                $("#city").append(item);
                
            },
            error:function(){
               
            }
        })
    }
});

$('#city').change(function(){
    var options=$("#city");
    $("#region").html('');
    // 下面 是给 市 遍历值 的
    if(options.val()){
        $.ajax({
            url:"/api/v1/region/area",
            type:'POST',
            data:{
                id:options.val()
            },
            dataType:'JSON',
            success:function(data){
               var item = '<option selected value="">请选择</option>';
                for(var i = 0 ; i < data.items.length; i++) {
                    
                    item += '<option value="' +data.items[i]['id']+ '">' + data.items[i]['name'] + '</option>';
                }
                
                $("#region").append(item);
                
            },
            error:function(){
           
            }
        })
        
    }

});
JS
);
?>

