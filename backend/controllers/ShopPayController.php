<?php
/**
 *
 * Author wsyone wsyone@faxmail.com
 * Time:2019年8月19日下午4:09:19
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace backend\controllers;
use Yii;
use common\models\ShopPaySearch;
use backend\common\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\ShopPay;

/**
 * ShopPayController implements the CRUD actions for ShopPay model.
 */
class ShopPayController extends Controller
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
            'ajax-update-field' => [
                'class' => 'common\\actions\\AjaxUpdateFieldAction',
                'allowFields' => ['status'],
                'findModel' => [$this, 'findModel']
            ],
            'switcher' => [
                'class' => 'backend\widgets\grid\SwitcherAction'
            ]
        ];
    }
    
    /**
     * Lists all ShopPay models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopPaySearch();
        Yii::$app->request->setQueryParams(ArrayHelper::merge(["shop_id"=>Yii::$app->session->get('shop_id')], Yii::$app->request->queryParams));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Displays a single ShopPay model.
     * @param integer $id
     * @return mixed
     */
    /*     public function actionView($id)
     {
     return $this->render('view', [
     'model' => $this->findModel($id),
     ]);
     } */
    
    /**
     * Creates a new ShopPay model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ShopPay();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Updates an existing ShopPay model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Deletes an existing ShopPay model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        
        return $this->redirect(['index']);
    }
    
    /**
     * Finds the ShopPay model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShopPayController the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public  function findModel($id)
    {
        if (($model = ShopPay::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
