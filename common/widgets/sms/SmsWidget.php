<?php
namespace common\widgets\sms;



use yii\base\Widget;
use yii\helpers\Url;

class SmsWidget extends  Widget
{
    public $scene;//场景 
    public $model;//模型
    public $form;//activeform
    public $attr;//模型的属性名
    public function init(){
        parent::init();        
    }
    
    public function run(){
        parent::run();
        return $this->registerClientScript();
    }
   

    public function registerClientScript()
    {
        $js = <<<'EOT'
             var csrfToken = $('meta[name="csrf-token"]').attr("content");
		    // 验证码倒计时
            var countdown=60; 
            function sendcode(scene){                
                    var obj = $("#codeBtn");
                    var mobile = $("input[name*='mobile']").val();     
                    if(checkMobile()&obj.attr('disabled')!='disabled'){
                        $.ajax({
                           type:'post',
                           dataType:'json',
                           url:#url#,
                           data:{'mobile':mobile,'scene':scene,'_csrf':csrfToken},
                           success:function(e){
                               settime(obj);                    
                           }
                        }) 
                    }           
                     
            }
            
            function settime(obj) { //发送验证码倒计时
                if (countdown == 0) { 
                    obj.attr('disabled',false); 
                    obj.html("获取验证码");
                    countdown = 60; 
                    return;
                } else { 
                    obj.attr('disabled',true);
                   
                    if(countdown<10){
                        obj.html("重新发送(0" + countdown + ")");
                    }else{
                         obj.html("重新发送(" + countdown + ")");
                    }
                    countdown--; 
            
                } 
            setTimeout(function() { 
                settime(obj) }
                ,1000) 
            }
            //手机号验证
            function checkMobile(){
                var mobile = $("input[name*='mobile']").val();
                var reg = /^1\d{10}$/;
                if(mobile.length===0){
                    mobileValid = false ;
                    $.toast("请输入11位手机号！","forbidden");
                    return false;     
                }else if(mobile.length===11){ 
                    if(!reg.test(mobile)){
                        $.toast("手机号格式错误！","forbidden");
                    }else{
                        return true;                           
                    }                    
                }else{
                    $.toast("请输入11位手机号！","forbidden");
                    return false;     
                }
            }
EOT;
        $js = str_replace('#url#', '"'.Url::to('/site/sms').'"', $js);
        $this->view->registerJs($js,\yii\web\View::POS_END); //因为可能会被pjax加载所以放在这里
        $fieldOptions = [
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => '<div class="send-code">{input}<span id="codeBtn" onclick="sendcode('.$this->scene.')">发送验证码</span></div>'
        ];
        $html = $this->form->field($this->model, $this->attr, $fieldOptions)
            ->label(false)
            ->textInput(['placeholder' => '请输入验证码']);
        
        return $html;
    }
   
}
