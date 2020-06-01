<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com
 * Time:2018年12月18日 下午6:26:47
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace backend\controllers;

use Yii;
use common\models\Order;
use common\models\OrderSearch;
use common\models\OrderLog;
use common\models\OrderDeliveryDoc;
use backend\common\controllers\Controller;
use yii\base\Response;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\logic\OrderLogic;
use common\models\ShippingCompany;
use common\helpers\Tools;
use common\models\ShopCategory;
use common\logic\ExcelLogic;
use function GuzzleHttp\Promise\queue;
use common\logic\OrderRefundLogic;


/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{

    /**
     * @inheritdoc
     */
     public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    
                ],
            ],
        ];
    } 


    /**
      * 全部正常订单
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember();
        $searchModel = new OrderSearch();
     
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionExport(){

       $data=yii::$app->request->get();
        $query=order::find();
        if(isset($data['create_time'])){
            $create_time=$data['create_time'];
            if(!empty($create_time)) {
                $query->andFilterCompare('create_time', strtotime(explode(' - ', $create_time)[0]), '>=');//起始时间
                $query->andFilterCompare('create_time', (strtotime(explode(' - ', $create_time)[1]) + 86400), '<');//结束时间
            }
        }
        if(isset($data['status'])&&!empty($data['status'])){ 
                $query->andWhere(['status'=>$data['status']]);
        }
        if(isset($data['order_no'])&&!empty($data['order_no'])){
            $query->andWhere(['order_no'=>$data['full_name']]);
        }
        if(isset($data['payment_no'])&&!empty($data['payment_no'])){
            $query->andWhere(['payment_no'=>$data['full_name']]);
        }
        if(isset($data['full_name'])&&!empty($data['full_name'])){
            $query->andFilterWhere(['like', 'full_name', $data['full_name']]);
        } 
        $data=$query->all();
        $order=array();
        foreach ($data as $key=>$vo){
            $order[$key]['id']=$vo['id'];
            $order[$key]['order_no']=$vo['order_no'];
            $order[$key]['create_time']=date('Y-m-d H:i:s',$vo['create_time']);
            $order[$key]['full_name']=$vo['full_name'];
            $order[$key]['address']=$vo['prov'].$vo['city'].$vo['area'].$vo['address'];
            $order[$key]['tel']=$vo['tel']; 
            $order[$key]['order_price']=$vo['order_price'];   
            $order[$key]['pay_amount']=$vo['pay_amount'];
            $order[$key]['payment_status']=$vo['payment_status']==1?'已支付':'待支付';
            $order[$key]['payment_name']=$vo['payment_name'];
            $order[$key]['delivery_status']=Tools::shipping_status($vo['delivery_status']);
            $order[$key]['status']=Tools::get_status($vo['status']);
            $str='';
            if(!empty($vo['orderSku'])){
            foreach($vo['orderSku'] as $vv){
                $str.='商品名称：'.$vv['goods_name'].' 规格：'.$vv['sku_value'].' 单价：'.$vv['sku_sell_price_real'].' 数量'.$vv['num'].'  ';
            }}
            $order[$key]['extend']=$str;   
            $order[$key]['m_desc']=$vo['m_desc']; 
        }
        $excel=new ExcelLogic();
        $head=['id'=>'序号','order_no'=>'订单号','create_time'=>'下单时间','full_name'=>'收货人','address'=>'收货地址','tel'=>'电话号码','order_price'=>'订单总价','pay_amount'=>'实际支付','payment_status'=>'支付状态','payment_name'=>'支付方式','delivery_status'=>'发货状态','status'=>'订单状态','extend'=>'商品信息','m_desc'=>'用户留言'];
        $file='订单数据'.date('YmdHis').'.xlsx';
        $excel->export($file,$head,$order); 

    }

    /**
     * @desc 发货订单
     * @return string
     */
    public function actionShipping(){
        Url::remember();
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'shipping');
        return $this->render('shipping', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
 
    }
    
    /**
     * @desc 发货单详情
     * @param int $id
     * @throws NotFoundHttpException
     * @return \yii\web\Response|string
     */
    public function actionDelivery( $id){

        $data= $this->findModel($id);
        $delivery=new OrderDeliveryDoc();
       
        //获取快递公司列表
        $items = ShippingCompany::find()
        ->select(['company_name'])
        ->indexBy('code')
        ->orderBy('sort asc,id asc')
        ->column();
        $dataProvider = new ActiveDataProvider([
            'query' => OrderDeliveryDoc::find()->where(['order_id'=>$id])->joinWith('shippingCompany'),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);
        if(yii::$app->request->isPost){
            $sku=yii::$app->request->post('sku');
            if(empty($sku)){
                throw new NotFoundHttpException('必须选择一个商品');
            }
            $su=yii::$app->request->post();
            $su['OrderDeliveryDoc']['admin_user']=yii::$app->user->id;
            $su['OrderDeliveryDoc']['m_id']=$data['m_id'];
            $su['OrderDeliveryDoc']['order_id']=$data['id'];
            $su['OrderDeliveryDoc']['shop_id']=$data['shop_id'];
            $shipping=ShippingCompany::find()->where(['code'=>$su['OrderDeliveryDoc']['shipping_code']])->one();
            $su['OrderDeliveryDoc']['shipping_name']=$shipping['company_name'];
       
            $logic= new OrderLogic();
            if($logic->checkSend($data['status'], $data['delivery_status'])){
                
                $msg=$logic->OrderSend($id, $su, $sku);
                if($msg['status']==1){
                    Yii::$app->session->setFlash('success', '操作成功');
                    
                }else{
                    Yii::$app->session->setFlash('error',$msg['msg']);
                }
                return $this->goBack();
                
            }else{
                Yii::$app->session->setFlash('error','操作失败');
                return $this->goBack();
            }
            
        }
        
        return $this->render('delivery', [
            'model' =>$data,
            'delivery'=> $delivery,
            'items'=>$items,
            'dataProvider' => $dataProvider,
        ]);
        
        
    }

    /**
     * Displays a single Order model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $data= $this->findModel($id);
        //获取平台费率
        $row=ShopCategory::find()->where(['id'=>$data['shop']['category_id']])->addSelect('percent')->one();
       
        $query=OrderLog::find()->where(['order_no'=>$data['order_no']]);
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                        'pageSize' => 10,
                ],
        ]);
        
        return $this->render('view', [
                'model' =>$data,
            'percent'=>$row['percent'],
                'dataProvider' => $dataProvider,
        ]);
        
    }

     /**
     * print
     * @param string $id
     * @return mixed
     */
    public function actionPrint($id)
    {
       
        return $this->render('print', [
            'model' => $this->findModel($id),
        ]);
    }
    
 

     /**
     * change-price
     * @param string $id
     * @return mixed
     */
    public function actionChangePrice()
    {
        $id=yii::$app->request->get('id');      
        $model = $this->findModel($id);
        $model->scenario = 'update';
        if(Yii::$app->request->isPost){
           
            if($model['payment_status']==1){
                throw new NotFoundHttpException('订单已支付，不能修改价格');
            }
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //第一步，修改order_no、discount_price、delivery_price
                $data=Yii::$app->request->post();
                $orderLogic = new OrderLogic();
                $model->load($data);
                $model->order_no=Tools::get_order_no();//更新order_no
                $model->save();
                if($model->hasErrors()){
                    $error=array_values($model->getFirstErrors())[0];
                    $transaction->rollBack();
                    throw new \Exception($error);//抛出异常
                }
                //第二步计算总价
                $price = $this->findModel($id);
                $price->pay_amount=$orderLogic->getPayAmount($price);
                $price->save();
                if($price->hasErrors()){
                    $error=array_values($price->getFirstErrors())[0];
                    $transaction->rollBack();
                    throw new \Exception($error);//抛出异常
                }
                //生成操作记录表
                $logic=new OrderLogic();
                $flag= $logic->saveLog($model['order_no'],$model->status, $model->delivery_status, $model->payment_status, '', '订单改价', $model->shop_id);
                if($flag['status']!=1){
                   return  $transaction->rollBack();
                }
               $transaction->commit();   
                Yii::$app->session->setFlash('success', '发布成功');
                return $this->redirect(['order/view','id'=>$id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }else{
            return $this->render('change_price', [
                'model' => $model,
            ]);
            
        }

    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
         
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->softDelete();
        Yii::$app->session->setFlash('success', '操作成功');
        return $this->goBack();
    }

    public function actionChart(){
        return $this->render('chart');

    }
  /**
   * 后台设置支付
   * @param integer $id
   * @return boolean|void|number[]|string[]
   */
    public function actionSetPay($id){
        $model= $this->findModel($id);
        $logic=new OrderLogic();
        $flag=$logic->SetPay($model);
        if($flag['status']==1){
            Yii::$app->session->setFlash('success', '操作成功');
        }else{
            Yii::$app->session->setFlash('error', '操作失败');
        }
        return $this->goBack();
    }
   
    /**
     * 设置收货成功
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionReceive($id){
        $model= $this->findModel($id);
        $logic=new OrderLogic();
        $flag=$logic->receive($model);
        if($flag['status']==1){
            Yii::$app->session->setFlash('success', '操作成功');
        }else{
            Yii::$app->session->setFlash('error', '操作失败');
        }
        return $this->goBack();
    }
    
    public function actionFinish($id){
        $model= $this->findModel($id);
        $logic=new OrderLogic();
        $flag=$logic->oneFinish($model);
        if($flag['status']==1){
            Yii::$app->session->setFlash('success', '操作成功');
        }else{
            Yii::$app->session->setFlash('error', '操作失败');
        }
        return $this->goBack();
    }
    
    public function  actionOneCancel($id){
        $model= $this->findModel($id);
        $logic=new OrderRefundLogic();
        $flag=$logic->oneCancel($model['id'], '');
        if($flag['status']==1){
            Yii::$app->session->setFlash('success', '操作成功');
        }else{
              Yii::$app->session->setFlash('error', $flag['msg']);
        }
        return $this->goBack(); 
    } 

    /**
     * Finds the Nav model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @Nav the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model =Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



}
