<?php
use yii\helpers\Html;
use frontend\themes\advance\assets\AppAsset;

/* @var $this yii\web\View */
?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<meta content="email=no" name="format-detection">
<?php //AppAsset::register($this)?>
<?php $this->registerJsFile('@web/static/js/adaptive.js',['depends' => 'frontend\themes\advance\assets\AppAsset','position'=>\yii\web\View::POS_HEAD]);?>
<?php $this->registerJsFile('@web/static/js/calculate.js',['depends' => 'frontend\themes\advance\assets\AppAsset','position'=>\yii\web\View::POS_HEAD]);?>
<?php $this->registerJsFile('@web/static/js/fastclick.min.js',['depends' => 'frontend\themes\advance\assets\AppAsset','position'=>\yii\web\View::POS_HEAD]);?>
<?php $this->registerJsFile('@web/static/js/fastclick.min.js',['depends' => 'frontend\themes\advance\assets\AppAsset','position'=>\yii\web\View::POS_HEAD]);?>
<?php $this->registerJsFile('@web/static/js/swipe.js',['depends' => 'frontend\themes\advance\assets\AppAsset','position'=>\yii\web\View::POS_HEAD]);?>
<?php $this->registerJsFile('@web/static/js/zepto.min.js',['depends' => 'frontend\themes\advance\assets\AppAsset','position'=>\yii\web\View::POS_HEAD]);?>
<?php $this->registerJsFile('@web/static/js/main.js',['depends' => 'frontend\themes\advance\assets\AppAsset','position'=>\yii\web\View::POS_HEAD]);?>
<?=Html::cssFile('/static/css/mycommon.css')?>


<style type="text/css">

.main .heading{
    padding: 0 .3rem;
    height: 1rem;
    overflow: hidden;
    border: 1px solid #e8e8e8;
    background:#fff;
}

.heading input{
    display: block;
    width: 100%;
    height: .6rem;
    font-size: .5rem;
    color: #383838;
    margin: .2rem auto;
    border:none;
    outline: none;
    float: left;
}
.main .heading1{
    padding: 0 .3rem;
    height: 1rem;
    overflow: hidden;
    border: 1px solid #e8e8e8;
    background:#fff;
    
}
#tablecss{

width: 100%;
border-right:1px solid #e4e4e4;border-bottom:1px solid #e4e4e4;
font-size: .5rem;
line-height: 1rem;
}
.tablecsstd {
    width: 25%;
    text-align: center;
    border-left:1px solid #e4e4e4;border-top:1px solid #e4e4e4;
    height: 1.5rem;
    padding: 0px;font-size:.6rem;
}
.tablecsstd1{
    width: 25%;
    text-align: center;
    border-left:1px solid #e4e4e4;border-top:1px solid #e4e4e4;
    height: 3rem;
    padding: 0px;
}
.tablecsstd4{
    width: 25%;
    text-align: center;
    border-left:1px solid #e4e4e4;border-top:1px solid #e4e4e4;
    height: 3rem;
    padding: 0px;font-size:.6rem;
}
.tablecsstdv{
    color: white;
    background: gray;
}

