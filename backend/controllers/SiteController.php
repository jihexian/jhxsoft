<?php

namespace backend\controllers;

use backend\common\controllers\Controller;
use common\logic\MigrationLogic;
use common\models\Order;
use common\models\Product;
use common\models\ProductSearch;
use yii\filters\VerbFilter;
/**
 * Site controller.
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'demo' => [
                'class' => 'yii\web\ViewAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = false;
        return $this->render('index');
    }

    public function actionDashboard()
    {  
        $countOrder = Order::find()->count();//订单总数
        $noSenOrder = Order::find()->andWhere(['delivery_status'=>0])->count();//待发货数量
        $senOrder = Order::find()->andWhere(['delivery_status'=>1])->count();//已发货数量
        $getOrder = Order::find()->andWhere(['delivery_status'=>3])->count();//已收货数量
        $finshOrder = Order::find()->andWhere(['completetime'=>!null])->count();//已完成订单
        $closeOrder = Order::find()->andWhere(['or','status=8','status=9'])->count();//关闭交易订单数量
        $refunOrder = Order::find()->andWhere(['status'=>10])->count();//退款订单数量
        $payOder = Order::find()->andWhere(['payment_status'=>1])->all();//找出所有已经支付的订单
        
        $totleMoney=0;
        foreach($payOder as $po){
            $totleMoney+=$po['pay_amount'];//计算总金额
        }
        $checkoutOder = Order::find()->andWhere(['is_shop_checkout'=>1])->count();//已给商家打款
        $groundProduct = Product::find()->andWhere(['status'=>1])->count();//上架商品数量
        
        $pageData = new ProductSearch();
        $product = $pageData->rankProduct();//找出商品排名数据
        
        $oldtoday=strtotime(date('Y-m-d'),time());//今天零点时间戳
        $oneDayOrder = Order::find()
        ->andWhere(['<','create_time',$oldtoday])
        ->andWhere(['>','create_time',$oldtoday-86400]);//前两天时间戳
        $ysterDayOrder = $oneDayOrder->count();//时间范围的订单数量
        $yesterDayOrderData = $oneDayOrder->all();//昨天订单订单
        $yesterTotleMoney=0;
        foreach($yesterDayOrderData as $ydod){
            $yesterTotleMoney+=$ydod['pay_amount'];//计算昨天总金额
        }
        
        $this_month = strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));//本月第一天时间戳
        $now = strtotime('now');//获取当前时间戳
        $monthOrder = Order::find()
        ->andWhere(['<','create_time',$now])
        ->andWhere(['>','create_time',$this_month]);
        $nowMonthOrder =$monthOrder->count();//本月订单数量
        $nowMonthOrderOrder = $monthOrder->all();//本月已经支付订单
        $monthTotleMoney=0;
        foreach($nowMonthOrderOrder as $noo){
            $monthTotleMoney+=$noo['pay_amount'];//计算昨天总金额
        }
        
        $noPayOder = Order::find()->andWhere(['payment_status'=>0])->count();//找出所有未支付的订单
        
        $noProduct =  Product::find()->andWhere(['<','stock',10])->count();//库存少于10预警
        $salePro = Product::find()->andWhere('sale')->all();//销量
       $saleTotle = 0;
       foreach ($salePro as $sp){ //计算销量
           $saleTotle+=$sp['sale'];
       }
        
        return $this->render('dashboard',[
            'countOrder'=>$countOrder,
            'noSenOrder'=>$noSenOrder,
            'senOrder'=>$senOrder,
            'getOrder'=>$getOrder,
            'finshOrder'=>$finshOrder,
            'closeOrder'=>$closeOrder,
            'refunOrder'=>$refunOrder,
            'totleMoney'=>$totleMoney,
            'checkoutOder'=>$checkoutOder,
            'groundProduct'=>$groundProduct,
            'product'=>$product,
            'ysterDayOrder'=>$ysterDayOrder,
            'yesterTotleMoney'=>$yesterTotleMoney,
            'nowMonthOrder'=>$nowMonthOrder,
            'monthTotleMoney'=>$monthTotleMoney,
            'noPayOder'=>$noPayOder,
            'noProduct'=>$noProduct,
            'saleTotle'=>$saleTotle
        ]);
    }
    /**
     * 
     * @return 昨日、今日、本周、年
     */
    public function actionOrderCount(){
        $data=null;
        $oldtoday=strtotime(date('Y-m-d'),time());//今天零点时间戳
        $oneDayOrder = Order::find()
        ->where(['<','create_time',$oldtoday])
        ->where(['>','create_time',$oldtoday-86400]);//前两天时间戳
        $yesterdaycount = $oneDayOrder->count();//时间范围的订单数量
        $data.=$yesterdaycount.',';
        $oneTodayOrder = Order::find()
        ->where(['<','create_time',$oldtoday+86400])//明天零点时间戳
        ->where(['>','create_time',$oldtoday]);//今天零点时间戳
        $todaycount = $oneTodayOrder->count();
        $data.= $todaycount.',';
        $time = time();
        //判断当天是星期几，0表星期天，1表星期一，6表星期六
        $w_day=date("w",$time);
        //php处理当前星期时间点上，根据当天是否为星期一区别对待
        if($w_day=='1'){
            $cflag = '+0';
        }
        else {
            $cflag = '-1';
        }
        //本周一零点的时间戳
        $weekstart = strtotime(date('Y-m-d',strtotime("$cflag week Monday", $time)));
        //本周末零点的时间戳
        $weekstop = strtotime(date('Y-m-d',strtotime("$cflag week Monday", $time)))+7*24*3600;
        $oneWeekOrder = Order::find()
        ->where(['<','create_time',$weekstop])//明天零点时间戳
        ->where(['>','create_time',$weekstart]);//今天零点时间戳
        $weekcount = $oneWeekOrder->count();
        $data.= $weekcount.',';
        $this_month = strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));//本月第一天时间戳
        $now = strtotime('now');//获取当前时间戳
        $monthOrder = Order::find()
        ->where(['<','create_time',$now])
        ->where(['>','create_time',$this_month]);
        $nowMonthOrder =$monthOrder->count();//本月订单数量
        $data.=$nowMonthOrder.',';
        $begin_year = strtotime(date("Y",time())."-1"."-1"); //本年开始
        $end_year = strtotime(date("Y",time())."-12"."-31"); //本年结束
        $oneYearOrder = Order::find()
        ->where(['<','create_time',$end_year])//明天零点时间戳
        ->where(['>','create_time',$begin_year]);//今天零点时间戳
        $yearcount = $oneYearOrder->count();
        $data.= $yearcount;
        
        return $data;
    }
    
    public function actionMigration(){
        $migration = new MigrationLogic();
        echo $migration->m_member();
    }
}
