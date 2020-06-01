<?php

namespace frontend\controllers;

use Yii;
use frontend\common\controllers\Controller;
use common\logic\OrderLogic;
use common\models\Order;
use common\models\OrderSearch;
use common\models\OrderDeliveryDoc;
use common\components\kuaidiniao\Kuaidiniao;
use yii\web\NotFoundHttpException;
use yii\rest\Serializer;
use yii\helpers\Json;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use yii\helpers\Url;
use common\logic\ShopCommissionLogic;
use common\models\ShopCommissionLog;
use common\logic\VillageCommissionLogic;
use common\logic\DistributeLogic;


use common\models\OrderRefundDoc;
use common\logic\OrderRefundLogic;
use common\models\OrderArrive;
use common\models\Recharge;
use common\models\OrderPick;
/**
 * Order controller.
 */
class OrderController extends Controller
{
	
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * 全部订单
     */
    public function actionAll()
    {
        Url::remember();
        $data['status']=Yii::$app->request->get('status',0); 
        if($data['status']==0)
            unset($data['status']);
        $data['m_id'] = Yii::$app->user->id;        
        $data['num']=10;
        $data['is_del']=0;
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($data);
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $dataProvider->setSort($sort);
        $model=$dataProvider->getModels();
            return $this->render('all',[
                    'data'=>$model,
                    'status'=>isset($data['status'])?$data['status']:'',
            ]);
        
        
        
    } 
    public function actionAjax(){
        $data=Yii::$app->request->post();
        if($data['status']==0)
        unset($data['status']);
        $data['m_id'] = Yii::$app->user->id;
        $data['num']=10;
        $data['is_del']=0;
        $serializer = new Serializer();
        $searchModel = new \api\modules\v1\models\OrderSearch();
        $dataProvider = $searchModel->search($data);
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $dataProvider->setSort($sort);
        $pagecount=$dataProvider->getTotalCount();
        $pagecount=ceil($pagecount/$data['num']);
        return Json::encode(ArrayHelper::merge(['items'=>$serializer->serialize($dataProvider)], ['pagecount'=>$pagecount]));  
    }
    
    
    /**
       * 订单详情
     */
     
    public function actionDetail()
    {
        Url::remember();
        $model=new OrderLogic();
        $order_id=Yii::$app->request->get('order_id');
        $data=Order::find()->where(['id'=>$order_id,'m_id'=>yii::$app->user->id])->one();
        if(empty($data)){
            throw  new NotFoundHttpException('数据不存在');
        }
        $pick=OrderPick::findOne(['order_id'=>$order_id]);
        return $this->render('detail',[
            'data'=>$data,
            'pick'=>$pick,
        ]
        );
        
    }
    /**
     * 取消订单
     * @return \yii\web\Response
     */
    public function actionCancel(){
        $order_id=Yii::$app->request->get('order_id');
        $order=Order::find()->where(['id'=>$order_id])->one();
        $logic=new OrderLogic();
        $message=$logic->cancel_order(yii::$app->user->id, $order_id);
        
        if($message['status']==1){
           
            Yii::$app->getSession()->setFlash('success', '操作成功');
            $this->goback();
        }else{
            Yii::$app->session->setFlash('error','取消失败');
            $this->goback(); 
        }   

    }
    /**
     * 查看物流信息
     */
    public function actionShipping(){
        $order_id=Yii::$app->request->get('order_id');
        $delivery=OrderDeliveryDoc::find()->joinWith('shippingCompany')->alias('doc')->joinWith('order')->where(['doc.m_id'=>Yii::$app->user->id,'order_id'=>$order_id])->orderBy('id desc')->one();
        if($delivery){
            $kd=new Kuaidiniao();
            $input=array();
            $input['OrderCode']=$delivery['order']['order_no'];//订单号
            $input['ShipperCode']=$delivery['shipping_code'];//快递公司
            $input['LogisticCode']=$delivery['delivery_code'];//快递单号
            $data= $kd->getOrderTracesByJson(Json::encode($input));
            //print_r(Json::decode($data));exit(); 
            return $this->render('shipping',['data'=>Json::decode($data),'shipping_name'=>$delivery['shipping_name']]);
           // return ['msg'=>'查询成功','status'=>1,'items'=>json_decode($data),'shipping_name'=>$delivery['shipping_name']];
        }else{
            Yii::$app->getSession()->setFlash('error', '不存在或者没有权限');
            $this->goback();
        }
    }

