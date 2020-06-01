<?php

namespace api\modules\v1\controllers;

use common\models\ProductComment;
use common\models\ProductCommentSearch;
use yii;
use api\common\controllers\Controller;
use yii\data\ActiveDataProvider;
use common\helpers\Tree;
use common\models\ProductSearch;
use common\models\Product;
use common\models\Skus;
use common\models\Order;
use common\models\OrderSku;
use common\models\AttributeValue;
use common\models\Attribute;
use common\models\ProductModelAttr;
use common\logic\ProductLogic;
use yii\helpers\Url;
use common\models\Collection;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use common\logic\CommentLogic;
use common\logic\ShareLogic;

class ProductController extends Controller
{

	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
				[
						'class' => QueryParamAuth::className(),
						'tokenParam' => 'token',
						'optional' => [
							'index',
							'skus',
							'detail',
							'comment',
							'find-by-model-attr',
						]
				]
		]);
	}

	/**
	 * 商品列表
	 * @return \yii\data\ActiveDataProvider
	 */
    public function actionIndex()
    {
    	$data=Yii::$app->request->post();
    
    	$redis = Yii::$app->redis;
    	$key=Yii::$app->user->id;
    	$history_keyword=array();
    	if(isset($data['name'])&&!empty($data['name'])&&!empty($key)){
    	    $redis->lrem($key,0,$data['name']);
    	    $redis->lpush($key,$data['name']);
    	    $history_keyword = Yii::$app->redis->lrange($key,0,7);
    	}
    	$body=yii::$app->request->get();
    	$data=array_merge($data,$body);
        $data['status']=1;
        $data['shop_status']=1;

    	$searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($data);  
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $dataProvider->setSort($sort);
 
        return $dataProvider;
    } 
    
    //列出历史搜索记录
    public function actionHistory(){
        $num=yii::$app->request->post('num',10);
        $key=yii::$app->user->id;
        $history_keyword = Yii::$app->redis->lrange($key,0,$num-1);
        return ['items'=>$history_keyword];
    }
    
    //清空历史搜索记录
    public function actionTruncate(){
        $key=yii::$app->user->id;
        if(yii::$app->redis->del($key)){
            return ['status'=>1,'msg'=>'成功'];
        }else{
            return ['status'=>0,'msg'=>'失败'];
        }
    }
    
    public function actionSkus(){
    	$productId = Yii::$app->request->post('product_id');
    	$productLogic = new ProductLogic();
    	//return $productLogic->getDetail($productId);
 		return $productLogic->getSkus($productId);
    }
    
	/**
	 * 商品详情
	 * @return 
	 */
    public function actionDetail(){

        $xx=Yii::$app->request->post('product_id');
    	!$xx? $productId = Yii::$app->request->get('product_id'):$productId = Yii::$app->request->post('product_id');
        $shareMid = Yii::$app->request->post('share_mid');
    	$product = Product::findOne(['product_id'=>$productId,'is_del'=>0]);
    	if (!$product){
    		$data = array('errcode'=>1,'errmsg'=>"商品不存在");
    		return $data;
    	}
    	$productLogic = new ProductLogic();
    	$skus = $productLogic->getSkus($productId);
    
    	
    	//获取好评差评中评数
    	$commentLogic = new CommentLogic();
    	$commentCount = $commentLogic->getCounts($productId);
    
    	//浏览量加一
    	$product->updateCounters(['visit' => 1]);
    	$member_id = Yii::$app->user->id;
    	if (!empty($member_id)){
    		$collection = Collection::find()->where(array('member_id'=>$member_id,'product_id'=>$productId))->one();
    		$data['isFavorite'] = empty($collection)? 0:1;
    	}else{
    		$data['isFavorite'] =0;
    	}
    	//如果是从分享入口进入，更新分享关系
    	$shareLogic = new ShareLogic();
    	if (!empty($member_id)&&!empty($shareMid)){
    		$shareLogic->updateShareInfo($member_id, $productId, $shareMid);
    	}    	
    	return array_merge($skus,array('comment_count'=>$commentCount));
 
    }
    /**
     * 商品评论列表
     */
     public function actionComment(){
     	$data=Yii::$app->request->post();
     	$data['pid'] = 0;
     	$data['status'] = 1;
     	$searchModel = new ProductCommentSearch();
     	$dataProvider = $searchModel->search($data);
     	$comment = $dataProvider->getModels();
     	//获取好评差评中评数
     	$commentLogic = new CommentLogic(); 
     	$commentCount=array();
     	if(isset($data['goods_id'])){
     	    $commentCount = $commentLogic->getCounts($data['goods_id']);
     	}
     	
		return array_merge(Yii::createObject($this->serializer)->serialize($dataProvider),array('comment_count'=>$commentCount));
     }

    /**
     * 通过属性值搜索商品
     * @return 
     */
    public function actionFindByModelAttr($num=10){
    	$modelAttrId = Yii::$app->request->post('model_attr_id');
    	$modelAttrValue = Yii::$app->request->post('model_attr_value_id');

    	$productIds = ProductModelAttr::find()->where(['model_attr_id'=>$modelAttrId,'model_attr_value_id'=>$modelAttrValue])->indexBy('product_id')->all();  	
    	
    	$query = Product::find()->where(['in', 'product_id', array_keys($productIds)])->andWhere(['status'=>1]);
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'sort' => [
    					'defaultOrder' => [
    							'up_time' => SORT_DESC
    					]
    			],
    			'pagination' => [
    					'pageSize' =>$num,
    			],
    	]);
    	$sort = $dataProvider->getSort();
    	$sort->enableMultiSort=true;
    	$dataProvider->setSort($sort);    	
    	$models = $dataProvider->getModels();
//     	foreach ($models as $model){
//     		$model->imagesAddPrefix();
//     	}
    	return $dataProvider;    	
    }
    
}
