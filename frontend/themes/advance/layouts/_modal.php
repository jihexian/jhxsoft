
 <div  id="p-info">

       		<h3 style="">完善信息</h3>
           
       		<div class="weui-cell">

            <icon class="iconfont icon-shouji iconfont-before" ></icon>
       		<input type="text" name="m_mobile" placeholder="请输入手机号" pattern="[0-9]*">
            <icon class="iconfont icon-guanbi1" ></icon>
       		</div>


       		<div class="weui-cell">
            <icon class="iconfont icon-yanzhengma iconfont-before" ></icon>
       		<input type="text" name="m_verifyCode" placeholder="请输入验证码" >
       		  
      			<button id="m-verify"  onclick="sendcode(1)" class="weui-vcode-btn">获取验证码</button>
    			
       		</div> 

       		<div class="weui-cell">

            <icon class="iconfont icon-home iconfont-before" ></icon>
       		<input type="text" name="m_company" placeholder="单位名称（选款）" >
          <icon class="iconfont icon-guanbi1" ></icon>
       		</div>

       		<div class="weui-cell">
            <icon class="iconfont icon-yunongtongxingming iconfont-before" ></icon>
       		<input type="text" name="m_name" placeholder="姓名或昵称（选款）" >
          <icon class="iconfont icon-guanbi1" ></icon>
       		</div>


          <div class="weui-cell c-box">
          <input type="checkbox" id="agreement" name="agree" value="agree" style="-webkit-appearance:checkbox!important;appearance:checkbox;">我已阅读并同意<a href="#" class="clause">《消费扶贫相关服务条款》</a>
       		<input type="text" hidden="hidden" name="csrf-frontend" value="<?= Yii::$app->request->csrfToken ?>" />
       		 </div>

      <!--      <label class="weui-cell weui-check__label" for="s11">
        <div class="weui-cell__hd">
          <input type="checkbox" class="weui-check" name="checkbox1" id="s11" checked="checked">
          <i class="weui-icon-checked"></i>
        </div>
        <div class="weui-cell__bd">
          <p>standard is dealt for u.</p>
        </div>
      </label> -->

       	 <a href="javascript:;" class="weui-btn weui-btn_primary weui-btn_disabled" style="width: 86%; line-height: 32px; margin-top: 10px;" onclick="info_submit(this)">提交</a>
          <p class="info-ft">注：请尽可能填写真实单位与姓名，方便平台做扶贫统计</p>

       </div>
       <?php $this->beginBlock('modal') ?>
           var countdown=60; 
            function sendcode(scene){      
                       
                    var obj = $("#m-verify");
                  
                    var mobile = $("input[name*='m_mobile']").val();  
                  
                    if(checkMobile()&obj.attr('disabled')!='disabled'){
                        $.ajax({
                           type:'post',
                           dataType:'json',
                           url:"/site/sms",
                           data:{'mobile':mobile,'scene':scene},
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
             
                var mobile = $("input[name*='m_mobile']").val();
                var reg = /^1(3|4|5|7|8)\d{9}$/;
                if(mobile.length===0){
                    mobileValid = false ;
                    alert("请输入11位手机号！");
                    return false;     
                }else if(mobile.length===11){ 
                    if(!reg.test(mobile)){
                        alert("手机号格式错误！");
                    }else{
                        return true;                           
                    }                    
                }else{
                    alert("请输入11位手机号！");
                    return false;     
                }
                
            }

           function checkCode(){

               var verifyCode = $("input[name*='m_verifyCode']").val();    
               if(verifyCode.length===0){

                      mobileValid = false ;
                      alert("验证码不能为空");
                      return false;     
                  } 
                 return true;
           }
            
             //完善信息对话框
          $(document).on("click", "#show-modal2", function() {
          $('#p-info').toggle()
            var mask = $("<div class='weui-mask '></div>").appendTo(document.body);
           mask.show();
           mask.addClass('weui-mask--visible');
           });

        //对话框1
            $(document).ready(function() {
        $.modal({
          title: "关于消费扶贫排名及数据",
          text: "为了更好的统计消费数扶贫的数据清完善信息资料",
          buttons: [
            { text: "立即填写", onClick: function(){
              
                $('#p-info').show(); 
                $('.weui-mask').remove()
                $('body').append("<div class='weui-mask '></div>");
               $('.weui-mask').show();
               $('.weui-mask').addClass('weui-mask--visible');
          } },
           
            { text: "不再提示", className: "default"},
          ]
        });
      });


      //完善信息对话框提交事件
      function info_submit(e){
      if(!($(e).hasClass('weui-btn_disabled'))){
                    var mobile = $("input[name*='m_mobile']").val(); 
                    var verifyCode = $("input[name*='m_verifyCode']").val();    
                    var name = $("input[name*='m_name']").val();    
                    var csrfToken =$("input[name*='csrf-frontend']").val();    
                    var company = $("input[name*='m_company']").val();       
                   if(  checkMobile()&checkCode()){
                       $.ajax({
                          type:'post',
                          dataType:'json',
                          url:"/member/bind-info",
                          data:{'mobile':mobile,'verifyCode':verifyCode,'username':name,'company':company,'_csrf':csrfToken},
                          success:function(dd){
                              if(dd.status==0){
                                alert(dd.msg);
                              }else{
                               $('#p-info').hide()
                              $('.weui-mask').remove()
                              $.alert("提交成功，开始购物", "完善信息");
                              window.location.reload()
                              }
                          
                          }
                       }) 
                   }  
        }
      }


      //清空input里的内容    
      $('.icon-guanbi').on('click',function(){
         $(this).siblings('input').val('')
    })



       //条款对话框
       $(document).on("click", ".clause", function() {
         
             $('.weui-mask').remove()
            $.alert({

                  title: '服务条款',
                  text: '条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条款内容条条款内容条款内容条款内容条款',
                  onOK: function () {
                    
                      $('.weui-mask').addClass('weui-mask--visible');

                  }
              });
       })


        $(document).on('click',function(e){
       
        if($(e.target).is($('.weui-mask')) && $('#p-info').css('display')!='none'){
        console.log( $('#p-info').css('display'))
        info_submit()
      }
       if($(e.target).is($('.icon-guanbi1'))){
             
         $(e.target).siblings('input').val('')
        
        }

        if($(e.target).is($('#agreement'))){
            
            $('#p-info .weui-btn').toggleClass('weui-btn_disabled')
      }

      })

      <?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['modal'], \yii\web\View::POS_END); ?>  