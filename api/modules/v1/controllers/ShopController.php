<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com
 * Time:2019年9月15日上午11:01:01
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace api\modules\v1\controllers;
use yii;
use api\common\controllers\Controller;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use common\models\ShopSearch;
use yii\helpers\Json;
use common\models\CollectionShop;
use common\models\Shop;
use common\models\ShopCategory;
use common\models\RegisterShop;
use common\models\ShopUser;

class ShopController extends Controller
{
	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
				[
						'class' => QueryParamAuth::className(),
						'tokenParam' => 'token',
						'optional' => [
							'index',
						]
				]
		]);
	}
	/**
	 * 商铺列表
	 * @return \yii\data\ActiveDataProvider
	 */
    public function actionIndex()
    {
    	$data=Yii::$app->request->post();
        $data['status']=1;
        $data['apply_status']=1;
    	$searchModel = new ShopSearch();
        $dataProvider = $searchModel->search($data);  
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $dataProvider->setSort($sort);
       return $dataProvider;
    }
    /**
     * 行业分类
     * @return number[]|array[]|\yii\db\ActiveRecord[][]
     */
    public function  actionShopCategory(){
        $shopcategory=ShopCategory::find()->where(['status'=>1])->all();
        return ['status'=>1,'items'=>$shopcategory];
    }
    /**
     * 申请店铺
     * @return number[]|string[]|number[]|mixed[]
     */
    public function actionApply(){
         $data=yii::$app->request->post();
         $user=ShopUser::findOne(['mobile'=>$data['mobile'],'m_id'=>yii::$app->user->id]);
         $truncation=yii::$app->db->beginTransaction();
         try {
             $data['m_id']=yii::$app->user->id;
             $model=new RegisterShop();
             $model->setScenario('edit');
             if(!empty($user)){   
                 $shop=Shop::findOne(['id'=>$user['shop_id']]);
                 if(!$shop||$user['status']==0){
                     $truncation->rollBack();
                     return ['status'=>0,'msg'=>'错误'];
                 }
                 if(!$model->load($data,'')||!$model->edit($user['shop_id'],$user['id'])){
                     $truncation->rollBack();
                     return ['status'=>0,'msg'=>current($model->getFirstErrors())];
                 }
                
             }else{
                 $register=new RegisterShop();
                 $register->setScenario('create');
                 if(!$register->load($data,'')||!$register->signup()){
                     $truncation->rollBack();
                     return ['status'=>0,'msg'=>current($register->getFirstErrors())];
                 }
             }
             
             $truncation->commit();
             return ['status'=>1,'msg'=>'申请成功'];
             
         } catch (\Exception $e) {
             $truncation->rollBack();
             return ['status'=>0,'msg'=>'操作失败'];
         }     
      
    }
   /**
    * 店铺申请状态
    * @return number[]|\common\models\Shop[]|NULL[]
    */
    public function actionApplyStatus(){
   
        $user=ShopUser::findOne(['m_id'=>yii::$app->user->id]);
        $shop=$user['shop'];
        if(!empty($shop)){
            return ['status'=>1,'item'=>$shop];
        }else{
           return ['status'=>0,'msg'=>'暂无店铺'];
        }
    }
 
    public function actionHome(){
        $id=yii::$app->request->post('id');
        $member_id=yii::$app->user->id;
        $shop=Shop::findOne(['id'=>$id]);
        $collection = CollectionShop::find()->where(array('shop_id'=>$id,'member_id'=>$member_id))->one();
        if (!empty($collection)){
            $like=1;
        }else{
            $like=0;
        }
        return ['shop'=>$shop,'like'=>$like];
    }
   /**
    * 店铺收藏接口
    * @param un
    * @return number[]|string[]|string
    */
    public function actionLike(){
        $id=yii::$app->request->post('id');
        $member_id=yii::$app->user->id;
        $collection = CollectionShop::find()->where(array('shop_id'=>$id,'member_id'=>$member_id))->one();
        if (!empty($collection)){
            return ['status'=>0,'msg'=>'该店铺已添加到收藏列表，请勿重复添加'];
        }
        $newcollection = new CollectionShop();
        $newcollection->member_id = $member_id;
        $newcollection->shop_id = $id;
        $flag = $newcollection->save();
        if (!$flag){
            return ['status'=>0,'msg'=>'收藏失败'];
        }else{
            return ['status'=>1,'msg'=>'收藏成功'];
        }
    }

}
