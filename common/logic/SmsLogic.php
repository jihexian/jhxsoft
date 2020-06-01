<?php
/**
 * 
 * Author vamper 944969253@qq.com
 * Time:2018年12月06日 下午4:03:39
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\logic;

use common\models\SmsLog;
use common\components\alisms\BaseSms;
use Yii;

class SmsLogic{
    //private $baseSms;
    
//     public function __construct(){
//         $baseSms = new BaseSms();
//     }
    
    public function sendSms($mobile,$templateCode,$templateParam,$signName,$scene){
        if (empty($mobile)) {
            return ['status'=>0,'msg'=>'手机号不能为空'];
        }
        if (empty($scene)) {
            return ['status'=>0,'msg'=>'场景值不能为空'];
        }
        $sms = SmsLog::find()->where(['mobile'=>$mobile,'scene'=>$scene])->one();
        $now = time();
        if (!empty($sms)&&$sms['created_at']>$now-60) {
            return ['status'=>0,'msg'=>'请勿频繁操作！'];
        }
        $baseSms =  new BaseSms();
        //$result = $baseSms->sendSms($mobile, $templateCode,'阿里云短信测试专用', $templateParam);
        $result = $baseSms->sendSms($mobile, $templateCode,$signName, $templateParam);
        if ($result['status']==0) {
            return $result;
        }        
        
        $code = $templateParam['code'];
        if(empty($sms)){
            $sms = new SmsLog();
            $sms->mobile = $mobile;
        }
        $sms->scene = $scene;
        $sms->code = $code;
        $sms->created_at = time();
        $sms->status=1;        
        if ($result['status']==0) {
            $sms->status=0;
            $sms->error_msg = $result['msg'];
        }else{
            $sms->status=1;
        }
        if (!$sms->save()) {         
          $result['status']=0;
          $result['msg'] = current($sms->getErrors());
        }
        return $result;
    }
    /**
     * 
     * @param  $mid
     * @param  $mobile
     * @param  $code
     * @param  $scene
     */
    public function validateSms($mobile,$code,$scene){
        $sms = SmsLog::find()->where(['mobile'=>$mobile,'scene'=>$scene,'code'=>$code])->one();
        if (empty($sms)) {
            return['status'=>0,'msg'=>'验证码错误，请重新输入！'];
        }else{
           if ($sms->created_at>time()+60*10*1000) {
                return['status'=>0,'msg'=>'验证码过期，请重新获取！'];
            } 
        }
        return ['status'=>1,'msg'=>'验证成功！'];
    }
    /**
     * 根据模板类型设置模板参数
     * @param  $type
     * @param  $data
     */
    public function getTempParams($type,$data){
        $templateParam = array();  
        switch ($type) {
            case 1://验证
                $templateParam['code'] = $data['code'];    
                break;
            case 2://发货
                $templateParam['phone'] = $data['phone'];
                $templateParam['consignee'] = $data['consignee'];
                break;
            case 3://下单
                $templateParam['username'] = $data['username'];
                $templateParam['order_sn'] = $data['order_sn'];
                $templateParam['consignee'] = $data['consignee'];
                break;
            case 4://审核店铺
                $templateParam['name'] = $data['name'];
                $templateParam['code'] = $data['code'];
                break;
            default:
                      
                break;
        }
        return $templateParam;
    }
}
