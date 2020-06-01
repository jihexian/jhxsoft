<?php
/**
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-05-30 17:46
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace api\modules\v1\controllers;
use api\common\controllers\Controller;
use common\logic\OrderLogic;
use common\models\OrderSku;
use yii;
use yii\base\Exception;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use common\helpers\Tools;
use common\helpers\Util;
use api\modules\v1\models\Order;
use api\modules\v1\models\OrderSearch;
use common\components\kuaidiniao\Kuaidiniao;
use common\models\OrderDeliveryDoc;
use common\models\Member;
use phpDocumentor\Reflection\Types\Integer;
use common\logic\OrderRefundLogic;
use common\models\OrderRefundDoc;
use common\models\ShippingCompany;
use common\modules\coupon\models\Coupon;
use Endroid\QrCode\QrCode;

class OrderController extends Controller{

/*    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => QueryParamAuth::className(),
                'tokenParam' => 'token',
                'optional' => [
                      
                ],
            ]
        ]);
    }
 */
    public function actionInfo(){
        $model=new OrderLogic();
        $uid =Yii::$app->user->id;
        $order_id=Yii::$app->request->post('order_id');
        $order=Order::findOne(['id'=>$order_id]);
        $data=Order::find()->alias('o')->joinWith('orderSku')->joinWith('couponItem')->where(['o.id'=>$order_id,'m_id'=>$uid])->asArray()->one();
        $buyTotal = 0;
        foreach ($data['orderSku'] as $key=>$va){
            $product_image=Tools::get_product_image($data['orderSku'][0]['goods_id']);
            $data['orderSku'][$key]['sku_image']=$va['sku_image']!=''?$va['sku_image']:$product_image;
            $data['orderSku'][$key]['sku_thumbImg']= $va['sku_thumbImg']!=''?$va['sku_thumbImg']:$product_image;
            $buyTotal =bcadd($buyTotal,$data['orderSku'][$key]['num'],2);
        }
        $data['prov']=$order['province']['name'];
        $data['city']=$order['city']['name'];
        $data['area']=$order['region']['name'];
        $data['buy_total'] = $buyTotal;
        $data['wait_pay_time']=Yii::$app->config->get('pay_time',2);
        $data['coupon'] = Coupon::find()->where($data['couponItem']['coupon_id'])->asArray()->one();
        return $data;
    }
    /**
     * 支付订单信息
     * @return number[]|string[]|\yii\db\ActiveRecord[]|array[]|NULL[]
     */
    public function actionPay(){
        $parent_sn=yii::$app->request->post('parent_sn','');
        $order_id=yii::$app->request->post('order_id',0);
        if(!$order_id&&!$parent_sn){
            throw new Exception('必须提供一个订单编号');
        }
        $con=array();
        $total=0;
        $pay_status=0;
        $pay_time=Yii::$app->config->get('pay_time',2);
        $info = array();
        if(!empty($parent_sn)){
            $orders=Order::find()->where(['parent_sn'=>$parent_sn])->all();
            foreach ($orders as $key=>$vo){
                $total+=$vo['pay_amount'];
                $pay_status+=$vo['payment_status'];
                $info['order'][$key] =$vo['order_no'];
            }
            $con['pay_amount']=$total;
            $con['info']=$info;
            $con['order_no']= $parent_sn;
            if(isset($orders[0])){
                $time=$orders[0]['create_time'];
                
            }
        }else{
            $order = Order::find()->where(['id' => $order_id])->one();
            $con['pay_amount']=$order['pay_amount'];
            $con['order_no']=$order['order_no'];
            $info = array();
            $info['order'][] =$order['order_no'];
            $con['info']=$info;
            $time=$order['create_time'];
        
            $pay_status=$order['payment_status'];    
        }
        //显示账号余额
        $member=Member::findOne(yii::$app->user->id);
        $con['user_money']=$member['user_money'];
        $con['end_time']=date('Y-m-d H:i:s',$time+$pay_time*3600);
        $con['set_pay_pwd']=$member['pay_pwd']!=''?1:0;
        $con['set_mobile']=$member['mobile']!=''?1:0;
        if($pay_status>0){
            return ['status'=>0,'msg'=>'订单已经支付'];
        }
        if (empty($con)) {
            return ['status'=>0,'msg'=>'订单不存在'];
        }
        return ['item'=>$con];
    }

    /**
    * 全部
    */
    public function actionAll(){
        
        $data = array();
        $data['m_id'] = Yii::$app->user->id;
        $data['status']=Yii::$app->request->get('status');

//         $data['num']=10;

        $data['is_del']=0;
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($data);
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $dataProvider->setSort($sort);
//         $model=$dataProvider->getModels();
        return $dataProvider;
    }
    /**
     * 查看物流信息
     */
    public function actionShipping(){
        $order_id=Yii::$app->request->post('order_id');
        $delivery=OrderDeliveryDoc::find()->joinWith('shippingCompany')->alias('doc')->joinWith('order')->where(['doc.m_id'=>Yii::$app->user->id,'order_id'=>$order_id])->orderBy('id desc')->one();
        if($delivery){
            $kd=new Kuaidiniao();
            $input=array();
            $input['OrderCode']=$delivery['order']['order_no'];//订单号
            $input['ShipperCode']=$delivery['shipping_code'];//快递公司
            $input['LogisticCode']=$delivery['delivery_code'];//快递单号
          
            $data= $kd->getOrderTracesByJson(json_encode($input));
         
            return ['msg'=>'查询成功','status'=>1,'items'=>json_decode($data),'shipping_name'=>$delivery['shipping_name']];
        }else{
            return ['msg'=>'不存在或者没有权限','status'=>0];
        }
       
      
    }

    /**
     *用户取消订单
     */
    public function actionCancel(){
        $order_id=Yii::$app->request->post('order_id');
        $logic=new OrderLogic();
       return  $logic->cancel_order(yii::$app->user->id, $order_id);

    }
    /**
     *系统作废订单
     */
    public function actionScancel(){
        $order_id=Yii::$app->request->post('order_id');
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $order= Order::find()->where(['m_id'=>Yii::$app->user->id,'id'=>$order_id])->one();
                if($order['status']!=1&&$order['payment_status']!=0){
                    throw new Exception('操作失败');
                }
                $logic=new OrderLogic();
                $row=$logic->sys_cancel_order($order_id,Yii::$app->user->id);
              yii::error($row);
                if($row['status']!=1){
                    throw new Exception($row['msg']);
                }
                $transaction->commit();
                 return ['status'=>1,'msg'=>'成功'];

        } catch (Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
        }
     
       
    }

    /**
     * @return array
     *用户确认收货
     *
     */
    public function actionConfirm(){
        $order_id=Yii::$app->request->post('order_id');
        $m_id=yii::$app->user->id;
        $logic=new OrderLogic();
        return $logic->confirm($order_id, $m_id);
       
    }


    /**
     * 获取订单里的商品信息
     * $order_id 订单id
     */

    public function actionGetskus(){
        $order_id=Yii::$app->request->post('order_id');
        $data=OrderSku::find()->where(['order_id'=>$order_id])->all();
        foreach ($data as $key=>$va){
        	$product_image=Tools::get_product_image($va['goods_id']);
        	$data[$key]['sku_image'] = $va['sku_image']!='' ? $va['sku_image'] : $product_image;
        	$data[$key]['sku_thumbImg'] = $va['sku_thumbImg']!='' ? $va['sku_thumbImg'] : $product_image;
        }
        return ['item'=>$data];
    }

   /**
    * 删除订单
    */
   public function actionDelete()
   {
       $order_id = Yii::$app->request->post('order_id');
       $order = Order::findOne($order_id);
       if (!empty($order)&&$order->softDelete()) {
           return ['status' => 1, 'msg' => '删除成功'];
       }else{
           return ['status' => 0, 'msg' => '删除失败'];
       }      
   }


    
    /**
     *收货前退款申请
     * @return 
     */
    public function actionApplyRefund(){
        if (Yii::$app->request->isPost) {
            $member = Yii::$app->user->identity;
            $orderId = Yii::$app->request->post('order_id');
            $params = array();
            $params['type'] = Yii::$app->request->post('type');
            $params['note'] = Yii::$app->request->post('note');
       
            $orderRefundLogic = new OrderRefundLogic();            
           //收货前退款
            return $orderRefundLogic->applyRefundBeforeReceive($orderId, $member->id, $params);            
        }
    }
    /**
     *  收货后退款申请
     * @return
     */
    public function actionApplyService(){
        if (Yii::$app->request->isPost) {
            $member = Yii::$app->user->identity;
            $orderSkuId = Yii::$app->request->post('order_sku_id');
            $params = array();
            $params['note'] = Yii::$app->request->post('note');     
            $params['refund_type'] = Yii::$app->request->post('refund_type');//0:原路退返 1、退到余额
            $params['name']=  Yii::$app->request->post('name');
            $params['mobile']=  Yii::$app->request->post('mobile');
            $params['status']= 2;//处理中
            $params['apply_status']= 1;//自动审核通过
            $orderRefundLogic = new OrderRefundLogic();
            //发货后，收货前退款
            return $orderRefundLogic->applyRefundAfterReceive($orderSkuId, $member->id, $params);
        }
    }
    
    /**
     * 发货后，收货后换货/维修申请
     * @return
     */
    public function actionApplyExchange(){
        if (Yii::$app->request->isPost) {
            $member = Yii::$app->user->identity;
            $orderSkuId = Yii::$app->request->post('order_sku_id');
            $params = array();
            $params['type'] = Yii::$app->request->post('type');
            $params['note'] = Yii::$app->request->post('note');
            $params['name']=  Yii::$app->request->post('name');
            $params['mobile']=  Yii::$app->request->post('mobile');
            $params['status']= 2;//处理中
            $params['apply_status']= 1;//自动审核通过
            $orderRefundLogic = new OrderRefundLogic();
            //发货后，收货后换货/维修申请
            return $orderRefundLogic->applyExchange($orderSkuId, $member->id, $params);
        }
    }
    
    /**
     *  退款单详情
     * @return
     */
    public function actionRefuseInfo(){
        $orderId = Yii::$app->request->post('order_id');
        $data=OrderRefundDoc::find()->where(['m_id'=>yii::$app->user->id,'order_id'=>$orderId])->one();
        if(!empty($data)){
           return ['item'=>$data,'status'=>1];
        }else{
           return ['status'=>0];
        }    
    }
    
    /**
     * 提交回寄快速单号接口
     */
    public function actionSend(){
        $serviceId = Yii::$app->request->post('id');
        $mid=yii::$app->user->id;
        $params=array();
        $params['company']=Yii::$app->request->post('company');
        $params['delivery_no']=Yii::$app->request->post('delivery_no');
        $params['delivery_time']=time();
        $service = new OrderRefundLogic();
        return  $service->sendBack($mid, $serviceId,$params);
        
    }
    
    /**
     * 
     */
    public function actionCompany(){
       $data= ShippingCompany::find()->where(['status'=>1])->orderBy('id desc')->all();
  
       return ['items'=>$data];
    
    }
    
    /**
     * 退货/售后申请
     */
    public function actionList(){
      

        $data = array();
        $data['m_id'] = Yii::$app->user->id;
        $data['status']=[2,3,4,5,6,7,10,11];
        $data['is_del']=0;
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($data);
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $dataProvider->setSort($sort);
        //         $model=$dataProvider->getModels();
        return $dataProvider;
    }
    //生成订单编号二维码
    public function actionQrcode(){
        $code=yii::$app->request->post('code');
        $qrCode = new QrCode($code);
        $qrCode->setEncoding('UTF-8');
        header('Content-Type: '.$qrCode->getContentType());
        $dir=Yii::getAlias('@storagePath/upload/')."order/".date('Y-m-d');
        $file =$dir."/".$code.'.png';
        if(!file_exists($dir.$file)){
            Util::create_folders($dir);
            $qrCode->writeFile($file);
        }   
        return ['status'=>1,'data'=>Yii::$app->params['domain'].'/'.explode("/web/", $file)[1],'order_no'=>$code];
        
    }
    
 
}
