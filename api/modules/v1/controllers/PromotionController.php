<?php

namespace api\modules\v1\controllers;

use yii;
use api\common\controllers\Controller;
use yii\data\ActiveDataProvider;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use common\logic\PromotionLogic;
use common\models\Product;
use common\models\Shop;
use api\modules\v1\models\FlashSale;
use common\helpers\Util;
class PromotionController extends Controller
{

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => QueryParamAuth::className(),
                'tokenParam' => 'token',
                'optional' => [
                   'flash-sale',
                ]
            ]
        ]);
    }
    /**
     *
     *  返回抢购列表
     */
    public function actionFlashSale($num=4,$proming_status=1){
    	$promotionLogic = new PromotionLogic();
    	$openStatus = $promotionLogic->checkOpen(1);
    	if (!$openStatus){
    		return ['open_status'=>0];
    	}
    	if ($proming_status==1){
    		$now = time();
    		$query = FlashSale::find()->joinWith('product')->joinWith('shop')
    		->where([FlashSale::tableName().'.status'=>1,Product::tableName().'.status'=>1,Shop::tableName().'.status'=>1])
    		->andWhere(['>','goods_num',0])
    		->andWhere(['>=','end_time',$now])->andWhere(['<=','start_time',$now]);
    	}
    	
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			'sort' => [
    					'defaultOrder' => [
    						'end_time' => SORT_ASC
    					]
    			],
    			'pagination' => [
    					'pageSize' =>$num,
    			],
    	]);
    	$data = $this->serializeData($dataProvider);
//     	foreach ($data['items'] as &$v){
//     		$product = $v['product'];
//     		$product['image'] = Util::ImagesAddPrefix($product['image'],'url');
//     		$product['image'] = Util::ImagesAddPrefix($product['image'],'thumbImg');
//     		$v['product'] = $product;
//     	}
    	return ['open_status'=>1,'data'=>$data];
    }
}
