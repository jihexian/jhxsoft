<?php

namespace backend\controllers;

use Yii;
use common\models\ProductType;
use yii\data\ActiveDataProvider;
use backend\common\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use common\models\Attribute;

/**
 * ProductTypeController implements the CRUD actions for ProductType model.
 */
class ProductTypeController extends Controller
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
    			],
    	    'position' => [
    	        'class' => 'backend\\actions\\Position',
    	        'returnUrl' => Url::current()
    	    ]
    	];
    }

    /**
     * Lists all ProductType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ProductType::find()->where(['shop_id'=>Yii::$app->session->get('shop_id')])->orderBy('sort asc'),
            'pagination' => [
                'pageSize' =>5000,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        	'pagination' => false
        ]);
    }

    /**
     * Displays a single ProductType model.
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
     * Creates a new ProductType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type_id = 0)
    {
        $model = new ProductType();
        $model->loadDefaultValues();        
        $model->parent_id = $type_id;
        //$modelAttr = new Attribute();
        //$modelAttrs = [new Attribute];
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            	//'modelAttr'=>$modelAttr,	
            ]);
        }
    }

    /**
     * Updates an existing ProductType model.
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
     * Deletes an existing ProductType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $flag = $model->delete();
		if (!$flag){
			Yii::$app->session->setFlash('error', $model->getErrors('type_id'));
		}else{
			Yii::$app->session->setFlash('success', '操作成功');
		}
        return $this->redirect(['index']);
    }

    /**
     * Finds the ProductType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = ProductType::find()->where(array('type_id'=>$id,'shop_id'=>Yii::$app->session->get('shop_id')))->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
