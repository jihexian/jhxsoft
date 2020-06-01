<?php
namespace common\logic;


use common\helpers\Tools;
use common\models\Hongbao;
use common\models\Member;
use Yii;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\helpers\Json;
use common\components\job\JobHongbao;

class HongbaoLogic
{
    /**
     * 红包
     * @param  $mid
     * @param  $params
     */
    public function createHongbao($mid,$params){
        $tx = Yii::$app->db->beginTransaction();
        try {
            if ($params['type']==1) {
                $hongbaoMoney = $params['money']*$params['send_num'];
            }else{
                $hongbaoMoney = $params['money'];
            }
            $hongbao = new Hongbao();
            $hongbao->loadDefaultValues();
            $hongbao->mid = $mid;
            $hongbao->type = $params['type'];
            $hongbao->money = $params['money'];
            $hongbao->rest_money = $hongbaoMoney;  
            $hongbao->sum_money = $hongbaoMoney; 
            $hongbao->code = Tools::get_order_no();
            $hongbao->password = Tools::get_order_no();
            $hongbao->send_num = $params['send_num'];            
            if (!$hongbao->save()) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>current($hongbao->getFirstErrors())];
            }
            $member = Member::findOne($mid);
            $userMoney = $member['user_money'];
            if ($userMoney<$hongbaoMoney) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'余额不足！'];
            }
            $accountLogic = new AccountLogic();
            $info = array();
            $info['hongbao']['id'] = $hongbao->id;
            $info['hongbao']['username'] = $member->username;            
            $info = Json::encode($info);
            $changeParams = array();
            $changeParams['money'] = -$hongbaoMoney;
            $result = $accountLogic->changeAccount($mid, $changeParams, 9,$info,'支付红包');
            if ($result['status']!=1) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>$result['msg']];
            }
            $jobHongbao = new JobHongbao();
            $jobHongbao->id = $hongbao['id'];
            Yii::$app->queue->delay($jobHongbao->getDelay())->push($jobHongbao);
            $tx->commit();           
            $encryptCode = base64_encode(Yii::$app->security->encryptByPassword($hongbao->password, $hongbao->code));
            
            return ['status'=>1,'msg'=>'发送成功','data'=>['encryptCode'=>$encryptCode,'id'=>$hongbao->id]];
        } catch (StaleObjectException $e){
            $tx->rollBack();
            return ['status'=>0,'msg'=>'系统错误，请重试！'];
        }catch (Exception $e) {
            $tx->rollBack();
            return ['status'=>0,'msg'=>'系统错误！'];
        }
    }
    /**
     * 领取红包
     * 
     */
    public function receive($mid,$params=null){
        
        $tx = Yii::$app->db->beginTransaction();
        try {
            $hongbao = Hongbao::findOne($params['id']);
            $sender = Member::findOne($hongbao->mid);
            if (empty($hongbao)) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'参数错误！'];
            }
            $a = $params['encryptCode'];
            $password = Yii::$app->security->decryptByPassword(base64_decode($a),$hongbao->code);
            if ($hongbao->password!=$password) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'参数错误！'];
            }

            $array = $hongbao->toArray();
            unset($array['code']);
            unset($array['password']);
            if ($hongbao->status==0) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'该红包已过期！','data'=>$array];
            }
            if ($hongbao->status==2) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'该红包已被领完！','data'=>$array];
            }
            if ($hongbao->rest_money<=0) {
                $tx->rollBack();
                return ['status'=>2,'msg'=>'该红包已被领完！','data'=>$array];
            }
            //判断是否领过红包
            if (isset($hongbao->info) && !empty($hongbao->info)) {
                $info = $hongbao->info;
                $info = Json::decode($hongbao->info);
                foreach ($info as $value) {
                    if ($value['mid']==$mid) {
                        $tx->rollBack();
                        return ['status'=>1,'msg'=>'你已经领过该红包了！','data'=>$array];
                    };
                }
            }
            
            
            $accountLogic = new AccountLogic();
            $now = time();
            if ($hongbao->created_at + 86400 < $now  && $hongbao->status != 0) {
                //处理红包回退逻辑
                $money = $hongbao['rest_money'];
                $info = array();
                $info['hongbao']['id'] = $hongbao->id;
                $info['hongbao']['username'] = $sender->getAttribute("username");
                $info = Json::encode($info);
                $changeParams = array();
                $changeParams['money'] = $money;
                $resultAccountLogic = $accountLogic->changeAccount($hongbao->mid, $changeParams, 9,$info,'红包退回');
                //accountlog记录
                if ($resultAccountLogic['status']!=1) {
                    $tx->rollBack();
                    return ['status'=>0,'msg'=>'系统错误，请重试！'];
                }
                //修改红包：rest_money和info
                $hongbao->rest_money = 0;
                $hongbao->status=0;
           
                $info = array();
                if (isset($hongbao->info)) {
                    $info = json_decode($hongbao->info);
                }
                $currentInfo = array();
                $currentInfo['mid'] = $hongbao->mid;
                $currentInfo['username'] = $sender->getAttribute('username');
                $currentInfo['type']= 2;
                $currentInfo['money'] = $money;
                $currentInfo['created_at'] = time();
                array_push($info, $currentInfo);
                $info = Json::encode($info);
                $hongbao->info = $info;
                if (!$hongbao->save()) {
                    $tx->rollBack();
                    return ['status'=>0,'msg'=>current($hongbao->getFirstErrors())];
                }    
                
            }else{
                
                
                //领取红包逻辑
               if ($hongbao['type']==1) {
                   $money = $hongbao['money'];                   
                   $recevier = Member::findOne($mid);
                   $info = array();
                   $info = array();
                   $info['hongbao']['id'] = $hongbao->id;
                   $info['hongbao']['username'] = $recevier->getAttribute("username");
                   $info = Json::encode($info);
                   $changeParams = array();
                   $changeParams['money'] = $money;   
                   $resultAccountLogic = $accountLogic->changeAccount($mid, $changeParams, 9,$info,'领取红包');
                   //accountlog记录
                   if ($resultAccountLogic['status']!=1) {
                       $tx->rollBack();
                       return ['status'=>0,'msg'=>'系统错误，请重试！'];
                   }
                   //修改红包：rest_money和info
                   $hongbao->rest_money -= $money;
                   if ($hongbao->rest_money==0) {
                       $hongbao->status=2;
                   }
                   $info = array();
                   if (isset($hongbao->info) && !empty($hongbao->info)) {
                       $info = json_decode($hongbao->info);
                   }
                   $currentInfo = array();
                   $currentInfo['mid'] = $mid;
                   $currentInfo['type']= 1;
                   $currentInfo['money'] = $money;
                   $infoMember = $recevier->getAttributes(['username','avatarUrl']);
                   $currentInfo['username'] = $infoMember['username'];
                   $currentInfo['avatarUrl'] = $infoMember['avatarUrl'];
                   $currentInfo['created_at'] = time();
                   array_push($info, $currentInfo);
                   $info = Json::encode($info);
                   $hongbao->info = $info;
                   if (!$hongbao->save()) {
                       $tx->rollBack();
                       return ['status'=>0,'msg'=>current($hongbao->getFirstErrors())];
                   }                   
               }               
            }
            $tx->commit();
            $array = $hongbao->toArray();
            unset($array['code']);
            unset($array['password']);
            return ['status'=>1,'msg'=>'领取成功！','data'=>$array];
        } catch (StaleObjectException $e){
            $tx->rollBack();
            return ['status'=>0,'msg'=>'系统错误，请重试！'];
        }catch (Exception $e) {
            $tx->rollBack();
            return ['status'=>0,'msg'=>'系统错误，请重试！'];
        }
    }
    /**
     * 红包详情
     */
    public function detail($mid,$params=null){
      $hongbao = Hongbao::findOne($params['id']);
      if(!$hongbao){
        return ['status'=>0,'msg'=>'参数错误！'];
      }
      if($hongbao->mid != $mid){
         return ['status'=>0,'msg'=>'权限错误！'];
      }
      
      $array = $hongbao->toArray();
      $encryptCode = base64_encode(Yii::$app->security->encryptByPassword($hongbao->password, $hongbao->code));
      $array['code'] = $encryptCode;
      unset($array['password']);
      $now = time();
      if ($hongbao->status == 0 || ($hongbao->created_at + 86400<$now)) {
        return ['status'=>2,'msg'=>'该红包已过期！','data'=>$array];
      }
      return ['status'=>1,'msg'=>'读取成功','data'=>$array];
    }
    /**
     * 红包退回
     */
    public function back($mid,$params=null){
        $items = Hongbao::findAll(['mid'=>$mid]);
        $accountLogic = new AccountLogic();
        $now = time();
        $tx = Yii::$app->db->beginTransaction();
        foreach ($items as $key => $hongbao) {
          $expiresTime = $hongbao->created_at + 86400;
          if ($expiresTime < $now && $hongbao->status != 0 && $hongbao['rest_money'] > 0) {
            $sender = Member::findOne($hongbao->mid);
            //处理红包回退逻辑
            $money = $hongbao['rest_money'];
            $info = array();
            $info['hongbao']['id'] = $hongbao->id;
            $info['hongbao']['username'] = $sender->getAttribute("username");
            $info = Json::encode($info);
            $changeParams = array();
            $changeParams['money'] = $money;
            $resultAccountLogic = $accountLogic->changeAccount($hongbao->mid, $changeParams, 10,$info,'红包退回','',$expiresTime);

            //accountlog记录
            if ($resultAccountLogic['status']!=1) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'系统错误，请重试！'];
            }
            //修改红包：rest_money和info
            $hongbao->rest_money = 0;
            $hongbao->status=0;
       
            $info = array();
            if (isset($hongbao->info) && !empty($hongbao->info)) {
                $info = json_decode($hongbao->info);
            }
            $currentInfo = array();
            $currentInfo['mid'] = $hongbao->mid;
            $currentInfo['username'] = $sender->getAttribute('username');
            $currentInfo['type']= 2;
            $currentInfo['money'] = $money;
            $currentInfo['created_at'] = $expiresTime;
            array_push($info, $currentInfo);
            $info = Json::encode($info);
            $hongbao->info = $info;

            if (!$hongbao->save()) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>current($hongbao->getFirstErrors())];
            }else{
                 return ['status'=>1,'msg'=>'过期的红包已退还'];
            }
            $tx->commit();  
          }
      } 
    }
    
}