.body-qrcode{margin-top:0px;width:100%;background-color:#fff;padding:5px 0;text-align:center;height:50px;}
.body-qrcode #img_status{width:25px;height:25px;}
.body-qrcode p{color:rgb(154,154,154);height:20px;line-height:20px;}
</style>
<title>牧歌田园</title>
<!--优先加载-->
<script>
window['adaptive'].desinWidth = 750;
window['adaptive'].baseFont = 18;
window['adaptive'].maxWidth = 750;
window['adaptive'].init();
</script>
</head>
<body>
<div class="wrap">
<input type="hidden" id="zijipay" />
<div class="main" style="background-color:white;padding-bottom: .3rem;">
<h1 style="background: white;font-size: .5rem; text-align: center; line-height: .8rem;">牧歌田园古屋农庄店</h1>
<input type="hidden" id="uid"  value="1"/>
<input type="hidden" id="open_id" value="okoAu1Is3_0DVsghubO-TeaWalsc"/>
<input type="hidden" id="order_id"  value=""/>
<div class="banner" id="swipeWrap">
<ul class="swipeBox">
<li><a href="https://mgtyny.buyqi.net/app/index.php?i=3&c=entry&actid=1&do=action&m=gengkuai_prize"><img src="http://mgtyny2019-1-16.oss-cn-shenzhen.aliyuncs.com/images/3/2019/01/ozJrZON17XH7O57HhWZjhkQHpJ7jjx.jpg"></a></li>
</ul>
<div class="swipeBtn">
<span class="active"></span>
</div>
</div>
<div class="heading">
<input type="text" id="money" value="" placeholder="金额" disabled="disabled"/></td>
</div>
<div class="body-qrcode" >
<div id="weishuru" style="margin-top: 15px; ">
<img id="img_status" src="/static/img/tip.png">
<p>输入完金额点击付款</p>
</div>
<div id="erweima" style="margin-top: 5px; display: none;">
<img id="imgurl" src='/static/img/dengdai.gif'  style="width: 1.5rem;" />
<p id="paystatus">正在支付请稍等</p>
</div>

</div>
<div style="margin-top:10px;">
<table id="tablecss"  border="0"  cellspacing="0" cellpadding="0">
<tr>
<td class="tablecsstd" >1</td>
<td class="tablecsstd" >2</td>
<td class="tablecsstd" >3</td>
<td rowspan="2" class="tablecsstd1"><img src="/static/img/delete.png" class="del-png" style="width: 40%;"></td>
</tr>
<tr>
<td class="tablecsstd" >4</td>
<td class="tablecsstd" >5</td>
<td class="tablecsstd" >6</td>
</tr>
<tr>
<td class="tablecsstd" >7</td>
<td class="tablecsstd" >8</td>
<td class="tablecsstd" >9</td>
<td rowspan="2" class="tablecsstd4" id="wancheng" >付<br>款</td>
<td rowspan="2" class="tablecsstd4"   id="qingkong"  style="display: none;"onclick="qiongkong()">清<br>空</td>
</tr>
<tr>
<td class="tablecsstd" >.</td>
<td class="tablecsstd" >0</td>
<td class="tablecsstd" >00</td>
</tr>
</table>
</div>
</div>

</div>
<script type="text/javascript">
$(document).ready(function(){
    $('.tablecsstd').on("touchstart", function(){
        $(this).addClass('tablecsstdv');
        var tdval=$(this).html();
        if(tdval=='.'){
            dot();
        }else{
            inputEvent(tdval);
        }
    });
        $('.tablecsstd').on("touchend", function(){
            $(this).removeClass('tablecsstdv');
        });
            $('.tablecsstd1').on("touchstart", function(){
                del();
            });
                $('#wancheng').on("touchstart", function(){
                    shoukuan();
                });
                    
});
    var dingshi1='';
    var dingshi2='';
    function shoukuan(){
        $('#zijipay').val('0');
        var money=$('#money').val();
        var huiyuan=$('#huiyuan').val();
        var uid=$('#uid').val();
        var open_id=$('#open_id').val();
        if(money=='' || money=='0' || money=='0.0' || money=='0.00'){
            alert('收款金额不能为空或0');return false;
        }
        var m=money.substr(money.length-1,1);
        if(m=='.'){
            alert('收款金额格式错误');return false;
        }
        $.ajax({
            url: './index.php?i=3&c=entry&do=doguding&m=bobo_pay&random='+Math.random(),
            type: 'POST',
            dataType: 'json',
            data: {money: money,randomshu:Math.random()},
            success: function (result) {
                if(result.status==1){
                    alert(result.msg);
                }else{
                    $('#order_id').val(result.orderid);
                    dingshi1=setTimeout("wxquery('"+result.orderid+"')",500);
                    weixingoumai(result.aa);
                }
            }
        });
    }
    function wxquery(out_trade_no){
        var order_id=out_trade_no;
        $.ajax({
            url: './index.php?i=3&c=entry&do=dowxpayquery&m=bobo_pay&random='+Math.random(),
            type: 'POST',
            dataType: 'json',
            data: {out_trade_no: out_trade_no,randomshu:Math.random()},
            success: function (result) {
                if(result.status==1){
                    $('#zijipay').val('1');
                    $('#img_status').attr('src',imgsrc);
                    $('#wancheng').hide();
                    $('#qingkong').show();
                    $('#paystatus').html('支付成功,'+result.msg);
                    $('#paystatus').show();
                    clearTimeout(dingshi1);
                    clearTimeout(dingshi2);
                    return false;
                }else{
                    dingshi2=setTimeout("wxquery('"+order_id+"')",500);
                }
            }
        });
    }
    function jsApiCall(result)
    {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            result,
            function(res){
                WeixinJSBridge.log(res.err_msg);
                var msg=res.err_msg;
                if(msg.indexOf("ok")>=0){
                    var uid=$('#uid').val();
                    var out_trade_no=$('#order_id').val();
                    if($('#zijipay').val()==0){
                        $.ajax({
                            url: './index.php?i=3&c=entry&do=dowxpayquery&m=bobo_pay',
                            type: 'POST',
                            dataType: 'json',
                            data: {out_trade_no:out_trade_no,uid:uid,randomshu:Math.random()},
                            success: function (result) {
                                if(result.status==2){
                                    $('#img_status').attr('src',imgsrc1);
                                    clearTimeout(dingshi1);
                                    clearTimeout(dingshi2);
                                    return false;
                                }else{
                                    $('#img_status').attr('src',imgsrc);
                                    $('#wancheng').hide();
                                    $('#qingkong').show();
                                    $('#paystatus').html('支付成功,'+result.msg);
                                    $('#paystatus').show();
                                    clearTimeout(dingshi1);
                                    clearTimeout(dingshi2);
                                    return false;
                                }
                                
                            }
                        });
                    }
                    
                }else if(msg.indexOf("cancel")>=0){
                    alert('已取消购买！');window.location.reload();
                    clearTimeout(dingshi1);
                    clearTimeout(dingshi2);
                    return false;
                }else{
                    alert(res.err_desc+'!');
                    clearTimeout(dingshi1);
                    clearTimeout(dingshi2);
                    return false;
                }
                
            }
            );
        
    }
    
    
    function weixingoumai(result)
    {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall(result), false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall(result));
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall(result));
            }
        }else{
            jsApiCall(result);
        }
        
    }
    function qiongkong(){
        clearTimeout(dingshi1);
        clearTimeout(dingshi2);
        $('#money').val('');
        $('#huiyuan').val('');
        $('#erweima').hide();
        $('#weishuru').show();
        $('#fukuanma').html('');
        $('#qingkong').hide();
        $('#wancheng').show();
    }
    </script>
    <script>var imgs = document.getElementsByTagName('img');for(var i=0, len=imgs.length; i < len; i++){imgs[i].onerror = function() {if (!this.getAttribute('check-src') && (this.src.indexOf('http://') > -1 || this.src.indexOf('https://') > -1)) {this.src = this.src.indexOf('https://mgtyny.buyqi.net/attachment/') == -1 ? this.src.replace('http://mgtyny2019-1-16.oss-cn-shenzhen.aliyuncs.com/', 'https://mgtyny.buyqi.net/attachment/') : this.src.replace('https://mgtyny.buyqi.net/attachment/', 'http://mgtyny2019-1-16.oss-cn-shenzhen.aliyuncs.com/');this.setAttribute('check-src', true);}}};</script><script type="text/javascript" src="https://mgtyny.buyqi.net/app/index.php?i=3&c=utility&a=visit&do=showjs&m=bobo_pay"></script></body>
    </html>
     