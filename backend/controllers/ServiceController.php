<?php

namespace backend\controllers;

use Yii;
use common\models\Service;
use common\models\ServiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\logic\OrderRefundLogic;

/**
 * ServiceController implements the CRUD actions for Service model.
 */
class ServiceController extends Controller
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

    public function actions()
    {
        return [
                'switcher' => [
                        'class' => 'backend\widgets\grid\SwitcherAction'
                ]
        ];
    }
    /**
     * Lists all Service models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Service model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        
        $model=$this->findModel($id);
        if($model['type']==1){
            return $this->render('refund', [
                 'model' => $model
            ]);
        }else{
            return $this->render('view', [
                    'model' => $model
            ]);
        }
    }

    
    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
/*     public function actionUpdate($id)
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
    } */

    /**
     * 退货或维修完成
     * @param int $id
     * @return \yii\web\Response|string
     */
    public function actionExchange($id)
    {
        $model = $this->findModel($id);
        $model->load(Yii::$app->request->post());
        $model->user_id=yii::$app->user->id;
        $model->status=1;
        $model->receive_status=1;
        if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    /**
     * 完成退款退货
     */
    public function actionRefund($id){

        $post=Yii::$app->request->post();
        $logic=new OrderRefundLogic();
        $uid=yii::$app->user->id;
        $flag= $logic->refundSku($id, $post, $uid);
        
        if ($flag['status']==1) {
         
            Yii::$app->session->setFlash('success', '编辑成功');
            return $this->redirect(['view','id'=>$id]);
        } else {
          
            Yii::$app->session->setFlash('error',$flag['msg']);
            return $this->redirect(['view','id'=>$id]);
        }
      
        
        
    }


    /**
     * Finds the Service model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Service the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Service::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
