<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com 
 * Time:2019年5月23日下午6:29:56
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\logic;
use common\models\OrderRefundDoc;
use Yii;
use yii\base\Exception;
use yii\db\StaleObjectException;
use common\models\Member;
use common\models\OrderSku;
use common\models\PlatformCouponLog;
use common\models\Service;
use common\models\Order;
use common\modules\coupon\models\CouponItem;
use common\helpers\Tools;
use common\logic\DistributeLogic;
class OrderRefundLogic{
    //整单退款逻辑接口
    public function refund($id,$post){
        $refund=OrderRefundDoc::findOne($id);
        $order=Order::findOne($refund['order_id']);
        //兼容多店铺订单在微信支付商户号里面的订单金额
        $pay_amount=Order::find()->where(['parent_sn'=>$order['parent_sn']])->sum('pay_amount');
        $transaction = Yii::$app->db->beginTransaction();
        try {  
            //平台优惠券金额
            $platform=PlatformCouponLog::find()->where(['or',['third_no'=>$order['order_no']],['third_no'=>$order['parent_sn']]])->andWhere(['status'=>1])->one();
            $coupon_money=0;
            if($platform){
                $coupon_money=$platform['money'];
                $platform->status=2;
                $platform->save();
                if($platform->hasErrors()){
                    $transaction->rollBack();
                    return ['status' => 0, 'msg' => '操作失败'];
                }
            }
            //获取订单在微信支付商户号的真实支付金额
            $max_money=bcsub($pay_amount,$coupon_money,2);
            //子订单最大退款金额
            $max_payment=bcsub($order['pay_amount'],$coupon_money,2);
            if($max_payment<0.01){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '该订单暂时不支持退款'];
            }
            //退款金额不能大于子订单金额
            if($max_payment<$post['OrderRefundDoc']['amount']){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '退款金额不能超过订单支付金额'];
            }
            if($order['status']==6){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '已经退过款，请不要重复操作'];
            }
            //修改order表
            $order->status=6;
            $order->scenario='update';
            $order->save();    
            if($order->hasErrors()){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '操作失败'];
            }
            //返回积分、优惠券
            $logic=new OrderLogic();
            $m=$logic->refund_account($order['m_id'],$order);
            if($m['status']!=1){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '操作失败'];
            }
          
            $refund->load($post);
            $refund->status=2;//退款成功
            $out_refund_no=Tools::get_order_no();
            $refund->out_refund_no=$out_refund_no;
            $refund->scenario='update';
            $refund->loadDefaultValues();
            $refund->save();
            if($refund->hasErrors()){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '操作失败'];
            }
            
            foreach ($order->orderSku as $vo){
                $vo['is_refund']=2;
                if(!$vo->save()){
                    $transaction->rollBack();  
                    return ['status' => 0, 'msg' => '操作失败'];
                }
            }
            //作废三级分销在路上金额
            $distribut=new DistributeLogic();
            $dis=$distribut->Cancel($order['m_id'], $order);
            if($dis['status']!=1){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '操作失败'];
            }
            //选择退款形式，type==0为原路退返， 1为退回余额
            $payment=$this->refundPayment($order,$post['OrderRefundDoc']['amount'],$max_money,$post['OrderRefundDoc']['type'],$out_refund_no,$refund['note']);
            if($payment['status']!=1){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => $payment['msg']];
            } 
            $transaction->commit();
            return ['status' =>1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['status' => 0, 'msg' => '操作失败'];
        }
    }
    

    
    public function refundSku($id,$post,$uid){
        
        $transaction = Yii::$app->db->beginTransaction();
        try {  
            $service=Service::findOne($id);
            if($service['status']==1){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '请不要重复操作'];
            }
            $order=Order::findOne($service->order_id);
            $pay_amount=Order::find()->where(['parent_sn'=>$order['parent_sn']])->sum('pay_amount');
            $orderSku=OrderSku::findOne($service->sku_id);
            if($orderSku->is_refund==2){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '请不要重复操作'];
            }
            //判断是否大于最大退款金额
            $max_amount=$this->getMaxRefundAmount($order, $orderSku);
            if ($max_amount['status']!=1) {
                return $max_amount;
            }else{
                $max_amount = $max_amount['data'];
            }
            //平台优惠券金额
            $platform=PlatformCouponLog::find()->where(['or',['third_no'=>$order['order_no']],['third_no'=>$order['parent_sn']]])->andWhere(['status'=>1])->one();
            $coupon_money=0;
            if($platform){
                $coupon_money=$platform['money'];
                $platform->status=2;
                $platform->save();
                if($platform->hasErrors()){
                    $transaction->rollBack();
                    return ['status' => 0, 'msg' => '操作失败'];
                }
            }
            //获取多订单在微信支付商户号的真实支付金额
            $max_money=bcsub($pay_amount,$coupon_money,2);
            //最多可退金额
            $max_amount=bcsub(max_amount, $coupon_money,2);
            if($max_amount<0.01){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '不支持退款'];
            }
            if($max_amount<$post['Service']['amount']){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '退款金额不能超过'.$max_amount];
            }
        

            $orderSku->is_refund=2;
            $orderSku->save();
            if($orderSku->hasErrors()){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '操作失败'];
            }
            $count=OrderSku::find()->where(['order_id'=>$service['order_id'],'is_refund'=>1])->all();
            if(count($count)>0){
                $status=7;
            }else{
                $status=6;
            }
            //修改order表
            $order->status=$status;
            $order->scenario='update';
            $order->save();
            if($order->hasErrors()){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '操作失败'];
            }
            //返回积分
            $logic=new OrderLogic();
            $m=$logic->refund_sku_account($orderSku,$orderSku['num']);
            if($m['status']!=1){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '操作失败'];
            }
            $refund=new OrderRefundDoc();
            $refund->loadDefaultValues();
            $refund->m_id=$uid;
            $refund->note=$service->mark;
            $refund->sku_id=$service->sku_id;
            $refund->order_id=$service->order_id;
            $refund->type=$post['Service']['refund_type'];
            $refund->amount=$post['Service']['amount'];
            $refund->status=2;
            $out_refund_no=Tools::get_order_no();
            $refund->out_refund_no=$out_refund_no;
            $refund->message='用户退货退款';
            $refund->scenario='create';
            $refund->save();
            if($refund->hasErrors()){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '操作失败'];
            }
            //作废三级分销在路上金额
            $distribut=new DistributeLogic();
            $dis=$distribut->Cancel($order['m_id'], $order);
            if($dis['status']!=1){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '操作失败'];
            }
            $service->load($post);
            $service->status=1;
            $service->user_id=$uid;
            $service->save();
            if($service->hasErrors()){
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '操作失败'];
            }
            //选择退款形式，type==0为原路退返， 1为退回余额
            $payment=$this->refundPayment($order,$post['Service']['amount'],$max_money,$post['Service']['refund_type'],$out_refund_no,'退货');
            if($payment['status']!=1){
                $transaction->rollBack();
                return ['status' => 0, 'msg' =>$payment['msg']];
            }
            $transaction->commit();
            return ['status' => 1, 'msg' => '操作成功'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['status' => 0, 'msg' => '操作失败'];
        }
    }
    
    /**
     * 
     * @param array $order 子订单详情
     * @param number $amount 提现金额
     * @param int $type
     * @param string $note
     * @return number[]|string[]
     */
    public function refundPayment($order,$amount,$max_money,$type,$out_refund_no,$note=''){
    
        switch($order['payment_code']){
            case 'weixin':      $payment=new \plugins\weixin\Weixin();break;
           case 'wxMini':      $payment=new \plugins\wxMini\WxMini();break;
            case 'alipayMobile':$payment=new \plugins\alipayMobile\AlipayMoblie();break;
            case 'money':       $payment=new \plugins\money\Money();break;
            default:break;
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if($type==0){
                if($order['payment_code']=='weixin'||$order['payment_code']=='wxMini'){ //微信支付
                    $weixin=array();
                    $weixin['transaction_id']=$order['payment_no'];
                    $weixin['total_fee']=$max_money;
                    $weixin['refund_fee']=$amount;
                    $weixin['refund_desc']=$note;
                    $weixin['out_refund_no']=$out_refund_no;
                    $flag=$payment->payment_refund($weixin);
                    if($flag['return_code']=='FAIL'){
                        $transaction->rollBack();
                        return ['status'=>0,'msg'=>$flag['return_msg']];
                    }
                    if($flag['result_code']!='SUCCESS'||$flag['return_msg']!='OK'){
                        $transaction->rollBack();
                        return ['status'=>0,'msg'=>$flag['err_code_des']];
                    }  
                }elseif($order['payment_code']=='alipayMobile'){                 //余额支付
                    $alipay=array();
                    $alipay['transaction_id']=$order['payment_no'];
                    $alipay['total_fee']=$max_money;
                    $alipay['refund_fee']=$amount;
                    $alipay['refund_reason']=$note;
                    $alipay['out_request_no']=$out_refund_no;
                    $flag=$payment->payment_refund($alipay);
                    if($flag->code!=10000){
                        $transaction->rollBack();
                        return ['status'=>0,'msg'=>'操作失败 ali'];
                    }
                
                }elseif($order['payment_code']=='money'){
                    $info=array();
                    $info['order'][0]=$order['order_no'];
                    $info=json_encode($info);
                    $changeParams=array();
                    $changeParams['money']=$amount; //积分退回
                    $account=new AccountLogic();
                    $flag=$account->changeAccount($order['m_id'], $changeParams, 7);
                    if($flag['status']!=1){
                        $transaction->rollBack();
                        return ['status'=>0,'msg'=>'操作失败'];
                    }
                }
            }else{
                $info=array();
                $info['order'][0]=$order['order_no'];
                $info=json_encode($info);
                $changeParams=array();
                $changeParams['money']=$amount; //积分退回
                $account=new AccountLogic();
                $flag=$account->changeAccount($order['m_id'], $changeParams, 7);
                if($flag['status']!=1){
                    $transaction->rollBack();
                    return ['status'=>0,'msg'=>'操作失败'];
                }      
            }
            $transaction->commit();
            return ['status'=>1,'msg'=>'操作成功'];
        }catch (\Exception $e) {
           $transaction->rollBack();
           return ['status' => 0, 'msg' => $e->getMessage()];
        }
       
    }
    

    
    /**
     * @desc 拒绝退款
     * @return number[]|string[]
     */
    public function refuse($id,$message){
        $p=yii::$app->db->beginTransaction();
        try {
            $refund=OrderRefundDoc::findOne(['id'=>$id]);
            $refund->status=1;
            $refund->check_status=0;
            $refund->message=$message;
            $refund->scenario='update';
            if(!$refund->save()){
                $p->rollBack();
                 return ['status'=>0,'msg'=>'操作失败'];
            }
            $order=Order::findOne(['id'=>$refund['order_id']]);
            $order->status=11;
            $order->scenario='update';
            if(!$order->save()){
                $p->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            }
            $p->commit();
            return ['status'=>1,'msg'=>'操作成功']; 
       
        } catch (\Exception $e) {
            $p->rollBack();
            throw $e;
        }
        
    }


    /**
     * 申请退款（收货前的整单退款）
     * @param  $orderId
     * @param  $skuId
     * @author vamper
     */
    public function applyRefundBeforeReceive($orderId,$mid,$params){
        $tx = Yii::$app->db->beginTransaction();
        try {
            //判断订单是否可以申请退款（针对发货后,收货前的整单退款）
            $order = Order::find()->where(['m_id'=>$mid,'id'=>$orderId,'payment_status'=>1])->one();
            if (empty($order)) {
                return ['status'=>0,'msg'=>'参数错误'];
            }
            if(!in_array($order['status'],[2,3,11])){
                $tx->rollBack();
                return ['status'=>0,'msg'=>'该订单不能申请退款'];
            }
            $orderRefundDoc = new OrderRefundDoc();
            $orderRefundDoc->loadDefaultValues();
            $orderRefundDoc->load($params,'');
            $orderRefundDoc->amount = $order->pay_amount;
            $orderRefundDoc->shop_id = $order->shop_id;
            $orderRefundDoc->m_id = $mid;
            $orderRefundDoc->order_id = $orderId;
            if (!$orderRefundDoc->save()) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>current($orderRefundDoc->getFirstErrors())];
            }
            $order->status=10;
            if (!$order->save()) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>current($order->getFirstErrors())];
            }
            $orderSkus = $order->orderSku;
            foreach ($orderSkus as $v){
                $v->is_refund = 1;
                if (!$v->save()) {
                    $tx->rollBack();
                    return ['status'=>0,'msg'=>current($v->getFirstErrors())];
                }
            }
            $tx->commit();
            return ['status'=>1,'msg'=>'申请成功！'];
        } catch (StaleObjectException $e) {            
            $tx->rollBack();
            return ['status'=>0,'msg'=>'请勿频繁申请退款！'];
        }catch (Exception $e) {
            $tx->rollBack();
            return ['status'=>0,'msg'=>$e->getMessage()];
        }
    }
    /**
     * 申请退款（针对发货后,收货后的订单退款）
     * @param  $orderId
     * @param  $skuId
     * @author vamper
     */
    public function applyRefundAfterReceive($orderSkuId,$mid,$params){
        $tx = Yii::$app->db->beginTransaction();
        try {
            //判断订单是否可以申请退款
            $orderSku = OrderSku::findOne($orderSkuId);
            if (empty($orderSku)) {
                return ['status'=>0,'msg'=>'参数错误'];
            }       
         /*    if ($orderSku['is_refund']!=0) {
                return ['status'=>0,'msg'=>'请不要重复申请'];
            }   */
            $order = $orderSku->order;            
            if ($order->m_id!=$mid) {
                return ['status'=>0,'msg'=>'参数错误'];
            }
            $service = new Service();
            $service->loadDefaultValues();
            $service->load($params,"");
            $service->order_id = $order->id;
            $service->sku_id = $orderSkuId;
            $service->type = 1;  
            $service->mid = $mid;
            $service->mark = $params['note'];
            $service->shop_id = $order['shop_id'];
            $service->name = $params['name'];
            $service->mobile = $params['mobile'];
            $amount = $this->getMaxRefundAmount($order, $orderSku);
            if ($amount['status']!=1) {
                $tx->rollBack();
                return $amount;
            }else{
                $amount = $amount['data'];                
            }
            $service->amount = $amount;
            if (!$service->save()) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>current($service->getFirstErrors())];
            }
            $order->status = 10;
            if (!$order->save()) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>current($order->getFirstErrors())];
            }
            $orderSku->is_refund = 1;
            if (!$orderSku->save()) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>current($orderSku->getFirstErrors())];
            }
            $tx->commit();
            return ['status'=>1,'msg'=>'申请成功！'];
        } catch (StaleObjectException $e) {
            $tx->rollBack();
            return ['status'=>0,'msg'=>'请勿频繁申请！'];
        }catch (Exception $e) {
            $tx->rollBack();
            return ['status'=>0,'msg'=>$e->getMessage()];
        }
    }
    
    
    /**
     * 申请换货/维修
     * @param  $orderId
     * @param  $skuId
     * @author vamper
     */
    public function applyExchange($orderSkuId,$mid,$params){
        if ($params['type']!=2&&$params['type']!=3) {
            return ['status'=>0,'msg'=>'参数错误'];
        }
        $tx = Yii::$app->db->beginTransaction();
        try {
            //判断订单是否可以申请退款
            $orderSku = OrderSku::findOne($orderSkuId);
            if (empty($orderSku)) {
                return ['status'=>0,'msg'=>'参数错误'];
            }
            if ($orderSku['is_refund']!=0) {
                return ['status'=>0,'msg'=>'请不要重复申请'];
            }
            $order = $orderSku->order;
            if ($order->m_id!=$mid) {
                return ['status'=>0,'msg'=>'参数错误'.$order->m_id.$mid];
            }
            $service = new Service();
            $service->loadDefaultValues();
            $service->load($params,"");
            $service->order_id = $order->id;
            $service->sku_id = $orderSkuId;
            $service->amount = 0;
            $service->mid = $mid;
            $service->mark = $params['note'];
            $service->shop_id = $order['shop_id'];
            $service->name = $params['name'];
            $service->mobile = $params['mobile'];
            if (!$service->save()) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>current($service->getFirstErrors())];
            }           
            $orderSku->is_refund = $params['type']==2? 3:5;
            if (!$orderSku->save()) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>current($orderSku->getFirstErrors())];
            }
            $tx->commit();
            return ['status'=>1,'msg'=>'申请成功！'];
        } catch (StaleObjectException $e) {
            $tx->rollBack();
            return ['status'=>0,'msg'=>'请勿频繁申请！'];
        }catch (Exception $e) {
            $tx->rollBack();
            return ['status'=>0,'msg'=>$e->getMessage()];
        }
    }
    
    public function sendBack($mid,$serviceId,$params){
        $service = Service::find()->where(['and',['mid'=>$mid,'id'=>$serviceId,'apply_status'=>1],['in','status',[0,2]]])->one();
        if (empty($service)) {
            return ['status'=>0,'msg'=>'参数错误'];
        }
        $service->company = $params['company'];
        $service->delivery_no = $params['delivery_no'];
        $service->delivery_time=time();
        if (!$service->save()){
            return ['status'=>0,'msg'=>current($service->getFirstErrors())];
        }
        return ['status'=>1,'msg'=>'成功！'];
    }
   
    /**
     * 获取退货最大退款金额
     * @param array $order
     * @param array $ordersku
     * @return number|string
     */
    public function getMaxRefundAmount($order,$ordersku){
        $skuTotalSellPriceReal = $ordersku['sku_sell_price_real']*$ordersku['num'];//退款货物实际支付价格        
        $originPayAmount = $order['sku_price_real'];//订单未改价前商品总价
        $payAmount = $order['pay_amount'];//订单改价后商品总额。
        
        if (empty($order['coupons_id'])) {//没有使用优惠券
            $skuRefundAmount = $skuTotalSellPriceReal*$payAmount/$originPayAmount;//该商品可退款金额 = 该商品实际支付价*改价后金额/改价前商品金额
            $refundMaxAmount = bcsub($skuRefundAmount, 0,2);
            if ($refundMaxAmount>0) {
                return ['status'=>1,'msg'=>'ok','data'=>$refundMaxAmount];
            }else{
                return ['status'=>0,'msg'=>'优惠商品，超过改价限制，不可进行退款！'];
            }
        }else{
            $couponItem = CouponItem::findOne($order['coupons_id']);
            $coupon = $couponItem->coupon;
            $couponPrice = $order['coupons_price'];
            $skuRefundAmount = $skuTotalSellPriceReal*$payAmount/$originPayAmount;//该商品可退款金额 = 该商品实际支付价*改价后金额/改价前商品金额
            $skuUseCouponPrice = $couponPrice*$skuTotalSellPriceReal/$originPayAmount; //该商品使用的优惠券金额 = 优惠总额*该商品实际支付价/改价前商品总额
            if ($order['discount_price']>0) {//改价，总价减少
                if ($coupon['product_limiter']==0) {//全场优惠券                   
                    $refundMaxAmount = bcsub($skuRefundAmount-$skuUseCouponPrice, 0,2);
                    if ($refundMaxAmount>0) {
                        return ['status'=>1,'msg'=>'ok','data'=>$refundMaxAmount];
                    }else{
                        return ['status'=>0,'msg'=>'优惠商品，超过改价限制，不可进行退款！'];
                    }
                }elseif($coupon['product_limiter']==1){//指定商品
                    if ($coupon['product_limiter_id']!=$ordersku['goods_id']) {//退款物品不是优惠券商品
                        $refundMaxAmount = bcsub($skuRefundAmount, 0,2);
                        if ($refundMaxAmount>0) {
                            return ['status'=>1,'msg'=>'ok','data'=>$refundMaxAmount];
                        }else{
                            return ['status'=>0,'msg'=>'该商品不可进行退款！'];
                        }
                    }else{//退款物品为优惠券商品
                        $refundMaxAmount = bcsub($skuRefundAmount-$couponPrice, 0,2);
                        if ($refundMaxAmount>0) {
                            return ['status'=>1,'msg'=>'ok','data'=>$refundMaxAmount];
                        }else{ 
                            return ['status'=>0,'msg'=>'该商品不可进行退款！'];
                        }
                    }
                }
            }else{//改价总价增加
                if ($coupon['product_limiter']==0) {//全场优惠券
                    $refundMaxAmount = bcsub($skuTotalSellPriceReal-$skuUseCouponPrice, 0,2);
                    if ($refundMaxAmount>0) {
                        return ['status'=>1,'msg'=>'ok','data'=>$refundMaxAmount];
                    }else{
                        return ['status'=>0,'msg'=>'优惠商品，超过改价限制，不可进行退款！'];
                    }
                }elseif($coupon['product_limiter']==1){//指定商品
                    $refundMaxAmount = bcsub($skuTotalSellPriceReal-$couponPrice, 0,2);
                    if ($refundMaxAmount>0) {
                        return ['status'=>1,'msg'=>'ok','data'=>$refundMaxAmount];
                    }else{
                        return ['status'=>0,'msg'=>'该商品不可进行退款！'];
                    }
                }
            }     
        }        
        
    }
    
    /**
     * 一键作废
     * @param  $order_id
     * @param  $mark
     * @return number[]|mixed[]|number[]|string[]|number[]|NULL[]
     */
    public function oneCancel($order_id,$mark){
        $tx = Yii::$app->db->beginTransaction();
        try {
            /*  create order_refund_doc data */ 
           $order=Order::findOne($order_id);
            if($order->status==6||$order->status==7||$order->status==10){
                $tx->rollBack();
                return ['status'=>0,'msg'=>'该订单不能作废'];
            }
            $all_money=Order::find()->where(['parent_sn'=>$order['parent_sn']])->sum('pay_amount');
            //平台优惠券金额
            $platform=PlatformCouponLog::find()->where(['or',['third_no'=>$order['order_no']],['third_no'=>$order['parent_sn']]])->andWhere(['status'=>1])->one();
            $coupon_money=0;
            if($platform){
                $coupon_money=$platform['money'];
                $platform->status=2;
                $platform->save();
                if($platform->hasErrors()){
                    $transaction->rollBack();
                    return ['status' => 0, 'msg' => '操作失败'];
                }
            }
            //父订单直实用户支付金额
            $max_money=bcsub($all_money,$coupon_money,2);
            //子订单最大可退金额
            $max_amount=bcsub($order['pay_amount'],$coupon_money,2);
            $out_refund_no=Tools::get_order_no();
            $model=new OrderRefundDoc();
            $model->loadDefaultValues();
            $user_id=$order->m_id;
            $model->order_id=$order_id;
            $model->m_id=$user_id;
            $model->shop_id=$order->shop_id;
            $model->status=2;
            $model->message=$mark;
            $model->amount=$max_amount;
            $model->out_refund_no=$out_refund_no;
            $model->save();
            if($model->hasErrors()){
                $tx->rollBack();
                return ['status'=>0,'msg'=>current($model->getFirstErrors())];
            }
            $order->scenario='update';
            $order->status=6;//全部退款完成
            if (!$order->save()) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>current($order->getFirstErrors())];
            }
            $orderSkus = $order->orderSku;
            foreach ($orderSkus as $v){
           
                $v->is_refund = 2;//退款完成
                if (!$v->save()) {
                    $tx->rollBack();
                    return ['status'=>0,'msg'=>current($v->getFirstErrors())];
                }
            }
            //退返积分、优惠券、减销量、加库存
            $logic=new OrderLogic();
            $m=$logic->refund_account($order['m_id'],$order);
            if($m['status']!=1){
                $tx->rollBack();
                return ['status' => 0, 'msg' => '操作失败1'];
            }
        //执行退款
            $payment= $this->refundPayment($order, $max_amount,$max_money, 0,$out_refund_no, '');
            if($payment['status']!=1){
                $tx->rollBack();
                return ['status' => 0, 'msg' =>$payment['msg']];
            }   
            $tx->commit();
            return ['status'=>1,'msg'=>'操作成功'];
        } catch (StaleObjectException $e) {
            $tx->rollBack();
            return ['status'=>0,'msg'=>'请勿频繁申请退款！'];
        }catch (Exception $e) {
            $tx->rollBack();
            return ['status'=>0,'msg'=>$e->getMessage()];
        }
        
         
    }

}