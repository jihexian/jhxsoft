<?php
/**
 * 
 * Author vamper 944969253@qq.com
 * Time:2019年6月8日 下午3:49:14
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */

namespace console\controllers;


use yii\console\Controller;
use api\modules\v1\models\Order;
use common\models\Hongbao;
use common\components\job\JobHongbao;
use Yii;
use common\components\job\JobProductComment;
use common\components\job\JobOrder;
use common\logic\OrderLogic;
use common\components\job\JobOrderConfirm;

class JobController extends Controller
{
    /**
     * 全部的红包退回队列
     */
    public function actionHongbaoRefund(){
        $list = Hongbao::find()->where(['status'=>1])->all();
        foreach ($list as $v){
            $jobHongbao = new JobHongbao();
            $jobHongbao->id = $v['id'];
            Yii::$app->queue->delay($jobHongbao->getDelay())->push($jobHongbao);
        }
    }
    /**
     * 全部订单评价
     */
    public function actionProductComment(){
        $list = Order::find()->where(['status'=>4])->all();
        $orderLogic = new OrderLogic();
       
        foreach ($list as $v){
            $orderSkus = $v->orderSku;
            $dalay = $orderLogic->getCommentDelay($v['delivery_time']);
            foreach ($orderSkus as $key=>$value) {
                if($value['goods_id']!=0){
                $data = array();
                $data['uid'] = $v['m_id'];
                $data['order_sku_id'] = $value['id'];
                $data['total_stars'] = 5;
                $job = new JobProductComment();
                $job->data = $data;               
                Yii::$app->queue->delay($dalay+$key*1)->push($job);
                }
            }            
        }
    }
    /**
     * 未付款订单自动取消
     */
    public function actionCancelOrder(){
        $now=time();
        //获取获取超过2小时没有支付的订单
        $data = Order::find()->where(['and',['<','create_time',$now-2*3600],['status'=>1,'payment_status'=>0]])->all();
        foreach($data as  $vo){       
            $job=new JobOrder();
            $job->id=$vo['id'];
            $job->m_id=$vo['m_id'];
            Yii::$app->queue->delay($job->getDelay())->push($job);
        }
        
    }
    /**
     * 未收货订单过期自动收货
     */
    public function actionOrderConfirm(){
        $now=time();
        $data = Order::find()->where(['and',['<','sendtime',$now-7*24*3600],['status'=>3,'payment_status'=>1]])->all();
        foreach($data as  $vo){
            $job=new JobOrderConfirm();
            $job->id=$vo['id'];
            $job->m_id=$vo['m_id'];
            Yii::$app->queue->delay($job->getDelay())->push($job);
        }
    }
    
    
}