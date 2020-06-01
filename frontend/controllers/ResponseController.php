<?php
/**
 * 
 * 
 * Author wsyone wsyone@faxmail.com 
 * Time:2019年5月20日下午5:26:11
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace frontend\controllers;
use yii;
use yii\web\Controller;
use common\models\OrderArrive;
use common\models\Recharge;
use common\models\Order;
class ResponseController extends Controller{
    public  $payment_code;
    public  $payment;
    public $enableCsrfValidation = false;
    public function init(){
        parent::init();
        $this->payment_code = Yii::$app->request->getBodyParam('payment_code');
        if(empty($this->payment_code)){
            $this->payment_code=yii::$app->request->get('payment_code');
            unset($_GET['payment_code']);
        }
      
        if(empty( $this->payment_code)){
            header("Content-type:text/html;charset=utf-8");
            exit('请选择一种支付方式');
        } 
        switch($this->payment_code){
            case 'weixin':$this->payment=new \plugins\weixin\Weixin();break;
            case 'alipayMobile':$this->payment=new \plugins\alipayMobile\AlipayMoblie();break;
            case 'money':$this->payment=new \plugins\money\Money();break;
            default:$this->payment=new \plugins\weixin\Weixin();break;
        }
       $xml = file_get_contents('php://input');
    }
    
    /**
     * 
     */
    public function actionNotify(){
        
        $this->payment->response();
        exit();
    }
    
    public function actionReturn(){
        $data= $this->payment->respond2();
        $order_no=$data['order_no'];
        if($data['status']==0){
            return $this->render('error',['msg'=>'支付失败']);
        }
        
        //余额充值
        if(stripos($data['order_no'],'re_') !== false){
         
 
                //更新recharge表的pay_status
            $recharge=Recharge::find()->where(['order_no'=>$order_no])->one();
                if(!empty($recharge)&&$recharge['pay_status']==1){
                    return $this->render('success',['msg'=>'充值成功','data'=>$recharge]);
                }else{
                    return $this->render('error',['msg'=>'充值失败']);
                }
            
        }elseif (stripos($order_no,'ar_') !== false){  //到店支付
            $orderArrive=OrderArrive::find()->where(['order_no'=>$order_no])->one();
            if(!empty($orderArrive)&&$orderArrive['payment_status']==1){
                return $this->render('success',['msg'=>'支付成功','data'=>$orderArrive]);
            }else{
                return $this->render('error',['msg'=>'支付失败']);
            }
        }elseif (stripos($order_no,'pn_') !== false){  //组合支付处理
            
            $data=Order::find()->where(['parent_sn'=>$order_no,'payment_status'=>0])->all();
         
            if(empty($data)){
                return $this->render('success',['msg'=>'支付成功']);
            }else{
                return $this->render('error',['msg'=>'支付失败']);
            }
          
        }else{
            
            $order=Order::find()->where(['order_no' =>$order_no,'payment_status'=>1])->one();
            if(empty($order)){
                return $this->render('success',['msg'=>'支付成功','data'=>$data]);
            }else{
                return $this->render('error',['msg'=>'支付失败']);
            }
         
        }
        
    }
}