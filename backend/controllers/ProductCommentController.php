<?php

namespace backend\controllers;

use Yii;
use common\models\ProductComment;
use common\models\ProductCommentSearch;
use backend\common\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/**
 * ProductCommentController implements the CRUD actions for ProductComment model.
 */
class ProductCommentController extends Controller
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

    public function actions(){
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
     * Lists all ProductComment models.
     * @return mixed
     */
    public function actionIndex()
    {
    	Url::remember();
        $searchModel = new ProductCommentSearch();
        $searchModel->pid=0;//只获取用户评价
        Yii::$app->request->setQueryParams(ArrayHelper::merge(["shop_id"=>Yii::$app->session->get('shop_id')], Yii::$app->request->queryParams));
       	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductComment model.
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
     * 管理员回复评价
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($comment_id)
    {    	
        $model = new ProductComment();		
        $model->loadDefaultValues();
        $model->pid=$comment_id;
        if ($model->load(Yii::$app->request->post())) {
        	$modelProductComment = ProductComment::findOne($comment_id);
        	$model->reply_member_id = $modelProductComment->member_id;
        	$model->order_sku_id = $modelProductComment->order_sku_id;
        	$model->goods_id = $modelProductComment->goods_id;
        	$model->order_no = $modelProductComment->order_no;
        	$model->reply_status = 1;
        	$userId = Yii::$app->user->identity->getId();
        	$model->user_id = $userId;
        	if ($model->save()){
        		$modelProductComment->reply_status=1;
        		$modelProductComment->save(true);
        		return $this->redirect(['index']);
        	}else{
        		var_dump($model->getErrors());
        	}
        	
        
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProductComment model.
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
     * Deletes an existing ProductComment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	$model = $this->findModel($id);
    	$transaction = Yii::$app->db->beginTransaction();
    	try {
    		$subFlag = true;
    		$subComments = $model->find()->where(array('pid'=>$model->comment_id))->all();
    		if (count($subComments)>0){
    			foreach ($subComments as $comment){
    				$subFlag = $comment->delete();
    				if ($subFlag==false){
    					break;
    				}
    			}
    		}    		
    		$flag = $model->delete();
    		if (!$flag||!$subFlag){
    			$transaction->rollBack();
    			Yii::$app->session->setFlash('error', '操作失败');
    		}else{    			
    			$transaction->commit();
    			Yii::$app->session->setFlash('success', '操作成功');
    		}
    		
    	} catch (Exception $e) {
    		$transaction->rollBack();
    		Yii::$app->session->setFlash('error', '操作失败');
    	}
    	return $this->redirect(['index']);
    }

    /**
     * Finds the ProductComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = ProductComment::find()->where(['comment_id'=>$id,'shop_id'=>Yii::$app->session->get('shop_id')])->one())!== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
