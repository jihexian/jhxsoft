<?php

namespace common\logic;
use yii;
use yii\db\StaleObjectException;
use common\models\Shop;
use common\models\ShopAccoutLog;
use common\models\ShopCommissionLog;
use function GuzzleHttp\json_encode;
use common\models\Order;
use common\models\OrderArrive;
use common\models\VillageCommissionLog;
class ShopCommissionLogic{
    /**
     * 
     * @param int $type 0:正常订单 1：到店支付
     * @param int $id 订单id
     * @param int $m_id 用户id
     * @return number[]|string[]
     */
    public function Log($type,$id,$m_id){
        if($type==0){
            $order=Order::findOne(['id'=>$id,'m_id'=>$m_id]);
            $sale_money=bcsub($order['pay_amount'],$order['delivery_price_real'],2);
        }elseif($type==1){
            $order=OrderArrive::findOne(['id'=>$id,'m_id'=>$m_id]);
            $sale_money=$order['pay_amount'];
        }    
        $t = Yii::$app->db->beginTransaction();
        try {
           
            if(empty($order)){
                $t->rollBack();
                return ['status'=>0,'msg'=>'失败'];
            }
    
            //平台手续费
            $percentage=$this->percent($order,$sale_money);
            //获取订单分销活动店家需要支付的分销总金额
            $distribute_money=$this->get_distribute_money($order['order_no']);   
            if($order['shop']['is_village']==1){
                $fupin=bcmul($sale_money,$order['shop']['percent'],2);
            }else{
                $fupin=0;
            }
            //实际收入
            $price1=bcsub($order['pay_amount'],$percentage,2 );
            $price2=bcsub($price1,$distribute_money,2);
            $money=bcsub($price2,$fupin,2);
            //店铺余额改变
            $shop=Shop::findOne(['id'=>$order['shop_id']]);
            $shop->setScenario('edit');
            $shop->money=bcadd($shop->money,$money,2);
            $shop->save();
            if($shop->hasErrors()){
                $t->rollBack();
                return ['status'=>0,'msg'=>current($shop->getFirstErrors())];
            }
            //平台收取服务费
            $model=new ShopCommissionLog();
            $model->order_no=$order['order_no'];
            $model->m_id=$m_id;
            $model->type=$type;
            $model->shop_id=$order['shop_id'];
            $model->money=$money;
            $model->percentage=$percentage;
            $model->save();
            if($model->hasErrors()){
                $t->rollBack();
                return ['status'=>0,'msg'=>current($model->getFirstErrors())];
            }
            //店铺收入流水
            $sa= new ShopAccoutLog();
            $sa->shop_id= $model->shop_id;
            $sa->type=$type==0?1:2; //1为订单消费 2为到店支付
            $sa->change_money=$money;
            $sa->money=$order['shop']['money'];
            $sa->pay_amount=$order->pay_amount;
            $sa->order_no=$order['order_no'];
            $distribute_money==0?$message='订单总金额为'.$order['pay_amount'].'元,平台服务费为：'.$percentage.'元':'订单总金额为'.$order['pay_amount'].'元,平台服务费为：'.$percentage.'元, 分销佣金：'.$distribute_money.'元';
            $sa->comment=$message;
            $sa->loadDefaultValues();
            $sa->save();
            if($shop->hasErrors()||$sa->hasErrors()){
                $t->rollBack();
                return ['status'=>0,'msg'=>'失败'];
            }

            //扶贫资金生成
            if($order['shop']['is_village']==1){
                $village=new VillageCommissionLog();
                $village->order_no=$order['order_no'];
                $village->type=$type;
                $village->m_id=$m_id;
                $village->shop_id=$order['shop_id'];
                $village->money=$sale_money;
                $village->percentage=$fupin;
                $village->village_id=$order['shop']['village_id'];
                $village->save();
                if($village->hasErrors()){
                    $t->rollBack();
                    return ['status'=>0,'msg'=>'失败'];
                }
            }
            
            $t->commit();
            return ['status'=>1,'msg'=>'成功'];
        }catch (StaleObjectException $e) {
            $t->rollBack();
            return ['status'=>0,'msg'=>'请勿频繁操作'];
        }catch (\Exception $e) {
           
            $t->rollBack();
            return ['status'=>0,'msg'=>'失败'];
        }
    }
   
    /**
     * 行业提成
     */
    public function percent($order,$sale_money){

        $data=Shop::findOne($order['shop_id']);
        if($data['category']['percent']!=0){
            $percentage=bcmul($sale_money,$data['category']['percent'],2);
        }

        return $percentage;
        
    }
    
    /**
     * desc 获取某个订单的分销总金额
     * @param int $order_no
     */
    public function get_distribute_money($order_no){
        $data=\common\models\DistributLog::find()->where(['order_no'=>$order_no])->all();
        $money=0;
        if(is_array($data)){
            foreach ($data as $vo){
                $money+=$vo['change_money'];
            }
        }
        return bcadd($money,0,2);
        
    }
    
   
}