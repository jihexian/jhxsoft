<?php

namespace backend\controllers;

use Yii;
use common\models\Member;
use common\models\ShopApply;
use common\models\ShopApplySearch;
use backend\common\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\filters\VerbFilter;
use common\models\Shop;
use yii\helpers\ArrayHelper;
use common\models\ProductCategory;
use common\helpers\Util;
use common\logic\SmsLogic;
use common\models\ShopUser;
/**
 * ShopApplyController implements the CRUD actions for ShopApply model.
 */
class ShopApplyController extends Controller
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
                    
                ],
            ],
        ];
    }

    /**
     * Lists all ShopApply models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShopApplySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ShopApply model.
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
     * Creates a new ShopApply model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//     public function actionCreate()
//     {
//         $model = new ShopApply();

//         if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['view', 'id' => $model->id]);
//         } else {
//             return $this->render('create', [
//                 'model' => $model,
//             ]);
//         }
//     }

    /**
     * Updates an existing ShopApply model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
//     public function actionUpdate($id)
//     {
//         $model = $this->findModel($id);

//         if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['view', 'id' => $model->id]);
//         } else {
//             return $this->render('update', [
//                 'model' => $model,
//             ]);
//         }
//     }

    /**
     * Deletes an existing ShopApply model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//     public function actionDelete($id)
//     {
//         $this->findModel($id)->delete();

//         return $this->redirect(['index']);
//     }

    /**
     * Finds the ShopApply model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShopApply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ShopApply::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionApprove(){
    	$status = \Yii::$app->request->get('status');
    	$id = \Yii::$app->request->get('id');
     	$shopApply = $this->findModel($id);
     	if ($shopApply->status!=0){
     	    Yii::$app->session->setFlash('error', '操作失败，该店铺不是未审核状态');
     	}else{
     	    $shopApply->status=$status;
     	    if ($status==1){
                $this->createShop($shopApply);
     	    }else{
     	        $shopApply->save();
     	        Yii::$app->session->setFlash('success', '操作成功，已拒绝申请！');
     	    }
     	    
     	}
     	return $this->redirect('index');	
    }
    
    private function  createShop($shopApply){
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //添加店铺
            $shop = new Shop();
            $shop->loadDefaultValues();
            $shop->name = $shopApply->name;
            $shop->license = $shopApply->license;
            $shop->idcard = $shopApply->idcard;
            $shop->type = $shopApply->type;
            $shop->member_id = $shopApply->member_id;
            $shop->address = $shopApply->address;
            $shop->status=1;
            $shop->mobile=$shopApply->mobile;
            $shop->logo=Yii::$app->params['defaultImg']['default'];
            $shopFlag = $shop->save();
            $shopApply->status=1;
            //添加店铺初始分类等基础信息
            $productCategory = new ProductCategory();
            $productCategory->loadDefaultValues();
            $productCategory->cat_name = '默认分类';
            $productCategory->shop_id = $shop->id;
            $productCategory->parent_id=0;
            $productCategory->sort=0;
            $productCategory->status=1;
            $productCategory->is_system=1;
            $productCategoryFlag = $productCategory->save();
            //修改申请状态
            $shopApplyFlag =$shopApply->save();
            
          //修改用户手机及密码
            /* $member= Member::findone(['id'=>$shopApply->member_id]);
            if($member['mobile']!=$shopApply->mobile||empty($member['mobile'])){
                 $member->mobile=$shopApply->mobile;
            } 
          
            $randnum='';
            for($i=0;$i<6;$i++){
                $randnum.=rand(0,9);
            }
            $member->password=Util::encrypt($randnum);
            $member->save();
            if($member->hasErrors()){
                $transaction->rollBack();
              return ['status'=>0,'msg'=> json_encode(current($member->getFirstErrors()))];
              
            }      
            $data=array();
            $data['code'] = $randnum;
            $data['name'] = $shopApply->mobile;
            $smsLogic=new SmsLogic();
            $templateCode='SMS_173425850';
            $templateParam = $smsLogic->getTempParams(4, $data);
            $result = $smsLogic->sendSms($shopApply->mobile, $templateCode, $templateParam,'几何线',1);
            if($result['status']!=1){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>$result['msg']];
            } */
            $user=new ShopUser();
            $user->setScenario('create');
            $user->username=$shopApply->mobile;
            $user->mobile=$shopApply->mobile;
            $user->password_hash=$shopApply->password_hash;
            $user->save();
            if($user->hasErrors()){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>current($user->getFirstErrors)];
            }

            if($shopFlag&&$shopApplyFlag&&$productCategoryFlag){
                $transaction->commit();
                return  Yii::$app->session->setFlash('success', '审核成功！');
            }else{
                $transaction->rollBack();
                return Yii::$app->session->setFlash('error', current(ArrayHelper::merge($shop->getErrors(), $shopApply->getErrors(),$productCategory->getErrors())));
            }
            
        } catch (Exception $e) {
            $transaction->rollBack();
            return  Yii::$app->session->setFlash('error', '系统错误，操作失败！');
        }
    }
}
