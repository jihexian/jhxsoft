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
class JobOrderConfirm extends BaseObject implements \yii\queue\JobInterface{
    public $id;
    public $m_id;
    public function execute($queue){
  
        $this->confirm($this->id,$this->m_id);
    }

    /**
     * 发货后超过7天，自动收货
     * @param int $id
     * @param int $m_id
     * @return string
     */
    public function confirm($id,$m_id){
        $data = Order::find()->where(['status'=>3,'id'=>$id])->one();
        if(!empty($data)){
            $logic=new OrderLogic();
            $result=$logic->confirm($id,$m_id);
            return Json::encode($result);
        }
    }
    public function getDelay(){
        
        $Order = Order::findOne($this->id);
        $now = time();
        $delay = $Order->sendtime +7*24*3600 - $now;
        return $delay>0? $delay : 1 ;
        
    }
}
