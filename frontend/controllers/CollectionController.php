<?php

namespace frontend\controllers;

use Yii;

use frontend\common\controllers\Controller;
use yii\helpers\Json;
use yii\filters\AccessControl;
use common\models\Collection;
use common\models\Product;
use common\models\CollectionShop;
use yii\helpers\Url;

/**
 * ProductComment controller.
 */
class CollectionController extends Controller
{
    /**
     * 添加收藏
     */
    public function actionAdd()
    {
        
        $product_id = Yii::$app->request->post('product_id');
        $member_id = Yii::$app->user->id;
        if($member_id=='')
            return Json::encode(['status'=>2,'msg'=>'请先登录再进行操作']);
        $collection = Collection::find()->where(array('product_id'=>$product_id,'member_id'=>$member_id))->one();
        if (!empty($collection)){
            return Json::encode(['status'=>0,'msg'=>'该商品已添加到收藏列表，请勿重复添加']);
        }
        $newcollection = new Collection();
        $newcollection->member_id = $member_id;
        $newcollection->product_id = $product_id;
        $flag = $newcollection->save();
        if (!$flag){
            return Json::encode(['status'=>0,'msg'=>$newcollection->errors]);
        }else{
            //收藏数+1
            Product::updateAllCounters(array('favorite'=>1),['product_id'=>$product_id]);
            return Json::encode(['status'=>1,'msg'=>'收藏成功']);
        }
    }
    
    public function actionDel(){
        $product_id = Yii::$app->request->post('product_id');
        $member_id = Yii::$app->user->id;
        $collection = Collection::find()->where(array('product_id'=>$product_id,'member_id'=>$member_id))->one();
        if (!empty($collection)){
            $collection->delete();
        }
        return Json::encode(['status'=>1,'msg'=>'取消成功']);
    }
    
    /**
     * 收藏店铺
     */
    public function actionAddShop()
    {
        $shop_id = Yii::$app->request->post('shop_id');
        $member_id = Yii::$app->user->id;
        if($member_id=='')
            return Json::encode(['status'=>2,'msg'=>'请先登录再进行操作']);
        $collection = CollectionShop::find()->where(array('shop_id'=>$shop_id,'member_id'=>$member_id))->one();
        if (!empty($collection)){
            return Json::encode(['status'=>0,'msg'=>'该店铺已添加到收藏列表，请勿重复添加']);
        }
        $newcollection = new CollectionShop();
        $newcollection->member_id = $member_id;
        $newcollection->shop_id = $shop_id;
        $flag = $newcollection->save();
        if (!$flag){
            return Json::encode(['status'=>0,'msg'=>'收藏失败']);
        }else{
            return Json::encode(['status'=>1,'msg'=>'收藏成功']);
        }
    }
    /**
     * 取消收藏店铺
     */
    public function actionDelShop(){
        $shop_id = Yii::$app->request->post('shop_id');
        $member_id = Yii::$app->user->id;
        $collection = CollectionShop::find()->where(array('shop_id'=>$shop_id,'member_id'=>$member_id))->one();
        if (!empty($collection)){
            $collection->delete();
        }
        return Json::encode(['status'=>1,'msg'=>'取消成功']);
    }
    
}