    /**
     * 
     */
    public function actionResult(){
        $order_id=Yii::$app->request->get('order_id');
        $data=Order::findOne(['id'=>$order_id]);
        $info=OrderRefundDoc::find()->where(['order_id'=>$order_id])->orderBy('id desc')->one();
        return $this->render('result',[
                'data'=>$data,
                'info'=>$info,
                
        ]);
    }
    
    
    public function actionApply(){
        $order_id=Yii::$app->request->get('order_id');
        $data=Order::findOne(['id'=>$order_id]);
        $info=OrderRefundDoc::find()->where(['order_id'=>$order_id])->orderBy('id desc')->one();
        return $this->render('apply',[
                'data'=>$data,
                'info'=>$info,
                
        ]);
    }
    
    public function actionPay(){
        $order_id=yii::$app->request->get('order_id');
        $order= Order::find()->where(['m_id'=>Yii::$app->user->id,'id'=>$order_id])->one();
        if (empty($order)) {
            throw new NotFoundHttpException('订单不存在');
        }
        
        if($order['payment_status']==1){
            throw new \Exception('订单已经支付');
        }
    
    
        return  $this->render('pay',[
            'order'=>$order,
            'order_id'=>$order_id,
        ]); 
    }
    


    /**
     * @return array
     *用户确认收货
     *
     */
    public function actionConfirm($order_id){

       $logic=new OrderLogic();
       $m_id=yii::$app->user->id;
       $flag= $logic->confirm($order_id, $m_id);
       if($flag['status']==1){
           Yii::$app->session->setFlash('success', '操作成功');
           return $this->redirect(['all']);
       }else{
           Yii::$app->session->setFlash('error', '操作失败');
           $this->goback();
       }

    }
    /**
     * 删除订单
     */
    public function actionDelete($order_id)
    {
//         $order_id = Yii::$app->request->post('order_id');
        $order = Order::findOne(['id'=>$order_id]);
        if (!empty($order)&&$order->softDelete()) {
            Yii::$app->session->setFlash('success', '删除成功');
            return $this->redirect(['all']);
        }else{
            Yii::$app->session->setFlash('error', '删除失败');

            $this->goback();
        }
    }    
    /**
      * 退款
    */
    public function actionRefuse(){
        if(yii::$app->request->isPost){
        $order_id=yii::$app->request->post('order_id'); 
        $param=array();
        $param['note']=yii::$app->request->post('note');
        $param['type']=yii::$app->request->post('type');
        $mid=yii::$app->user->id;
        $logic=new OrderRefundLogic();    
        $data=$logic->applyRefundBeforeReceive($order_id,$mid,$param);
        if($data['status']==1){
            Yii::$app->getSession()->setFlash('success', '操作成功');
        }else{
            Yii::$app->getSession()->setFlash('success', $data['msg']);
           
        }
        $this->goback();
        }
    }
   
    /**
     * 查询订单支付状态
     * @return boolean|string
     */

    public function actionCheckStatus(){
        $order_id = Yii::$app->request->post('order_id');
        $parent_sn=Yii::$app->request->post('parent_sn');
        if(!$order_id&&!$parent_sn){
            return false;
        }else{
            if($parent_sn){
                $status= Order::find()->where(['parent_sn'=>$parent_sn])->one();
                if(stripos($parent_sn,'pn_') !== false){ 
                    $status['payment_status']>0?$data= ['status'=>1,'msg'=>'支付完成']:$data= ['status'=>0,'msg'=>'支付失败'];
                }elseif (stripos($parent_sn,'ar_') !== false){  //到店支付
                    $status=OrderArrive::find()->where(['order_no'=>$parent_sn])->one();
                    $status['payment_status']==1?$data= ['status'=>1,'msg'=>'支付完成']:$data= ['status'=>0,'msg'=>'支付失败'];

                }elseif (stripos($parent_sn,'re_') !== false){  //用户充值
                    $status=Recharge::find()->where(['order_no'=>$parent_sn])->one();
                    $count=$status['pay_status']==1?$data= ['status'=>2,'msg'=>'充值成功']:$data= ['status'=>0,'msg'=>'充值失败'];
                }
                
            }else{
                $su=Order::findOne(['id'=>$order_id]);
                if($su['payment_status']==1){
                    $data=['status'=>1,'msg'=>'支付完成'];
                }else{
                    $data=['status'=>0,'msg'=>'支付失败'];
                }
            }
            return json_encode($data);
        }
    }
    
}
