<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com  
 * Time:2018年11月28日 下午6:13:02
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace frontend\controllers;
use yii;
use common\models\Order;
use common\logic\OrderLogic;
use common\models\Member;
use common\helpers\Tools;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use frontend\common\controllers\Controller;
use yii\helpers\Url;
use common\helpers\Util;
use yii\helpers\Json;
use common\logic\DistributeLogic;
use common\models\OrderArrive;
use common\models\Plugin;
use common\models\Recharge;
use yii\filters\AccessControl;
use plugins;
use Endroid\QrCode\QrCode;

class PaymentController extends Controller {
    public $payment; //  具体的支付类
    public $payment_code; //  具体的支付code
    
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
    
    public function init(){
        parent::init();

      $this->payment_code = Yii::$app->request->getBodyParam('payment_code');
        if(empty($this->payment_code)){
            $this->payment_code=yii::$app->request->get('payment_code');
        }
        if(empty( $this->payment_code)){
            header("Content-type:text/html;charset=utf-8");
            exit('请选择一种支付方式');     
        } 
        switch($this->payment_code){
            case 'weixin':$this->payment=new \plugins\weixin\Weixin();break;
            case 'alipayMobile':$this->payment=new \plugins\alipayMobile\AlipayMoblie();break;
         
            default:break;
        }
    }
 

    public function actionQrcode($url)
    {
      
        $qrCode = new QrCode($url);
        header('Content-Type: '.$qrCode->getContentType());
        return $qrCode->writeString();
    }

    
    
    /**
     * @desc 支付方式选择
     * @param int $pay_id
     * @param int $order_id
     */   
    public function actionCode(){  

        $parent_sn=yii::$app->request->get('parent_sn');
        $order_id=yii::$app->request->get('order_id');
        if(!$order_id&&!$parent_sn){
            exit('必须提供一个订单编号');  
            //throw new Exception('必须提供一个订单编号');
        }
        Url::remember();//记录当前地址

        //获取订单总金额
        $con=array();
        $total=0;
        $pay_status=0;
        $sku_total=0;
        $wx_openid=Yii::$app->session->get('wx_openid');
        if(!empty($parent_sn)){
            $orders=Order::find()->where(['parent_sn'=> $parent_sn])->all();
            if(empty($orders)){
                throw new Exception('没有数据');
            }
            $info = array();
            foreach ($orders as $key=>$vo){
                $total+=$vo['pay_amount'];
                $pay_status+=$vo['payment_status'];
                $info['order'][$key] =$vo['order_no'];
            }
            $con['pay_amount']=$total;
            $con['order_no']=  $parent_sn;
           
            $con['info']=$info;
            $con['create_time']=$orders[0]['create_time'];
        }else{
            $order = Order::find()->where(['id' => $order_id])->one();
            $con['pay_amount']=$order['pay_amount'];
            $con['order_no']=$order['order_no'];
            $info = array();
            $info['order'][] =$order['order_no'];
            $con['info']=$info;
            $pay_status=$order['payment_status'];
            $total=$con['pay_amount'];   
        }
        if($pay_status>0){
            yii::$app->session->setFlash('error','订单已支付');
           return  $this->redirect(['order/all']);
         
        } 
        if($this->payment_code=='money'){
            return $this->redirect(['payment/pkey','payment_code'=>$this->payment_code,'order_id'=>$order_id,'parent_sn'=>$parent_sn]);
        }
        
      $notifyUrl=getenv('SITE_URL').Url::to(['response/notify','payment_code'=>$this->payment_code]);
 
      if (!empty($wx_openid) && strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
            //微信公众号支付
            $code_str =  $this->payment->getJSAPI($con,$notifyUrl);
            exit($code_str);
        }else{

           $result=$this->payment->get_code($con,$notifyUrl); 
           if(!empty($result)&&isset($result['code_url'])){
               return $this->render('code',['url'=>$result['code_url'],'payment_code'=>$this->payment_code,'order_id'=>$order_id, 'parent_sn'=>$parent_sn]);
           }else{
               yii::$app->session->setFlash('error','错误');
               return $this->redirect(['order/all']);
           }
     

        }  

    }
    
