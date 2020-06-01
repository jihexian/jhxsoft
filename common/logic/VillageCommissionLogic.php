<?php

namespace common\logic;
use common\models\Shop;
use common\models\VillageCommissionLog;
class VillageCommissionLogic{


/**
 * 完成订单生成log记录
 */
    public function Log($order,$m_id){
        $model=new VillageCommissionLog();
        $model->order_no=$order['order_no'];
        $model->m_id=$m_id;
        $model->shop_id=$order['shop_id'];
        $model->money=$order['pay_amount']-$order['delivery_price_real'];
        $data=$this->percent($order);
        $model->percentage=($order['pay_amount']-$order['delivery_price_real'])*$order['shop']['percent'];
        $model->village_id=$data['village_id'];
        if($model->save()){
            return ['status'=>1,'msg'=>$model->percentage];
        }
        throw new \Exception('操作失败');
    }
    
    /**
     * 提成
     */
    public function percent($order){
        $data=Shop::find()
            ->where(['id'=>$order['shop_id']])
            ->one();
        $percentage=($order['pay_amount']-$order['delivery_price_real'])*$data['percent'];
        return [
                'percentage'=>$percentage,
                'village_id'=>$data['village_id'],
        ];
        
    }
}