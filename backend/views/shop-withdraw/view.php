<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Withdrawal */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Withdrawals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

   <section class="content">
                <div class="box box-primary">
    <div class="box-body">
    <table id="w0" class="table table-striped table-bordered detail-view"><tbody><tr><th>ID</th><td><?=$model->id?></td></tr>

        <tr><th>用户</th><td><?=$model['member']['username']?></td></tr>
        <tr><th>提款账号真实姓名</th><td><?=$model->name?></td></tr>
        <tr><th>提现金额</th><td><?=$model->money?></td></tr>
        <tr><th>银行名称</th><td><?=$model->bank?></td></tr>
        <tr><th>银行账号</th><td><?=$model->account?></td></tr>
        <tr><th>申请时间</th><td><?php echo date('Y-m-d H:i:s',$model->created_at);?></td></tr>
          <?php if($model['transaction_id']):?>
        <tr><th>付款对账流水号</th><td><?=$model->transaction_id?></td></tr>   
        <tr><th>打款时间</th><td><?=$model->pay_time?></td></tr>   
        <?php endif;?>
        <?php if($model->status==0):?> 
            <tr><th>操作备注</th><td>
            <textarea name="remark" id="remark" style="width:100%;height:80px;"><?=$model->mark?></textarea>
            </td></tr>
             <tr><td>
             <?php if(empty($model->payment_code)):?> 
             <p class="btn btn-primary btn-flat" data-status="tranfer"  class="fahuo">完成转账</p> 
             <?php else:?>     
             <p class="btn btn-primary btn-flat" data-status="auto" class="fahuo">通过申请</p>
             <?php endif;?>
              <p class="btn btn-primary btn-danger" data-status="refuse" class="fahuo">拒绝申请</p>
            </td></tr>     
          <?php else:?> 
             <tr><th>操作备注</th><td><?=$model->mark?></td></tr>
             <tr><th>状态</th><td><?=$model->renderStatus($model->status)?></td></tr>
         <?php endif;?>
           
        <?php if(empty($model->payment_code)):?>        
                <tr><th>提现流程：</th><td>
        <br/>
        1:用户前台申请提现<br/>
        2:财务转账给用户 <br/>
        3:填写备注（转账时间、转账人员、转账回执单号等） <br/>
        4:点击完成'完成转账'按钮<br/>
        </th></tr>
        <?php else:?>
         <tr><th>提现流程：</th><td>
        1:用户前台申请提现<br/>
        2:管理员通过申请，系统调用微信或支付宝接口打款给客户<br/>
        3：程序判断是否打款成功，返回最终结果
        </td></tr>
        <?php endif;?>
        </tbody>
        </table>  
          </div>
        </div>
    </section>
    
    
<?php
$id=$model['id'];
$this->registerJs(<<<JS
$(function(){
    $('.btn').click(function(e){
            var id = $id;
            console.log(id);
            var remark = $("#remark").val();
            var status =   $(e.target).attr("data-status");
            if(remark==''){
              alert('操作备注不能为空');
          }else{
         $.ajax({
             type: "post",
             url: "/admin/shop-withdraw/api",
             data: {id:id,remark:remark,status:status},
             dataType: "json",
             success: function(data){
                alert(data.msg);
             setTimeout(function (){
               window.location.reload();
                }, 3000)
                          
            }
         });}
    });
});
JS
);
?>
