<?php
/**
 * 
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-05-30 17:35
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace common\logic;
use common\models\Order;
use common\models\OrderLog;
use common\models\Product;
use common\models\ProductComment;
use common\models\Shop;
use common\models\ShopRecharge;
use yii;
use common\helpers\Tools;
use yii\base\Exception;
use yii\data\Pagination;
use yii\db\StaleObjectException;
use common\models\OrderSku;
use common\models\OrderDeliveryDoc;
use common\models\DistributLog;
use backend\models\Skus;
use common\models\Recharge;
use yii\helpers\ArrayHelper;
use common\modules\promotion\models\FlashSale;
use common\models\OrderArrive;
use yii\helpers\Json;
use common\modules\coupon\models\Coupon;
use common\components\job\JobOrderConfirm;
use common\components\job\JobProductComment;
use common\models\ShopAccoutLog;
class OrderLogic{
    public $obj;
    public function __construct()
    {
        $this->obj=new Order();
    }
    /**
     * 计算order_price
     * @param  $data
     * @return string
     */ 
    public function getOrderPrice($data){
        return bcadd($data['sku_price_real'], $data['delivery_price_real'],2);
    }
    /**
     * 计算订单实际支付价格pay_amount
     * @param  $data
     * @return string
     */
    public function getPayAmount($data){
        $calc1 = bcsub($this->getOrderPrice($data) , $data['integral_money'],2);
        $calc2 = bcsub($calc1 , $data['coupons_price'],2);
        $calc3 = bcsub($calc2 , $data['discount_price'],2);
        $pay = $calc3;
        return $pay;
    }
    

    public function getCommentDelay($delivery_time){
      $delay=$delivery_time+24*3600-time();
        return $delay>0?$delay:1; 
    }

    public function setIntegral($num,$orders){
        $totalOrderPrice = 0;
        foreach ($orders as $order){
            $totalOrderPrice += $order['order_price'];
        }
        foreach ($orders as &$order){
            if($order != end($orders)) {
                // 不是最后一项
                //$orderNum = 
            } else {
                // 最后一项
                
            }
        } 
    }
     

    /**
     * 获取订单列表
     * @param $user
     * @param int $status
     * @url http://xcx.jihexian.com/api/v1/order/all?page=1&per-page=1 可以通过per-page设置每页的条数
     */
    public function get_data($user,$status,$in='in'){
             if(!empty($status)){
                 if(!is_array($status)){
                     $data=['status'=>$status,'m_id'=>$user,'is_del'=>0];
                 }elseif($in=='in'){
                     $data=['status'=>$status,'m_id'=>$user,'is_del'=>0];
                 }
             }else{
                 $data=['m_id'=>$user,'is_del'=>0];
             }
          
               $query=Order::find()->where($data);
               $query = Order::find()->where($data)->joinWith('orderSku')->asArray();
               $countQuery =  Order::find()->where($data);
               $pages = new Pagination(['totalCount' => $countQuery->count()]);
               $pages->validatePage=false;

               $models = $query->offset($pages->offset)
                   ->limit($pages->limit)
                   ->orderBy('id DESC, order_no DESC')->all();
               foreach ($models as $key => $value) {

                     foreach ($value['orderSku'] as $kk=>$va){
                         $product_image=Tools::get_product_image($va['goods_id']);
                         $models[$key]['orderSku'][$kk]['sku_image']=$va['sku_image']!=''?$va['sku_image']:$product_image;
                         $models[$key]['orderSku'][$kk]['sku_thumbImg']=$va['sku_thumbImg']!=''?$va['sku_thumbImg']:$product_image;
                     }
               }
               $data['totalCount']=$countQuery->count();
               $data['pageCount']=$pages->getPageCount();
               $data['currentPage']=$pages->getPage();
               $data['perPage']=$pages->getPageSize();

               return  [
                   'items' => $models,
                   '_meta' => $data,

               ];

    }

    
    /**
     * 取消订单
     * @param int $m_id
     * @param string  $order_id 
     * @return number[]|string[]
     */
    public function cancel_order($m_id,$order_id){
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $order= Order::find()->where(['m_id'=>$m_id,'id'=>$order_id])->one();
            if (!$order) {
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'记录不存在'];
            }
            $order->status = 8;//用户没有支付，直接取消订单
            $order->save();
            if ($order->hasErrors()) {
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            }
         
            $row=$this->return_account($m_id,$order);
            if($row['status']!=1){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            } 
            
            $distribut=new DistributeLogic();
            $dis=$distribut->Cancel($m_id, $order);
            if($dis['status']!=1){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            }
            
            $transaction->commit();
            return ['status'=>1,'msg'=>'成功'];
            
        }catch(StaleObjectException $e){
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'请不要重复操作'];
        } catch (Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
        }
    }
    
    
    public function sys_cancel_order($order_id,$m_id){
        
       
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $order= Order::find()->where(['m_id'=>$m_id,'id'=>$order_id])->one();
            
            if(empty($order)){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'记录不存在'];
            }
            $order->status = 9;//订单超时，自动作废
            $order->save();
            if ($order->hasErrors()) {
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            }
            
            $row=$this->return_account($m_id,$order);
            if($row['status']!=1){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            }
            $distribut=new DistributeLogic();
            $dis=$distribut->Cancel($m_id, $order);
            if($dis['status']!=1){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            }
            $transaction->commit();
            return ['status'=>1,'msg'=>'成功'];
            
        }catch(StaleObjectException $e){
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'请不要重复操作'];
        } catch (Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
        }
    }

    /**
     * 
     * @param int order_sku $id
     * @param int $m_id
     * @param array() $data
     * @return string|number[]|string[]
     */
    public function Comment($id,$m_id,$data){
            $transaction = Yii::$app->db->beginTransaction();
           try {        
             $order_sku=OrderSku::findOne($id);
             $order = Order::find()->where(['id'=>$order_sku['order_id'],'m_id'=>$m_id])->one();
             
             if(empty($order)){
             return Json::encode(['status'=>0,'msg'=>'参数错误']);
             }
             if ($order_sku->is_comment==1){
             return Json::encode(['status'=>0,'msg'=>'您已经评价过了']);
             }
             $productComment = new ProductComment();
      
             if ($data['total_stars']<2){
             $data['appraise'] = 1;
             }else if($data['total_stars']>=2&&$data['total_stars']<4){
             $data['appraise'] = 2;
             }else if($data['total_stars']>=4&&$data['total_stars']<6){
             $data['appraise'] = 3;
             }
             $data['member_id']=$m_id;
             $data['goods_id']=$order_sku['goods_id'];
             $data['order_sku_id']=$id;
             $data['order_no']=$order_sku['order_no'];
             $data['shop_id']=$order_sku['shop_id'];
             $productComment->load($data, '');
             $productComment->loadDefaultValues();
             $productComment->save();
             if($productComment->hasErrors()) {
             $transaction->rollBack();
             return ['status'=>-1,'msg'=>current($productComment->getFirstErrors())];
             }
             //购物获取积分
              $scoreLogic = new ScoreLogic();
              $scoreStatus=$scoreLogic->addScore($order_sku);
              if($scoreStatus['status']!=1){
              $transaction->rollBack();
              return ['status'=>-1,'msg'=>'更新积分失败'];
              }      
             //分享积分
             /*             $shareLogic = new ShareLogic();
             if (!$shareLogic->setShareReward($data['order_sku_id'])){
             $transaction->rollBack();
             return ['status'=>-1,'msg'=>'更新积分失败'];
             } */
            
            //更新order_skus已点评
            $order_sku->is_comment=1;
            $order_sku->save();
            if($order_sku->hasErrors()) {
                $transaction->rollBack();
                return ['status'=>-2,'msg'=>current($order_sku->getFirstErrors())];
            }      
            //更新如果订单关联的order_skus都已经评论完了，改变order表状态
            $count=OrderSku::find()->where(['order_id'=>$order_sku['order_id'],'is_comment'=>0])->count(); 
            if($count==0) {
                $order->status = 5;
                $order->save();
                if ($order->hasErrors()) {
                    $transaction->rollBack();
                    return ['status' => -3, 'msg' =>current($order->getFirstErrors())];
                }
                //分销结算抽成
                $distribut=DistributLog::find()
                ->where(['and',['status'=>2],['cid'=>$m_id],['order_no'=>$order['order_no']]])
                ->all();
                if($distribut){
                    $distribut_log=new DistributeLogic();
                    $su= $distribut_log->changestatus($distribut);
                    if($su['status']!=1){
                        $transaction->rollBack();
                        return ['status' => 0, 'msg' =>'失败'];
                    }
                }
                
                //店铺资金结算（平台抽佣，店铺资金结算）
                $log=new ShopCommissionLogic();
                $com=$log->Log(0, $order['id'], $order->m_id);
                if($com['status']!=1){
                    $transaction->rollBack();
                    return ['status' => 0, 'msg' =>'失败'];
                }
            }
            $transaction->commit();
            return ['status'=>1,'msg'=>'评论成功'];
        }catch (StaleObjectException $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'请勿频繁申请！'];
        }catch(\Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>$e->getMessage()];
        } 
    }
 
    /**
     * @desc 用户确认收货
     * @param int $order_id
     * @param int $m_id
     * @throws Exception
     * @return number[]|string[]
     */
    public function confirm($order_id,$m_id){
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $order= Order::find()->where(['m_id'=>$m_id,'id'=>$order_id])->one();
            if($order['status']!=3){
                throw new Exception('操作失败');
            }
            $order->status =4;
            $order->delivery_time=time();
            $num=0;
            foreach ($order['orderSku'] as $vo){
                if($vo['is_send']==0){
                    $num+=1;
                }
                try {
                $job=new JobProductComment();
                $data=array();
                $data['uid']=$order->m_id;
                $data['order_sku_id']=$vo['id'];
                $data['total_stars']=5;
                $job->data=$data;
                Yii::$app->queue->delay(24*3600)->push($job);
                } catch (Exception $e) {
                }
            }

            if($num>0){
                $order->delivery_status=4;//部分收货
            }else{
                $order->delivery_status=3;//全部收货
            }
          
            $order->save();
            if($order->hasErrors()){
                return ['status'=>0,'msg'=>'操作失败'];
            }
            $transaction->commit();
            return ['status'=>1,'msg'=>'操作成功'];
            
        } catch (Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
            
        }
    }

    /**
     * 
     * @param int $order_no
     * @param int $order_status
     * @param int $shipping_status
     * @param int $pay_status
     * @param string $action_note
     * @param string $status_desc
     * @return number
     */
    public  function saveLog($order_no,$order_status,$shipping_status,$pay_status,$action_note,$status_desc,$shop_id){
        $model=new OrderLog();
        $model->order_no=$order_no;
        $model->user_id=yii::$app->user->id;
        $model->action_user=yii::$app->user->identity->username;
        $model->shop_id=$shop_id;
        $model->order_status=$order_status;
        $model->shipping_status=$shipping_status;
        $model->pay_status=$pay_status;
        $model->action_note=$action_note;
        $model->status_desc=$status_desc;
        $model->loadDefaultValues();
        if($model->save()){
            return ['status' => 1, 'msg' => '操作成功'];
        }else{   
            return ['status' => 0, 'msg' => '失败'];
        }
    }
 
  /**
   * 后台设置订单线下支付
   * @param array $model 订单
   */  
    public function SetPay($model){
        $truncate=yii::$app->db->beginTransaction();
        try {
            if($model['payment_status']!=0||$model['status']!=1){
                $truncate->rollBack();
                return ['status'=>0,'msg'=>'该订单不能设置线下支付'];
            }
            $ext=array();
            $ext['transaction_id']='houtai'.time().rand(1000,9999);
            $ext['payment_code']='money';
            $ext['payment_name']='线下支付';
            $flag=$this->update_pay_status($model->order_no,$model->pay_amount,$ext);  
            $ff=$this->saveLog($model['order_no'],2, 0, 1, '','后台把订单设置为已支付',$model->shop_id);    
            if($flag['status']!=1||$ff['status']!=1){
                $truncate->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            }
            $truncate->commit();
            return ['status'=>1,'msg'=>'操作成功'];
        } catch (Exception $e) {
            $truncate->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
        }
    }
    
    
    public function receive($model){
        $truncate=yii::$app->db->beginTransaction();
        try {
            if($model['status']!=3||$model['delivery_status']==0){
                $truncate->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            }
            $model->status=4;//status=4 已收货
            $model->delivery_status=3;  
            $flag=$this->saveLog($model['order_no'],$model->status, $model['delivery_status'],  $model->payment_status, '','后台把订单状态设置为已收货',$model->shop_id);
            if(!$model->save()||$flag['status']!=1){
                $truncate->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            }
            $truncate->commit();
            return ['status'=>1,'msg'=>'操作成功'];
        } catch (Exception $e) {
            $truncate->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
        }
    }
        
    /**
     * 一键收货完成
     * @return number[]|string[]
     */
    function oneFinish($model){
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if(empty($model)||$model->status==4){
                $transaction->rollBack();

                return ['status'=>0,'msg'=>'没有权限'];
            }
            if(isset($model['data'])){
            $now=time();
            if($now<$model['data']['use_start_time']||$now>$model['data']['use_end_time']){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'已不在使用时间范围内'];
            }
            }
            $model->status=4;
            $model->delivery_status=1;
            $model->delivery_time=time();
            $model->save();
            if($model->hasErrors()){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>current($model->getFirstErrors())];
            }
            //不用评价，默认好评
            foreach ($model['orderSku'] as $vo){
                $data=array();
                $data['uid']=$model->m_id;
                $data['order_sku_id']=$vo['id'];
                $data['total_stars']=5;
                $comment=new CommentLogic();
                $su= $comment->addComment($data);
                if($su['status']!=1){
                    $transaction->rollBack();
                    return ['status'=>0,'msg'=>$su['msg']];
                }
            }
            
            $flag=$this->saveLog($model['order_no'],5, 1, $model->payment_status, '','工作人员核销了订单',$model->shop_id);
            if($flag['status']!=1){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            }
            $transaction->commit();
            return ['status'=>1,'msg'=>'操作成功'];

            
        } catch (Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
        }
    }
    /**
     * 
     * @param int  $id   //                    订单id
     * @param array $su  //OrderDeliveryDoc  发货信息数组
     * @param array $sku //OrderSku          id数组
     * @throws \Exception
     */
    function OrderSend($id,$su,$sku){
        $transaction = Yii::$app->db->beginTransaction();
     
        $delivery=new OrderDeliveryDoc();
        try {
        
            //新增发货单
           $delivery->load($su);
            $delivery->save(); 
            if($delivery->hasErrors()) {
         
                $transaction->rollBack();
                return ['status'=>0,'msg'=>current($delivery->getErrors())];
            }
            //更新order_sku
            $num=OrderSku::updateAll(['is_send'=>1],['in','id',$sku]);
            if($num<1){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
                
            } 
            //更新order表
           $count=OrderSku::find()->where(['order_id'=>$id,'is_send'=>0])->count();
     
            $order=Order::findOne($id);
            $order->sendtime=time();
            $order->status=3;
            if($count>0){
                $order->delivery_status=2;//部分发货
                $desc='部分发货';
            }else{
                $order->delivery_status=1;//全部发货 
                $desc='已全部发货 ';
            }
             $order->save();
             $flag=$this->saveLog($order['order_no'],3,1,1,$su['OrderDeliveryDoc']['note'],$desc,$order['shop_id']);
            if($order->hasErrors()||$flag['status']!=1){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            } 
            try {
                $job=new JobOrderConfirm();
                $job->id=$id;
                $job->m_id=$order->m_id;
                Yii::$app->queue->delay(3*24*3600)->push($job);
            } catch (Exception $e) {
                
            }
            $transaction->commit();
            return ['status'=>1,'msg'=>'操作成功'];
            
        } catch (Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
        }
    }
    

   /**
    * @info 判断是否能发货
    * @param int $status
    * @param int $delivery_status
    * @return string
    */
    function checkSend($status,$delivery_status){
        if($status<2){
            return 0;//'订单未支付';
        }elseif($status==3&&$delivery_status==1){
            return 0; // '订单已经发货完成';
        }else{
           return 1;   
        }
        
    }
    /** 
     * 
     * @param int $status
     * @param int $payment_status
     * @return number
     */
    function checkRefuse($status,$payment_status){
        if($status=2&&$payment_status==1){
            return 1;
        }else{
            return 0;//'订单未支付';
        }
        
    }
    /**
     * @array $order_no 如$order_no=[123434,23434434]
     * @desc 订单支付成功后，更改订单状态逻辑
     * @return number[]|NULL[]|number[]|string[]
     */
    function change($order_no,$ext,$m_id){
            $transaction = Yii::$app->db->beginTransaction();
            $o=count($order_no);
            try {
                $data=array();
                $data['payment_status']=1;
                $data['status']=$ext['status'];
                $data['delivery_status']=$ext['delivery_status'];
                $data['paytime']=time();
                $data['payment_no']=$ext['transaction_id'];
                $data['payment_code']=$ext['payment_code'];
                $data['payment_name']=$ext['payment_name'];
                $mark=isset($ext['mark'])?$ext['mark']:'';
                $num=Order::updateAll($data,['in','order_no',$order_no]);
                if($num!=$o) {
                    return ['status'=>-1,'msg'=>'操作失败'];
                    $transaction->rollBack();
                }
                $orderSku=OrderSku::find()->where(['in','order_no',$order_no])->all();
                //增加销量
                $t=self::add_sale($orderSku);
                if(!$t){
                    $transaction->rollBack();
                    return ['status'=>0,'msg'=>'操作失败'];
                }
                foreach ($orderSku as $value){
                    $product=$value['product'];
                    $distributeOpen = Yii::$app->config->get('distribute_open');//判断分销模块是否开启
                    //判断是否有分销金额,生成分销记录
                    if($distributeOpen&&$product['distribute_money']>0){
                        $distribute=new DistributeLogic();
                        $distribute->money($m_id, $product,$value);
                    }
                 
                }
                $transaction->commit();
                return ['status'=>1,'msg'=>'操作成功'];
                
            } catch(\Exception $e) {
                $transaction->rollBack();
             
                return ['status'=>0,'msg'=>$e->getMessage()];
            }
            
    }
    
    
    /**
    * @desc支付完成修改订单
    * @param $order_no订单号
    * @param array $ext 额外参数
    * @return bool|void  
    */
    public  function update_pay_status($order_no,$order_amount,$ext=array()){ 
        //余额充值
        if(stripos($order_no,'re_') !== false){ 
            $transaction = Yii::$app->db->beginTransaction();
            try {
             //更新recharge表的pay_status
              $recharge=Recharge::find()->where(['order_no'=>$order_no,'pay_status'=>0])->one();
              if(empty($recharge)||$recharge['pay_amount']!=$order_amount){
                  $transaction->rollBack();
                  return  ['status'=>0,'msg'=>'操作失败'];
              }
              $recharge->pay_status=1;
              $recharge->transaction_id=$ext['transaction_id'];
              $recharge->payment_code=$ext['payment_code'];
              $recharge->payment_name=$ext['payment_name'];
              $recharge->save();
              if($recharge->hasErrors()){
                  $transaction->rollBack();
                  return  ['status'=>0,'msg'=>'操作失败'];
              }
             //更改账户余额及明细
             $account=new AccountLogic();
             $info = array();
             $info['recharge'][] = $order_no;
             $info = Json::encode($info);
             $changeParams = array();
             $changeParams['money'] = $recharge['pay_amount'];
             $account_status=$account->changeAccount($recharge['m_id'], $changeParams, 2,$info,'订单充值');
             if($account_status['status']!=1){
                 $transaction->rollBack();
                 return ['status'=>0,'msg'=>'操作失败'];
             }
             $transaction->commit();
             return ['status'=>1,'msg'=>'操作成功'];
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception('操作失败');
            }        
        }elseif (stripos($order_no,'jf_')!==false){   //商家积分充值
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //更新recharge表的pay_status
                $recharge=ShopRecharge::find()->where(['order_no'=>$order_no,'pay_status'=>0])->one();
                if(empty($recharge)||$recharge['pay_amount']!=$order_amount){
                    $transaction->rollBack();
                    return  ['status'=>0,'msg'=>'操作失败'];
                }
                $recharge->pay_status=1;
                $recharge->transaction_id=$ext['transaction_id'];
                $recharge->payment_code=$ext['payment_code'];
                $recharge->payment_name=$ext['payment_name'];
                $recharge->save();
                if($recharge->hasErrors()){
                    $transaction->rollBack();
                    return  ['status'=>0,'msg'=>'操作失败'];
                }
                //更改账户余额及明细
                $sa= new ShopAccoutLog();
                $sa->shop_id= $recharge->shop_id;
                $sa->type=6;
                $sa->pay_amount=$recharge->pay_amount;
                $sa->score=$recharge->score;
                $sa->comment='积分充值';
                $sa->order_no=$recharge->order_no;
                $sa->save();
                if($sa->hasErrors()){
                    $transaction->rollBack();
                    return ['status'=>0,'msg'=>'失败'];
                }  
                //店铺余额改变
                $shop=Shop::findOne(['id'=>$recharge['shop_id']]);
                $shop->setScenario('edit');
                $shop['score']+=$recharge['score'];
                $shop->save();
                if($shop->hasErrors()){
                    $transaction->rollBack();
                    return ['status'=>0,'msg'=>current($shop->getFirstErrors())];
                }
                $transaction->commit();
                return ['status'=>1,'msg'=>'操作成功'];
            } catch (Exception $e) {
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'失败'];
            } 
            
        }elseif (stripos($order_no,'ar_') !== false){  //到店支付
             $orderArrive=OrderArrive::find()->where(['order_no'=>$order_no,'payment_status'=>0])->one();
             $arrive = Yii::$app->db->beginTransaction();
             try {
                 if(empty($orderArrive)||$orderArrive['pay_amount']!=$order_amount){
                     $arrive->rollBack();
                     return  ['status'=>0,'msg'=>'操作失败'];
                 }
                 $orderArrive->payment_status = 1;
                 $orderArrive->payment_code = $ext['payment_code'];
                 $orderArrive->payment_name = $ext['payment_name'];
                 $orderArrive->payment_no =$ext['transaction_id'];;
                 $orderArrive->pay_time = time();
                 $orderArrive->save();
                 if($orderArrive->hasErrors()){
                     $arrive->rollBack();
                     return  ['status'=>0,'msg'=>'操作失败'];
                 }
                 //店铺资金结算（平台抽佣，店铺资金结算）
                 $log=new ShopCommissionLogic();
                 $com=$log->Log(1, $orderArrive->id, $orderArrive->m_id);
                 if($com['status']!=1){
                     $arrive->rollBack();
                     return ['status' => 0, 'msg' =>'失败'];
                 }  
                 $arrive->commit();
                 return ['status'=>1,'msg'=>'成功'];
             }catch(Exception $e){
                 $arrive->rollBack();
                 throw new Exception('操作失败');
             }
            
        }elseif (stripos($order_no,'pn_') !== false){  //组合支付处理
            
            $data=Order::find()->where(['parent_sn'=>$order_no,'payment_status'=>0,'status'=>1])->all();
            $ids=array();
            $total=0;
            foreach ($data as $key=>$vo){
                $ids[$key]=$vo['order_no'];
                $total+=$vo['pay_amount'];
            }
            if(empty($data)||$total!=$order_amount){
                return  ['status'=>0,'msg'=>'订单无效'];
            }
            //$data['delivery_id']==0为正常配送，支付完成后转到待发货状态2  $data['delivery_id']==1电子票直接改成status=3已发货状态
            $data[0]['delivery_id']==1?$ext['status']=3:$ext['status']=2;   
            $data[0]['delivery_id']==1?$ext['delivery_status']=1:$ext['delivery_status']=0;
            return $this->change($ids,$ext,$data[0]['m_id']);
        }else{
         
            $order=Order::find()->where(['order_no' =>$order_no,'payment_status'=>0,'status'=>1])->one();
          
            if(empty($order)||$order['pay_amount']!=$order_amount){
                return  ['status'=>0,'msg'=>'订单无效'];
            }
            $order['delivery_id']==1?$ext['status']=3:$ext['status']=2;
            $order['delivery_id']==1?$ext['delivery_status']=1:$ext['delivery_status']=0;
            $ids=array();
            $ids[0]=$order_no;
            return $this->change($ids,$ext,$order['m_id']);
        }
        
    }
    
    public function order_result(){
        
    }
    
   
    
    /**
     * orderSku
     * @desc 增加库存
     * @param  array  $orderSku
     * @param $type=0取消未支付订单 $type=1 取消支付订单/退款
     * @return number[]|string[]
     */
    
    public static  function add_stock($orderSku,$type=0) {
        
        $b=yii::$app->db->beginTransaction();
        try {
            foreach ($orderSku as $key=>$value){ 
                $num=$value['num'];
                if(!empty($value['prom_id'])){
                    //增加活动库存
                  switch ($value['prom_type']) {
                        case 1:
                            if($type==0){   //取消和退款
                                $prom = FlashSale::findOne($value['prom_id']);
                                if (!$prom->updateCounters(['order_num'=>-$num])) { //更新下单数
                                    $b->rollBack();
                                    return ['status'=>0,'msg'=>'操作失败'];
                                }}
                                break;
                        default:
                            $b->rollBack();
                            return ['status'=>0,'msg'=>'操作失败'];break;
                    } 
                }else{
                    //修改非活动库存
                    $post = \common\models\Skus::findOne($value['sku_id']);
                    if($post){
                        $s=$post->updateCounters(['stock' =>$num]);
                        if(!$s){
                            $b->rollBack();
                            return ['status'=>0,'msg'=>'操作失败'];
                        }
                    }
                    $product=Product::findOne($value['goods_id']); 
                    //修改商品库存
                    $stock=0;
                    foreach ($product['skus'] as $vv){
                        $stock+=$vv['stock'];
                    }
                    $product->stock=$stock;
                    $p=$product->save();
                    if(!$p){
                        $b->rollBack();
                        return ['status'=>0,'msg'=>'操作失败'];
                    }
                }
            }
            $b->commit();
            return ['status'=>1,'msg'=>'操作成功'];
        } catch (Exception $e) {
            $b->rollBack();
            return ['status'=>-1,'msg'=>'操作失败'];
            
        }
        
    }
    
    /**
     * orderSku
     * @desc下单修改库存接口
     * @param  array  $orderSku
     * @param  减库存
     * @return number[]|string[]
     */
    
    public static  function min_stock($orderSku) {
        
        $b=yii::$app->db->beginTransaction();
        try {
            foreach ($orderSku as $key=>$value){
           
                    $num=$value['num'];
               
                if(!empty($value['prom_id'])){
                    //修改活动库存
                 switch ($value['prom_type']) {
                        case 1:
                            $prom = FlashSale::findOne($value['prom_id']);
                            if (!$prom->updateCounters(['order_num'=>$num])) { //更新下单数
                                $b->rollBack();
                                return ['status'=>0,'msg'=>'操作失败'];
                            }
                            break;
                        default:
                            $b->rollBack();
                            return ['status'=>0,'msg'=>'操作失败'];break;
                    } 
                }else{
                    //修改非活动库存
                    $post = \common\models\Skus::findOne($value['sku_id']);
                    $s=$post->updateCounters(['stock' =>-$num]);
                    
                    $product=Product::findOne($value['goods_id']);
                    //修改商品库存
                    $stock=0;
                    foreach ($product['skus'] as $vv){
                        $stock+=$vv['stock'];
                    }
                    $product->stock=$stock;
                    $p=$product->save();
                    if(!$s||!$p){
                        yii::error($product->getFirstErrors());
                        $b->rollBack();
                        return ['status'=>0,'msg'=>'操作失败'];
                    }
                }
            }
            $b->commit();
            return ['status'=>1,'msg'=>'操作成功'];
        } catch (Exception $e) {
            $b->rollBack();
            return ['status'=>-1,'msg'=>'操作失败'];
            
        }
        
    }
    

    
    
    /**
     *
     * @desc  支付完成加销量
     * @param array $orderSku
     * @param number $type 
     * @return number[]|string[]
     */
    public   function add_sale($orderSku){
        //修改库存
        
        $b=yii::$app->db->beginTransaction();
        try {
            foreach ($orderSku as $value){
               
               
              
                if(!empty($value['prom_id'])){
                    //修改非活动库存
                    switch ($value['prom_type']) {
                        case 1:
                            $prom = FlashSale::findOne($value['prom_id']);
                            
                            if (!$prom->updateCounters(['buy_num'=>$value['num']])||!$prom->updateCounters(['order_num'=>-$value['num']])) { //更新购买数量与下单数
                                $b->rollBack();
                                return ['status'=>0,'msg'=>'操作失败'];
                            }
                           
                            break;
                        default:
                            $b->rollBack();
                            return ['status'=>0,'msg'=>'操作失败'];
                            break;
                    }
                }else{
                    
                    
                    $product=Product::findOne($value['goods_id']);
                    
                    $t=$product->updateCounters(['sale'=>$value['num']]);
                    
                    if(!$t){
                        
                        $b->rollBack();
                        return ['status'=>0,'msg'=>'操作失败'];
                    }
                }
            }
            $b->commit();
            return ['status'=>1,'msg'=>'操作成功'];
            
            
        } catch (Exception $e) {
            $b->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
            
        }
        
    }
    
    /**
     *
     * @desc 减销量  
     * @param array $orderSku
     * @return number[]|string[]
     */
    public   function min_sale($orderSku){
        //修改库存
        
        $b=yii::$app->db->beginTransaction();
        try {
            foreach ($orderSku as $value){
            
                
             
                if(!empty($value['prom_id'])){
                    //修改非活动库存
                    switch ($value['prom_type']) {
                        case 1:
                            $prom = FlashSale::findOne($value['prom_id']);
                            
                            if (!$prom->updateCounters(['buy_num'=>-$value['num']])) { //更新购买数量与下单数
                                $b->rollBack();
                                return ['status'=>0,'msg'=>'操作失败'];
                            }
                          
                            break;
                        default:
                            $b->rollBack();
                            return ['status'=>0,'msg'=>'操作失败'];
                            break;
                    }
                }else{
                    
                    
                    $product=Product::findOne($value['goods_id']);
                    
                    $t=$product->updateCounters(['sale'=>-$value['num']]);
                    
                    if(!$t){
                        
                        $b->rollBack();
                        return ['status'=>0,'msg'=>'操作失败'];
                    }
                }
            }
            $b->commit();
            return ['status'=>1,'msg'=>'操作成功'];
            
            
        } catch (Exception $e) {
            $b->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
            
        }
        
    }

 
    /**
     *
     * @desc订单没有支付取消时退回积分、优惠券 
     * @param  $mid
     * @param  $order
     * @param  
     */
    public  function return_account($mid,$order=array()){
       
        $transaction = Yii::$app->db->beginTransaction();
        try {  
           //添加库存
           $stock=self::add_stock($order['orderSku'],0);
           if($stock['status']!=1){
               
               $transaction->rollBack();
               return ['status'=>0,'msg'=>'失败'];
           } 
           //退回优惠券
          if (!empty($order['coupons_id'])) {
               $couponLogic = new CouponLogic();
               $result = $couponLogic->refundCoupons($order['id'],$order['coupons_id'],$mid);
               if ($result['status']!=1) {
                   $transaction->rollBack();
                   return $result;
               } 
           }           
           $transaction->commit();
           return ['status'=>1,'msg'=>'成功'];
        }catch(\Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>$e->getMessage()];
        }
    }
   
    
    
    
    /**
     *
     * @desc整单退款退回积分
     * @param  $mid
     * @param  $order
     * @param
     */
    public  function refund_account($mid,$order=array()){
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $info=array();
            $info['order'][0]=$order['order_no'];
            
            $info=json_encode($info);
            $changeParams=array();
            if($order['integral']>0){
                $changeParams['score']=$order['integral']; //积分退回
            }
            if(!empty($changeParams['score'])){
                $account=new AccountLogic();
                $account->changeAccount($order['m_id'], $changeParams, 7);
            }
            
            //添加库存
            $stock=self::add_stock($order['orderSku'],1);
            if($stock['status']!=1){
                
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'失败'];
            }
           
            //减销量
            $sale=self::min_sale($order['orderSku']);
            if($sale['status']!=1){
                
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'失败'];
            }
         
            //退回优惠券
            if (!empty($order['coupons_id'])) {
                $couponLogic = new CouponLogic();
                $result = $couponLogic->refundCoupons($order['id'],$order['coupons_id'],$mid);
                if ($result['status']!=1) {
                    $transaction->rollBack();
                    return $result;
                }
            }
            $transaction->commit();
            return ['status'=>1,'msg'=>'成功'];
        }catch(\Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>$e->getMessage()];
        }
    }

    
    /**
     *
     * @desc部分退款修改库存与销量
     * @param  $mid
     * @param  $order
     * @param
     */
    public  function refund_sku_account($orderSku,$num){
        $order_sku=array(['0'=>$orderSku]);        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //添加库存
            $stock=self::add_stock($order_sku,1);
            if($stock['status']!=1){
                
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'失败'];
            } 
            
            $sale=self::min_sale($order_sku);
            if($sale['status']!=1){
                
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'失败'];
            }
            $transaction->commit();
            return ['status'=>1,'msg'=>'成功'];
        }catch(\Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>$e->getMessage()];
        }
    }
    
    

    
    /**
     * 
     * @param  $shopExtraCarts:shopExtraCartsWithShipping
     */
    public function getOrdersByCarts($shopExtraCarts){
        $orders = [];
        $couponLogic = new CouponLogic();
        foreach ($shopExtraCarts as $shopId=>$shopCarts){
            
            $orders[$shopId]['orderSkus'] = array();
            //orderSkus
            foreach ($shopCarts['data'] as $cart){
                $orderSkus = array();
                $orderSkus['goods_id'] = $cart['product_id'];
                //获取商品封面
                $product=Product::findOne($cart['product_id']);
                $product_image=isset($product['image'][0]['thumbImg'])? $product['image'][0]['thumbImg']:Yii::$app->params['defaultImg']['default'];
                $orderSkus['goods_name'] = $cart['product_name'];
                $orderSkus['sku_id'] = $cart['sku_id'];
                $orderSkus['sku_image'] = $cart['skus']['image']!=''?$cart['skus']['image']:$product_image;
                $orderSkus['sku_thumbImg'] = $cart['skus']['thumbImg']!=''?$cart['skus']['thumbImg']:$product_image;
                $orderSkus['num'] = $cart['num'];
                $orderSkus['sku_market_price'] = $cart['market_price'];
                $orderSkus['sku_sell_price'] = $cart['sale_price'];
                $orderSkus['sku_sell_price_real'] = $cart['sale_price_real'];
                $orderSkus['sku_weight'] = $cart['skus']['weight'];
                $orderSkus['sku_value'] = $cart['sku_values'];
                $orderSkus['num'] = $cart['num'];
                $orderSkus['shop_id'] = $cart['shop_id'];
                $orderSkus['prom_id'] = $cart['prom_id'];
                $orderSkus['prom_type'] = $cart['prom_type'];
                array_push($orders[$shopId]['orderSkus'], $orderSkus);
            }           
            //order
            $order = array();
            $order['delivery_price'] = $shopCarts['shipping_price'];//实际的邮费价格
            $order['delivery_price_real'] = $shopCarts['shipping_price'];;//实际的邮费价格
            $order['sku_price']=$shopCarts['sale_total'];//商品市场价格
            $order['sku_price_real']=$shopCarts['sale_real_total'];//产品实际价格
            $order['order_price'] = $this->getOrderPrice($order);
            $order['integral_money'] = $shopCarts['scoreMoney'];
            $order['integral'] = $shopCarts['score'];
            $order['discount_price']=0;
            //设置优惠券信息
            if (!empty($shopCarts['coupon'])) {
                $order['coupons_id'] = $shopCarts['coupon']['id'];
                $coupons_price = $couponLogic->getDiscountPrice($shopCarts['coupon']['coupon_id'], $order['sku_price_real']);
                $order['coupons_price'] = $coupons_price;
            }else{
                $order['coupons_price'] = 0;
            }
            $order['pay_amount'] = $this->getPayAmount($order);
            
            $orders[$shopId]['order'] = $order;            
            
        }
        return $orders;
    }
    /**
     * 获取订单详情页数据
     * 
     */
    public function getOrderDetails($orderId,$uid){
        $data=Order::find()->joinWith('orderSku')->joinWith('couponItem')->where(['yj_order.id'=>$orderId,'m_id'=>$uid])->asArray()->one();   
        $buyTotal = 0;
        foreach ($data['orderSku'] as $key=>$va){
            $product_image=Tools::get_product_image($data['orderSku'][0]['goods_id']);
            $data['orderSku'][$key]['sku_image']=$va['sku_image']!=''?$va['sku_image']:$product_image;
            $data['orderSku'][$key]['sku_thumbImg']= $va['sku_thumbImg']!=''?$va['sku_thumbImg']:$product_image;
            $buyTotal =bcadd($buyTotal,$data['orderSku'][$key]['num'],2);       
        }
        $data['buy_total'] = $buyTotal;
        $data['wait_pay_time']=Yii::$app->config->get('pay_time',2);
        $data['coupon'] = Coupon::find()->where($data['couponItem']['coupon_id'])->asArray()->one();
        return $data;
    }
    
    /**
     * 余额支付逻辑
     */
    
    public  function money($m_id,$con){
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //$member=Member::findOne($m_id);
            $account=new AccountLogic();

            $info = Json::encode($con['info']);

            $changeParams = array();
            $changeParams['money'] = -$con['pay_amount'];
            $account_status=$account->changeAccount($m_id, $changeParams, 1,$info,'订单消费' );
            

            if($account_status['status']!=1){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            }

            //更新订单状态
         
            $arr=$this->update_pay_status($con['order_no'],$con['pay_amount'],['payment_code'=>'money','payment_name'=>'余额支付','transaction_id'=>rand(1,10000).time()]);
      
            if($arr['status']!=1) {
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            }
            $transaction->commit();
            return ['status'=>1,'msg'=>'操作成功'];
            
        }catch (StaleObjectException $e) {
            // 解决冲突的代码
            $transaction->rollBack();
            throw new Exception('操作错误');
        }catch (Exception $e) {
            
            $transaction->rollBack();
            throw new Exception('操作错误');
        }
    }

}
