<?php
namespace api\modules\v1\controllers;
use Yii;
use api\common\controllers\Controller;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use common\models\Collection;
use common\models\Product;
use common\models\CollectionSearch;

class CollectionController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => QueryParamAuth::className(),
                'tokenParam' => 'token',
                'optional' => [                    
                ]
            ]
        ]);
    }


    /**
     * 添加收藏
     */
    public function actionAdd()
    {
    	$product_id = Yii::$app->request->post('product_id');
    	$product = Product::findOne($product_id);
    	if (empty($product)) {
    	    return ['status'=>0,'msg'=>'该商品已下架，收藏失败！'];
    	}
    	$member_id = Yii::$app->user->id;
    	$collection = Collection::find()->where(array('product_id'=>$product_id,'member_id'=>$member_id))->one();
    	if (!empty($collection)){
    		return ['status'=>0,'msg'=>'该商品已添加到收藏列表，请勿重复添加'];
    	}
    	$newcollection = new Collection();
    	$newcollection->member_id = $member_id;
    	$newcollection->product_id = $product_id;
    	$flag = $newcollection->save();
    	if (!$flag){
    		return ['status'=>0,'msg'=>$newcollection->errors];
    	}else{
    		//收藏数+1
    		Product::updateAllCounters(array('favorite'=>1),['product_id'=>$product_id]);
    		return ['status'=>1,'msg'=>'操作成功'];
    	}
    	
    }
    /**
     * 收藏列表
     * @return 
     */
    public function actionLists(){
    	$data = Yii::$app->request->post();
    	$member_id = Yii::$app->user->id;
    	$data['member_id'] = $member_id;
    	$searchModel = new CollectionSearch();
    	$dataProvider = $searchModel->search($data);    
    	$collections = $dataProvider->getModels();
//     	foreach ($collections as $key=>$vo){
//     		$product=$vo['product'];	    	 
// 	        $product->imagesAddPrefix();     	        	        	
	        
//     	}
    	return $dataProvider;
    }

    public function actionDel(){
    	$product_id = Yii::$app->request->post('product_id');
    	$member_id = Yii::$app->user->id;
    	$collection = Collection::find()->where(array('product_id'=>$product_id,'member_id'=>$member_id))->one();
    	if (!empty($collection)){
    		$collection->delete();
    	}    	
    	return ['status'=>1,'msg'=>'操作成功'];
    }
}