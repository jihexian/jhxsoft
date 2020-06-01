<?php

namespace api\modules\v1\controllers;

use api\common\controllers\Controller;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use common\logic\CouponLogic;
use common\modules\coupon\models\CouponItem;
use common\modules\coupon\models\Coupon;
class CouponController extends Controller
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
     * 获取店铺可领取优惠券列表
     * @return \yii\data\ActiveDataProvider
     */
    public function actionLists(){
        if (Yii::$app->request->isPost) {
            $params=Yii::$app->request->post();
            $couponLogic  = new CouponLogic();
            $params['mid'] = Yii::$app->user->id;
            $dataProvider = $couponLogic->getShopReceiveCoupons($params['shop_id'], $params);
            return $dataProvider;
        }
    }
    
    /**
     * 领取优惠券
     * @return string
     */
    public function actionReceive(){
        if (Yii::$app->request->isPost) {
            $mid = Yii::$app->user->id;
            $couponid = Yii::$app->request->post('coupon_id');
            $conditions = Yii::$app->request->post('conditions'); 
            if(!empty($conditions['code'])&&!empty($conditions['password'])){
                $couponItem = CouponItem::find()->andWhere(['code'=>$conditions['code']])->one();
                if (empty($couponItem)) {
                    return ['status'=>0,'msg'=>'卡号错误 ！'];
                }else{
                    $couponid =$couponItem['coupon_id'];
                } 
            }
       
            $couponLogic  = new CouponLogic();
            $result = $couponLogic->getCouponItem($mid, $couponid,$conditions);
            return $result;
        }
    }
    /**
     * 获取优惠券分享链接
     * @return 
     */
    public function actionShareLink(){
        if (Yii::$app->request->isPost) {
            $mid = Yii::$app->user->id;
            $itemId = Yii::$app->request->post('item_id');
            $couponLogic  = new CouponLogic();
            $result = $couponLogic->getShareLink($itemId, $mid);
            return $result;
        }
    }
    /**
     * 处理点击分享请求
     */ 
    public function actionDoShare(){
        if (Yii::$app->request->isPost) {
            $mid = Yii::$app->user->id;
            $params = Yii::$app->request->post();           
            $couponLogic  = new CouponLogic();
            $result = $couponLogic->doShare($mid,$params);
            return $result;
        }
    }
    
    public function actionItemInfo(){
        if (Yii::$app->request->isPost) {
            $params = Yii::$app->request->post();
            $couponItem  = CouponItem::find()->alias('a')->where(['a.id'=>$params['id']])->joinWith('coupon c')->joinWith('member m',false)->select('a.id,a.coupon_id,m.username')->asArray()->one();
            if (empty($couponItem)) {
                return ['status'=>0,'msg'=>'参数错误！'];
            }
            return $couponItem;
        }
    }
    
}
