<?php

namespace backend\controllers;

use Yii;
use common\models\ShopPay;
use common\models\ShopWithdraw;
use common\models\ShopWithdrawSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\base\Exception;
use common\logic\ShopWithdrawLogic;
use common\models\Shop;
/**
 * ShopWithdrawController implements the CRUD actions for ShopWithdraw model.
 */
class ShopWithdrawController extends Controller
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
                    'refuse'=> ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ShopWithdraw models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopWithdrawSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopWithdraw model.
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
     * Creates a new ShopWithdraw model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new ShopWithdraw();
         $model->setScenario('create');
        $uid=yii::$app->user->id;
        $shop=Shop::find()->where(['id'=>Yii::$app->session->get('shop_id')])->one();
        if (yii::$app->request->isPost) {  
            $post=Yii::$app->request->post();
            if($post['ShopWithdraw']['money']>$shop['money']){
                Yii::$app->session->setFlash('error', '提现金额不能大于店铺余额');
                return $this->redirect(['index']);
            }
            $shop_pay=ShopPay::find()->where(['id'=>$post['ShopWithdraw']])->one();       
            if(empty($shop_pay)){
                Yii::$app->session->setFlash('error', '请先添加提现银行账号');
                return $this->redirect(['index']);
            }
            $shopwithlogic=new ShopWithdrawLogic();
            $flag=$shopwithlogic->apply($shop_pay,$post,$uid);
            if($flag['status']==1){
                Yii::$app->session->setFlash('success', '申请提现成功');
            }else{
                Yii::$app->session->setFlash('error', $flag['msg']);
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                    'shop'=>$shop,
                   'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ShopWithdraw model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
/*      public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->status!=1){
            Yii::$app->session->setFlash('error', '操作失败，该记录尚未通过审核');
            return $this->redirect('index');	
        }
        if (Yii::$app->request->post()){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->status=2;
                $model->pay_time=time();
                $model->load(Yii::$app->request->post());
                $transaction->commit();
                $model->save();
                Yii::$app->session->setFlash('success', '操作成功！');
                return $this->redirect(['view', 'id' => $model->id]);
            }
            catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', '系统错误，操作失败！');
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }  */

   

    /**
     * Finds the ShopWithdraw model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShopWithdraw the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopWithdraw::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    

    /* public function actionRefuse($id,$version=0){
        $shopwithlogic=new ShopWithdrawLogic();
        $flag=$shopwithlogic->fail($id,$version);
        if($flag['status']==1){
            Yii::$app->session->setFlash('success', '操作成功！');
        }else{
            Yii::$app->session->setFlash('error', $flag['msg']);
        }
        return $this->redirect('index');	
    }

    public function actionPass($id){
        $shop_withdraw=ShopWithdraw::findOne(['id'=>$id]);
        $shop_withdraw->setScenario('update');
        if($shop_withdraw['status']!=0){
            Yii::$app->session->setFlash('error', '请不要重复处理！');
        }
        $shop_withdraw->status=1;
        if($shop_withdraw->save()){
            Yii::$app->session->setFlash('success', '操作成功！');
        }else{
            Yii::$app->session->setFlash('error', '操作失败！');
        }
        return $this->redirect('index');	
        
    } */
    
    public function actionApi(){
        $post=yii::$app->request->post();
        $logic=new ShopWithdrawLogic();
        if(!empty($post)){
            switch ($post['status']){
                case 'tranfer':$info=$logic->personTranfer($post['id'],$post['remark']);break;
                case 'auto':$info= $logic->apiTranfer($post['id'],$post['remark']);break;
                case 'refuse':$info=$logic->refuse($post['id'],$post['remark']);break;
                default:$info=$logic->refuse($post['id'],$post['remark']);break;
            }
            return Json::encode($info);   
        }
      
    }
    

}
