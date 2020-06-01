<?php

namespace backend\controllers;
use Yii;
use common\models\Shop;
use common\models\ShopUser;
use common\models\ShopSearch;
use common\models\ShopWithdraw;
use common\models\Order;
use common\models\OrderSearch;
use common\models\ShopAccoutLogSearch;
use common\models\ShopWithdrawSearch;
use backend\common\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\models\ShopCommissionLog;
use common\models\ShopUserSearch;
/**
 * ShopController implements the CRUD actions for Shop model.
 */
class ShopController extends Controller
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
                   // 'approve' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
    	return [
    			'ajax-update-field' => [
    					'class' => 'common\\actions\\AjaxUpdateFieldAction',
    					'allowFields' => ['status'],
    					'findModel' => [$this, 'findModel']
    			],
    			'switcher' => [
    					'class' => 'backend\widgets\grid\SwitcherAction'
    			],
    	    'position' => [
    	        'class' => 'backend\\actions\\Position',
    	        'returnUrl' => Url::current()
    	    ]
    	];
    }
    /**
     * Lists all Shop models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
   
    public function actionApply(){
        $searchModel = new ShopSearch();
        $data=array();
        $data['apply_status']=0;
        $dataProvider = $searchModel->search($data);
        return $this->render('apply', [
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Shop model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
 
    /**
     * 驳回申请
     * @return \yii\web\Response|string
     */
    public function actionRefuse($id){
        $model = $this->findModel($id);
       $model->setScenario('refuse');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', '已驳回申请');
            return $this->redirect(['apply']);
        } else {
            return $this->render('refuse', [
                'model' => $model,
            ]);
        }
    }
  
   
    public function actionCreate()
        {
            $model = new Shop();
            $model->setScenario('create');
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
               return $this->redirect(['view', 'id' => $model->id]);
             } else {
               $model->business_hours='9:00-12:00  15:00-24:00'; 
               return $this->render('create', [
                        'model' => $model,
                   ]);
            }
        }

    /**
     * Updates an existing Shop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

     public function actionUpdate($id=1)
    {
        $model = $this->findModel($id);

        if(yii::$app->request->isPost){
            $model->load(Yii::$app->request->post());
            if($model->map){
                $str=explode(',', $model->map);
                $model->lat=isset($str[0])?$str[0]:'';
                $model->lng=isset($str[1])?$str[1]:'';
            }  
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }else{
            return $this->render('update', [
                'model' => $model,
            ]);
        }
      
    } 

    /**
     * 店铺资金概况
     * @return string
     */
    public function actionRecord($id=1){
        $model = $this->findModel($id);
        //总营业额
        $total=Order::find()->where(['payment_status'=>1,'status'=>[2,3,4,5],'shop_id'=>$id])->sum('pay_amount');
        //待结算
        $waiting=Order::find()->where(['payment_status'=>1,'status'=>[2,3,4],'shop_id'=>$id])->sum('pay_amount');
        //店铺提现
        $finish=ShopWithdraw::find()->where(['shop_id'=>$id,'status'=>1])->sum('money');
        $ready=ShopWithdraw::find()->where(['shop_id'=>$id,'status'=>0])->sum('money');
        //平台服务费
        $service=ShopCommissionLog::find()->where(['shop_id'=>$id])->sum('percentage');
        //扶贫资金
        //店铺流水
        $shopAccout=new ShopAccoutLogSearch();
        $data=yii::$app->request->post();
        $data['ShopAccoutLogSearch']['shop_id']=$id;
        $dataProvider=$shopAccout->search($data);
        return $this->render('record', [
                'total'=>$total,
                'waiting'=>$waiting,
                'model' => $model,
                'finish'=>$finish,
                'ready'=>$ready,
                'service'=>$service,
                'shopAccout'=>$shopAccout,
                'dataProvider'=>$dataProvider
        ]);
    }
    /**
     * Finds the Shop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Shop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Shop::findOne(['id'=>$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

   /**
    * 店铺资产
    */
    public function actionAccout(){
        $shop_id=yii::$app->session->get('shop_id');
        $model = $this->findModel($shop_id);
        $total=Order::find()->where(['payment_status'=>1,'status'=>[2,3,4,5],'shop_id'=>$shop_id])->sum('order_price');
        //待提现
        $ready=Order::find()->where(['payment_status'=>1,'status'=>[2,3,4],'shop_id'=>$shop_id])->sum('order_price');
        //已提现
        $withdraw=ShopWithdraw::find()->where(['status'=>1,'shop_id'=>$shop_id])->sum('money');
        //订单记录列表
        $modelSearch = new OrderSearch();
        $order=$modelSearch->search(['payment_status'=>1,'shop_id'=>$shop_id]);
        //提现记录列表
        $withdrawSearch=new ShopWithdrawSearch();
        $withdrawList=$withdrawSearch->search(['shop_id'=>$shop_id]);
        $shopAccountSearch=new  ShopAccoutLogSearch();
        $shopAccountLog=$shopAccountSearch->search(['shop_id'=>$shop_id]);
        return $this->render('account', [
            'model' => $model,
            'total'=>empty($total)?'0.00':$total,
            'ready'=>empty($ready)?'0.00':$ready,
            'withdraw'=>empty($withdraw)?'0.00':$withdraw,
            'withdrawList'=>$withdrawList,
            'order'=>$order,
            'shopAccountLog'=>$shopAccountLog
        ]);
    }
    public function actionApprove($id){
        $model = $this->findModel($id);
        $model->apply_status=1;
        $model->status=1;
        if($model->save()){
            Yii::$app->session->setFlash('success', '审核成功');
            return $this->redirect(['index']);
        }else{
            Yii::$app->session->setFlash('error', '审核失败');
            return $this->redirect(['apply']);
        }
    }
    

 
}
