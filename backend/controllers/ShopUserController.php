<?php
namespace backend\controllers;
use common\models\Shop;
use common\models\ShopUserSearch;
use Yii;
use common\models\ShopUser;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShopUserController implements the CRUD actions for ShopUser model.
 */
class ShopUserController extends Controller
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
     * Lists all ShopUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopUser model.
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
     * Updates an existing ShopUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
     * Deletes an existing ShopUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
      return $this->redirect(['index']);
      //return $this->goBack();
    }
    
    /**
     * Deletes an existing ShopUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteUser($id,$shop_id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['shop-user/manager','id'=>$shop_id]);
        //return $this->goBack();
    }
    
    /**
     * 重置密码
     */
    public function actionResetPassword($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'resetPassword';
        if($model->load(Yii::$app->request->post()) && $model->save()){
          $this->redirect('index');
        }
        return $this->render('password', [
            'model' => $model
        ]);
    }

    /**
     * Finds the ShopUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShopUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($model = ShopUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    public function actionManager($id){
        $model = new ShopUser();
        $model->setScenario('register');
        $shop= Shop::findOne(['id'=>$id]);
        $searchModel = new ShopUserSearch();
        $data['shop_id']=$id;
        $dataProvider = $searchModel->search($data);
        
        if ($model->load(Yii::$app->request->post())&&$model->create()) {
            Yii::$app->getSession()->setFlash('success','操作成功');
            return $this->redirect(['shop-user/manager','id'=>$id]);
        } else {
            return $this->render('manager', [
                'model' => $model,
                'shop'=>$shop,
                'dataProvider' => $dataProvider
            ]);
        }
    }
}
