<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com 
 * Time:2019年6月12日上午9:52:11
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\components\job;
use yii;
use yii\base\BaseObject;
use yii\helpers\Json;
use common\logic\OrderLogic;
use common\models\Order;
class JobOrder extends BaseObject implements \yii\queue\JobInterface{ 
    public $id;   
    public $m_id;
    public function execute($queue){
        $this->cancel($this->id,$this->m_id);
     
    }
    /**
               * 订单超时，自动作废
     * @param int $id
     */
    public function cancel($id,$m_id){
        $data = Order::find()->where(['status'=>1,'payment_status'=>0,'id'=>$id])->one();
        if(!empty($data)){
            $logic=new OrderLogic();
            $result=$logic->sys_cancel_order($id,$m_id);
            return Json::encode($result);
        }
    }

    public function getDelay(){
        
        $Order = Order::findOne($this->id);
        $now = time();
        $delay = $Order->create_time + 7200 - $now;
        return $delay>0? $delay : 1 ;
        
    }
}
