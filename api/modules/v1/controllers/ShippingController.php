<?php

namespace api\modules\v1\controllers;

use yii;
use api\common\controllers\Controller;
use yii\data\ActiveDataProvider;
use common\helpers\Tree;
use common\models\Address;
use common\models\ProductSearch;
use common\models\Product;
use common\models\Skus;
use common\models\AttributeValue;
use common\models\Attribute;
use common\models\ProductModelAttr;
use common\models\Shipping;
use common\logic\ShippingLogic;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
class ShippingController extends Controller
{

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => QueryParamAuth::className(),
                'tokenParam' => 'token',
                'optional' => [
                    'test',
                ]
            ]
        ]);
    }
    /**
     *
     * 返回运费价格
     */
    public function actionPrice(){
    	$userId = Yii::$app->user->id;    	
    	$shippingLogic = new ShippingLogic();
    	$addressId = Yii::$app->request->post('address_id');
    	$address= Address::find()->where(['id'=>$addressId,'uid'=>$userId ])->one();
    	isset($address->region_id)?$region_id=$address->region_id:$region_id=0;
    	$postPrice = $shippingLogic->getPrice($userId,$region_id);
    	return ['post_price'=>$postPrice];
    }
    
    public function actionSinglePrice(){
    	$userId = Yii::$app->user->id;
    	$shippingLogic = new ShippingLogic();
    	$addressId = Yii::$app->request->post('address_id');
    	$skuId = Yii::$app->request->post('sku_id');
    	$num = Yii::$app->request->post('num');
    	$address= Address::find()->where(['id'=>$addressId,'uid'=>$userId ])->one();
    	isset($address->region_id)?$region_id=$address->region_id:$region_id=0;
    	$postPrice = $shippingLogic->getSinglePrice($userId,$skuId,$num,$region_id);
    	return ['post_price'=>$postPrice];
    }
}
