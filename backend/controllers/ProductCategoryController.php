<?php

namespace backend\controllers;

use Yii;
use common\models\ProductCategory;
use yii\data\ActiveDataProvider;
use backend\common\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * ProductCategoryController implements the CRUD actions for ProductCategory model.
 */
class ProductCategoryController extends Controller
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
    			'position' => [
    					'class' => 'backend\\actions\\Position',
    					'returnUrl' => Url::current()
    			],'ajax-update-field' => [
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
     * Lists all ProductCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ProductCategory::find()->where(['shop_id'=>Yii::$app->session->get('shop_id')])->orderBy(['sort'=> SORT_ASC]),
        	'pagination' => [
        		'pageSize' =>200,
        	],
        ]);
		
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        	'pagination' => false
        ]);
    }

    /**
     * Displays a single ProductCategory model.
     * @param integer $id
     * @return mixed
     */
//     public function actionView($id)
//     {
//         return $this->render('view', [
//             'model' => $this->findModel($id),
//         ]);
//     }

    /**
     * Creates a new ProductCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $category_id
     * @return mixed
     */
    public function actionCreate($category_id = 0)
    {
        $model = new ProductCategory();
        $model->loadDefaultValues();
        $model->parent_id = $category_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProductCategory model.
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
     * Deletes an existing ProductCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {    	
    	$model = $this->findModel($id);
        $flag = $model->delete();
		if (!$flag){
			Yii::$app->session->setFlash('error', $model->getErrors('category_id'));
		}else{
			Yii::$app->session->setFlash('success', '操作成功');
		}
        return $this->redirect(['index']);
    }

    /**
     * Finds the ProductCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = ProductCategory::find()->where(['category_id'=>$id,'shop_id'=>Yii::$app->session->get('shop_id')])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
}