    public function actionPkey(){
        $parent_sn=yii::$app->request->get('parent_sn');
        $order_id=yii::$app->request->get('order_id');
        if(!$order_id&&!$parent_sn){
            throw new Exception('必须提供一个订单编号');
        }
        Url::remember();//记录当前地址
        
        //获取订单总金额
        $con=array();
        $total=0;
        $pay_status=0;
        $sku_total=0;
        $wx_openid=Yii::$app->session->get('wx_openid');
        if(!empty($parent_sn)){
            $orders=Order::find()->where(['parent_sn'=> $parent_sn])->all();
            $info = array();
            foreach ($orders as $key=>$vo){
                $total+=$vo['pay_amount'];
                $pay_status+=$vo['payment_status'];
                $info['order'][$key] =$vo['order_no'];
            }
            $con['pay_amount']=$total;

            $con['order_no']=  $parent_sn;
            $con['info']=$info;

            $con['create_time']=$orders[0]['create_time'];
        }else{
            $order = Order::find()->where(['id' => $order_id])->one();
            $con['pay_amount']=$order['pay_amount'];

            $con['order_no']=$order['order_no'];
            $info = array();
            $info['order'][] =$order['order_no'];
            $con['info']=$info;
            $pay_status=$order['payment_status'];
            $total=$con['pay_amount'];
        }
        if($pay_status>0){
            yii::$app->session->setFlash('error','订单已支付');
            $this->redirect(['order/all']);
            
        }

            if(Yii::$app->request->isPost){ 
                //TODO:验证支付密码
                $password=Yii::$app->request->post('Member')['pay_pwd'];//获取页面password
                $password=Util::encrypt($password);
                $flag=Member::find()
                ->where(['id'=>Yii::$app->user->id])
                ->one();
                if(empty($flag['pay_pwd'])){
                    Yii::$app->getSession()->setFlash('error', '请先设置支付密码');
                    return $this->redirect(['member/reset-pay-password']);
                }
                if($flag['pay_pwd']!=$password){
                    Yii::$app->getSession()->setFlash('error', '密码错误');
                    return $this->goBack();
                }
                
                $logic=new OrderLogic();
                $message=$logic->money(yii::$app->user->id,$con );
                if($message['status']==1){
                    yii::$app->session->setFlash('success','操作成功');
                    $this->redirect(['order/all']);
                }else{
                    yii::$app->session->setFlash('error',$message['msg']);
                    $this->goBack();
                }
                //订单状态及订单log处理
                
            }else{
                $member=Member::find()->where(['id'=>yii::$app->user->id])->one();
                return  $this->render('pkey',[
                        'order'=>$con,
                        'member'=>$member,
                ]);
                
            }
        }
 
    
    private function error($m='操作失败'){
        Yii::$app->session->setFlash('error', $m);
        $this->goBack();
        return false;
    }

    public function actionRecharge()
    {  
           $order_id=yii::$app->request->get('id');
           $model=Recharge::findOne(['id'=>$order_id,'m_id'=>yii::$app->user->id]);
           if(empty($model)||$model->pay_status==1){
               exit('操作失败');
           }
            // 获取订单总金额
            $con = array();
            $con['pay_amount'] =$model->pay_amount;
            $con['order_no'] = $model->order_no;
            $wx_openid=Yii::$app->session->get('wx_openid');
            $notifyUrl=getenv('SITE_URL').Url::to(['response/notify','payment_code'=>$this->payment_code]);
            if (!empty($wx_openid) && strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
                //微信公众号支付
                $code_str =  $this->payment->getJSAPI($con,$notifyUrl);
                exit($code_str);
            }else{
                $result=$this->payment->get_code($con,$notifyUrl);
                if(!empty($result)&&isset($result['code_url'])){
                    return $this->render('other_code',['url'=>$result['code_url'],'payment_code'=>$this->payment_code,'order_no'=>$model->order_no]);
                }else{
                    yii::$app->session->setFlash('error','错误');
                    $this->goBack();
                }
            }  
     }

     public function actionArrive()
     {
         $order_id=yii::$app->request->get('id');
         $model=OrderArrive::findOne(['id'=>$order_id]);
         if(empty($model)||$model->payment_status==1){
             exit('操作失败');
         }
         // 获取订单总金额
         $con = array();
         $con['pay_amount'] =$model->pay_amount;
         $con['order_no'] = $model->order_no;
         $wx_openid=Yii::$app->session->get('wx_openid');
         $notifyUrl=getenv('SITE_URL').Url::to(['response/notify','payment_code'=>$this->payment_code]);
         if (!empty($wx_openid) && strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
             //微信公众号支付
             $code_str =  $this->payment->getJSAPI($con,$notifyUrl);
             exit($code_str);
         }else{
             $result=$this->payment->get_code($con,$notifyUrl);
             if(!empty($result)&&isset($result['code_url'])){
                 return $this->render('other_code',['url'=>$result['code_url'],'payment_code'=>$this->payment_code,'order_no'=>$model->order_no]);
             }else{
                 yii::$app->session->setFlash('error','错误');
                 $this->goBack();
             }
         }
     }
    
  
   
    
    
}