<?php

namespace backend\controllers;

use Yii;
use common\models\Store;
use common\models\StoreSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Region;
use common\models\StoreRegion;

/**
 * StoreController implements the CRUD actions for Store model.
 */
class StoreController extends Controller
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
     * Lists all Store models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StoreSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Store model.
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
     * Creates a new Store model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Store();
        if ($model->load(yii::$app->request->post())) {
          if ($model->validate()) {
           $transaction = Yii::$app->db->beginTransaction();
           try {
               $model->save();
               if ($model->hasErrors()) {
                   throw new \Exception(current($model->getFirstErrors()));
               }
               $ids=explode(',',$model->regions);
               $regions=Region::find()->where(['id'=>$ids,'level'=>3])->select(['id'])->all();
               $relationship=new StoreRegion();
               foreach($regions as $vo){
                   $relation=clone $relationship;
                   $relation->store_id=$model->id;
                   $relation->region_id=$vo->id;
                   $relation->save();
                   if ($relation->hasErrors()) {
                       throw new \Exception(current($relation->getFirstErrors()));
                   }
               }
               $transaction->commit();
               Yii::$app->session->setFlash('success', '操作成功');
               return $this->refresh();
           } catch (\Exception $e) {
               $transaction->rollBack();
               Yii::$app->session->setFlash('error', $e->getMessage());
           } 
          }
        }
            return $this->render('create', [
                'model' => $model,
            ]);
        
    }

    /**
     * Updates an existing Store model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(yii::$app->request->post())) {
            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$model->save(false)) {
                        $transaction->rollBack();
                        throw new \Exception(current($model->getFirstErrors()));
                    }
                    $ids=explode(',',$model->regions);
                    $regions=Region::find()->where(['id'=>$ids,'level'=>3])->select(['id'])->all();
                    //删除StoreRegion表信息
                    StoreRegion::deleteAll(['store_id'=>$id]);
                    //再新增
                    $relationship=new StoreRegion();
                    foreach($regions as $vo){
                        $relation=clone $relationship;
                        $relation->store_id=$model->id;
                        $relation->region_id=$vo->id;
                        $relation->save();
                        if (!$relation->save(false)) {
                            $transaction->rollBack();
                            throw new \Exception(current($relation->getFirstErrors()));
                        }
                    }
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', '操作成功');
                    return $this->refresh();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        }
            return $this->render('update', [
                'model' => $model,
            ]);
       
    }

    /**
     * Deletes an existing Store model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
/*     public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    } */

    /**
     * Finds the Store model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Store the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = Store::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
