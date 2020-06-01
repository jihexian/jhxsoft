<?php
/**
 * 
 * Author vamper 944969253@qq.com
 * Time:2018年11月30日 下午4:03:39
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\logic;
use yii;
use yii\base\Exception;
use yii\db\StaleObjectException;
use common\models\AccountLog;
use api\modules\v1\models\Member;
class AccountLogic{


  	/**
   	 *
   	 * @param int $mid 用户id
 
   	 * @param int $changeParams 变动积分、余额，正为增加，负为减少
   	 * @param int $type 1:订单消费，2充值，3，活动赠送 4，管理员操作，5到店支付，6分销提成、7订单退回、8提现9红包
 
   	 * @param int $info 对应的info Json数据
   	 * @param string $desc 备注
   	 * @param int $uid 若type=4需填写管理员uid
   	 * @return array
   	 */
	public function changeAccount($mid,$changeParams,$type,$info=null,$desc=null,$uid=null) {
	    isset($changeParams['score'])? $changeScore = $changeParams['score'] : $changeScore = null;
	    isset($changeParams['money'])? $changeMoney = $changeParams['money'] : $changeMoney = null;
	    isset($changeParams['distribut_money'])? $changeDistribut = $changeParams['distribut_money'] : $changeDistribut = null;
	    if ($changeScore===0){
	        return ['status'=>0,'msg'=>'变动积分必须不为0'];
	    }
	    if ($changeMoney===0){
	        return ['status'=>0,'msg'=>'变动金额必须不为0'];
	    } 
	    if($changeDistribut===0){
	        return ['status'=>0,'msg'=>'变动金额必须不为0'];
	    }
	  
	    //保存记录，并增加积分
	    $transaction = Yii::$app->db->beginTransaction();
	    try {
	        $member = Member::findOne($mid);
	        $accountLog = new AccountLog();
	        $accountLog->member_id = $mid;
	        if (isset($changeMoney)) {	
	            $oldMoney = $member->user_money;
	            if ($changeMoney<0&&$oldMoney<-$changeMoney){
	                $transaction->rollBack();
	                return ['status'=>0,'msg'=>'余额不足'];
	            }
	            $member->user_money = $member->user_money + $changeMoney;
	            $accountLog->money = $oldMoney;
	            $accountLog->change_money = $changeMoney;
	        }
	        
	        if (isset($changeScore)) {	
	            $oldScore = $member->score;
	            if ($changeScore<0&&$oldScore<-$changeScore){
	                $transaction->rollBack();
	                return ['status'=>0,'msg'=>'积分不足'];
	            }
	            $member->score = $member->score + $changeScore;
	            $accountLog->score = $oldScore;
	            $accountLog->change_score = $changeScore;
	        }	
	        
	        if (isset($changeDistribut)) {
	            $oldData = $member->distribut_money;
	            if ($changeDistribut<0&&$oldData<-$changeDistribut){
	                $transaction->rollBack();
	                return ['status'=>0,'msg'=>'余额不足'];
	            }
	            $member->distribut_money +=$changeDistribut;
	            $accountLog->money = $oldData;
	            $accountLog->change_money = $changeDistribut;
	        }
	        
	        
	        $member->save();	  
	        if($member->hasErrors()){
	            $transaction->rollBack();
	            return ['status'=>0,'msg'=>current($member->getFirstErrors())];
	        }
	        $accountLog->type = $type;
	        if (empty($desc)) {
	            switch($type){
	                case 1:
	                    $accountLog->desc = '线上订单消费';break;
	                case 2:
	                    $accountLog->desc = '充值';break;
	                case 3:
	                    $accountLog->desc = '活动赠送';break;
	                case 4:
	                    $accountLog->desc = '管理员操作';break;
	                case 5:
	                    $accountLog->desc = '到店支付';break;
	                case 6:
	                    $accountLog->desc = '分销提成';break;
	                case 7:
	                    $accountLog->desc = '订单退回';break;
	                case 8: 
	                    $accountLog->desc = '用户提现';break;
	                case 9:
	                    $accountLog->desc = '红包';break;
	                case 10:
	                    $accountLog->desc = '红包退回';break;
	                case 11:
	                    $accountLog->desc = '提现退回';break;
	                default:$accountLog->desc = '';break;
	            }
	        }else{
	            $accountLog->desc = $desc;
	        }
	        $accountLog->info = $info;
	       $accountLog->save();
	       if($accountLog->hasErrors()){
	           $transaction->rollBack();
	           return ['status'=>0,'msg'=>current($accountLog->getFirstErrors())];
	       }
	       $transaction->commit();
	       return ['status'=>1,'msg'=>'变更成功','data'=>$accountLog];
	        
	    }catch (StaleObjectException $e) {
	        // 解决冲突的代码
	        $transaction->rollBack();
	        return ['status'=>0,'msg'=>'变更失败'];
	    }catch (Exception $e) {
	        yii::error($e->getMessage());
	        $transaction->rollBack();
	        return ['status'=>0,'msg'=>'变更失败1'];
	    }
	}
}
