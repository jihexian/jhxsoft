<?php
/**
 * 
 * 
 * Author wsyone wsyone@faxmail.com 
 * Time:2019年6月10日下午5:38:38
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\logic;
use Yii;
use common\models\Plugin;
use common\models\ShopPay;
use common\models\Withdrawal;
use common\logic\AccountLogic;
use common\models\Member;
use yii\db\StaleObjectException;
use yii\helpers\Json;
use common\helpers\Tools;
class WithdrawalLogic {
    /**
     * 
     * @param float $money
     * @param float $user_money
     * @return number[]|string[]
     */
    public function apply($money,$mid,$client='wxMini',$type=0,$data=array()){
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $member=Member::findOne($mid);
            if(($type==0&&$member['user_money']<$money)||($type==1&&$member['distribut_money']<$money)){
                return ['status' => 0,'msg' => '余额不足'];
            }
            //新增提现记录
            $withdrawal=new Withdrawal();
            if($client=='wxMini'&&$member['xcx_openid']){
                $payment=Plugin::find()->where(['id'=>$client,'status'=>1])->one();
                if(empty($payment)){
                    return [ 'status' => 0, 'msg' => '请先到后台插件控制台开启微信小程序支付功能'];
                }
               $withdrawal->bank_card=$member['xcx_openid'];
               $withdrawal->bank_name=$payment['name'];
               $withdrawal->realname=$member['username'];
               $withdrawal->payment_code=$payment['id'];  
            }elseif($client=='weixin'&&$member['wx_openid']){
                $payment=Plugin::find()->where(['id'=>$client,'status'=>1])->one();
                if(empty($payment)){
                    return ['status' => 0,'msg' => '请先到后台插件控制台开启微信小程序支付功能' ];
                }
                $withdrawal->realname=$member['username'];
                $withdrawal->bank_card=$member['wx_openid'];
                $withdrawal->bank_name=$payment['name'];
                $withdrawal->payment_code=$payment['id']; 
            }else{
                $withdrawal->bank_card=$data['bank_card'];
                $withdrawal->bank_name=$data['bank_name'];
                $withdrawal->realname=$data['realname'];
            }
            $withdrawal->pay_amount=$money;
            $withdrawal->status=0;
            $withdrawal->order_no=Tools::get_order_no();
            $withdrawal->m_id=$mid;
            $withdrawal->type=$type;
            $withdrawal->scenario='create';
            $withdrawal->loadDefaultValues();
            $withdrawal->save();
            if($withdrawal->hasErrors()){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>current($withdrawal->getErrors())[0]];
            }
            //修改用户余额
            $account=new AccountLogic();
            $changeParams=array();
            if($type==0){
                $changeParams['money']=-$money;
                $desc='用户提现';
            }elseif($type==1){
                $changeParams['distribut_money']=-$money;
                $desc='分销提现';
            }
         
            $info=array();
            $info['withdrawal'][0]=$withdrawal->id;
            $info=Json::encode($info);
            $data=$account->changeAccount($mid, $changeParams, 8,$info,$desc);
            if($data['status']!=1){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>$data['msg']];
            }
            $transaction->commit();
            return ['status'=>1,'msg'=>'成功']; 
      
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
        } 
    }
    
    /**
     * @desc 使用微信或支付宝接口付款给客户
     * @param int $with_id
     */
    public function apiTranfer($id,$remark){
        $transaction=yii::$app->db->beginTransaction();
        try {
           $data=Withdrawal::findOne($id);
           if($data->status!=0){
               $transaction->rollBack();
               return ['status'=>0,'msg'=>'订单已经处理过'];
           }
          switch($data['payment_code']){
               case 'weixin':      $payment=new \plugins\weixin\Weixin();break;
               case 'wxMini':      $payment=new \plugins\wxMini\WxMini();break;
               case 'alipayMobile':$payment=new \plugins\alipayMobile\AlipayMoblie();break;
               default:break;
           }
           
           if($data['payment_code']=='weixin'||$data['payment_code']=='wxMini'){ //微信支付
               $weixin=array(
                       'amount'=>$data->pay_amount,
                       'order_no'=>$data->order_no,
                       'openid'=>$data->bank_card,
                       'desc'=>'用户提现'.$data->pay_amount.'元',
               );
               $flag=$payment->transfer($weixin);
               if($flag['status']!=1){
                   $transaction->rollBack();
                   return ['status'=>0,'msg'=>$flag['msg']];
               }  
             
            
           }elseif($data['payment_code']=='alipayMobile'){                 //余额支付
               $alipay=array();
             //  $flag=$payment->payment_refund($alipay);
                     
           } 
           $data->scenario='update';
           $data->status=1;
           $data->remark=$remark;
   
           $data->pay_time=$flag['payment_time'];
           $data->transaction_id=$flag['payment_no'];
           $data->save();
           if($data->hasErrors()){
               $transaction->rollBack();
               return ['status'=>0,'msg'=>current($data->getErrors())[0]];
           }
           $transaction->commit();
           return ['status'=>1,'msg'=>'操作成功'];
        }catch(StaleObjectException $e){
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'请不要频繁操作'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>$e->getMessage()];
            
        }
        
    }

    public function personTranfer($id,$remark){
        $transaction=yii::$app->db->beginTransaction();
        try {
            $data=Withdrawal::findOne($id);  
            if($data->status!=0){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'订单已经处理过'];
            }
            $data->scenario='update';
            $data->status=1;
            $data->pay_time=time();
            $data->remark=$remark;
            $data->save();
            if($data->hasErrors()){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>current($data->getErrors())[0]];
            }
            $transaction->commit();
            return ['status'=>1,'msg'=>'操作成功'];
        }catch(StaleObjectException $e){
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'请不要频繁操作'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
            
        }
    }
    /**
     * @desc拒绝提现
     * @param int $id
     * @return number[]|mixed[]|number[]|string[]
     */
    public function refuse($id,$remark){
        $transaction=yii::$app->db->beginTransaction();
        try {
            $data=Withdrawal::findOne($id);
            if($data->status!=0){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'订单已经处理过'];
            }
            $data->status=2;
            $data->remark=$remark;
            $data->save();
            if($data->hasErrors()){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>current($data->getErrors())[0]];
            }
            
            //修改用户余额
            $account=new AccountLogic();
            $changeParams=array();
            if($data['type']==0){  
                $changeParams['money']=$data->pay_amount;
            }else{
                $changeParams['distribut_money']=$data->pay_amount;
            }
          
            $info=array();
            $info['withdrawal'][0]=$id;
            $info=Json::encode($info);
            $data=$account->changeAccount($data->m_id, $changeParams, 8,$info,'提现不通过退回');
            if($data['status']!=1){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            }
           $transaction->commit();
           return ['status'=>1,'msg'=>'操作成功'];
         }catch(StaleObjectException $e){
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'请不要重复操作'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
            
        }
        
    }
}
