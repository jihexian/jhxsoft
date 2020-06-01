<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com 
 * Time:2019年5月31日
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace backend\controllers;

use Yii;
use common\models\OrderRefundDoc;
use common\models\OrderRefundDocSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use api\modules\v1\models\Order;
use common\logic\OrderLogic;
use yii\helpers\Url;
use Prophecy\Util\StringUtil;
use common\logic\OrderRefundLogic;
use common;
/**
 * OrderRefundDocController implements the CRUD actions for OrderRefundDoc model.
 */
class OrderRefundDocController extends Controller
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
     * Lists all OrderRefundDoc models.
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember();
        $searchModel = new OrderRefundDocSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrderRefundDoc model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        Url::remember();
        $refund=OrderRefundDoc::findOne($id);  
        $refund->scenario='update';
        $order=Order::findOne($refund['order_id']);

        return $this->render('view', [
            'model' =>$refund,
             'order'=>$order,

        ]);
    }
    
    public function actionSave(){
        if(yii::$app->request->isPost){
            $post=yii::$app->request->post();
            $id=yii::$app->request->get('id');
          
        
            $rf=new OrderRefundLogic();
            if($post['OrderRefundDoc']['check_status']==1){   //同意退款
                //执行退款
                if(isset($post['OrderRefundDoc']['sku_id'])){
                    $pp=$rf->refundSku($id,$post);
                }else{
                    $pp=$rf->refund($id,$post);
                }
                if($pp['status']==1){
                    Yii::$app->session->setFlash('success', '操作成功');
                }else{
                    Yii::$app->session->setFlash('error', $pp['msg']);
                }
                return $this->goBack();
            }else{                          //拒绝退款
                $su=$rf->refuse($id,$post['OrderRefundDoc']['message']);
                if($su['status']==1){
                    Yii::$app->session->setFlash('success', '操作成功');
                }else{
                    Yii::$app->session->setFlash('error', '操作失败');
                }
                return $this->goBack();
            }
        }
    }

    /**
     * Creates a new OrderRefundDoc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrderRefundDoc();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OrderRefundDoc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
   
    

 

    /**
     * Finds the OrderRefundDoc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return OrderRefundDoc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrderRefundDoc::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
}
