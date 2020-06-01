<?php
namespace common\logic;


use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use common\models\Coupon;
use common\models\CouponItem;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use Imagine\Filter\Basic\Save;
use yii\helpers\Url;
use common\models\Product;
use common\helpers\Util;
use common\helpers\Tools;
use Hashids\Hashids;

class CouponLogic
{
    /**
     * 获取用户该店铺未使用的优惠券
     * @param int $mid
     * @param int $shopId
     * @return array
     */
    
    public function getCanUseShopCoupons($mid,$shopId){
        $items = CouponItem::find()->andWhere(['mid'=>$mid,'use_time'=>null])->joinWith('coupon',false)->andwhere(['shop_id'=>$shopId,'status'=>1])->all();
        return Json::decode(Json::encode($items));
    }
  
    
    /**
     * 获取用户该店铺未使用的优惠券能否使用的数组
     * @param array $shopCoupons
     * @param array $conditions
     * @return array[]
     */
    public function getAbleShopCoupons($shopCoupons,$conditions){
        $items = array();
        $items['enable'] = array();
        $items['disable'] = array();
        foreach ($shopCoupons as $shopCoupon){
            $moneyCondition = true;
            $timeCondition = true;
            $productCondition=true;
            //判断时效
            $now = time();
            if ($now<$shopCoupon['coupon']['use_start']||$now>$shopCoupon['coupon']['use_end']) {
                $timeCondition = false;
            }
            //判断使用类型及总金额
            if ($shopCoupon['coupon']['product_limiter']==0) {//全场
                if (isset($conditions['money_condition'])) {
                    $moneyCondition=$shopCoupon['coupon']['money_condition'] > $conditions['money_condition']? false: true;
                }
                
            }elseif ($shopCoupon['coupon']['product_limiter']==1){//单品
                if (!array_key_exists($shopCoupon['coupon']['product_limiter_id'], $conditions['product_ids'])) {
                    //array_push($items['disable'], $shopCoupon);
                    $productCondition=false;
                }else{
                    
                    $moneyCondition = $shopCoupon['coupon']['money_condition'] > $conditions['product_ids'][$shopCoupon['coupon']['product_limiter_id']]? false: true;                    
                }                
            }
            if ($moneyCondition && $timeCondition && $productCondition) {
                array_push($items['enable'], $shopCoupon);
            }else{
                array_push($items['disable'], $shopCoupon);
            }
            
        }        
        ArrayHelper::multisort($items['enable'], 'coupon.use_money',SORT_DESC);
        ArrayHelper::multisort($items['disable'], 'coupon.use_money',SORT_ASC);
        return $items;
    }
    /**
     * 领取优惠券
     * 
     * @param int $mid
     * @param int $couponid
     * @param array $conditions extendparam
     * @return array
     */
    public function getCouponItem($mid,$couponid,$conditions=null){
        //判断是否满足领取条件        
        $receiveData = $this->checkCanReceive($couponid, $mid,$conditions);        
        return $receiveData;        
    }
    /**
     * 检验优惠券是否可用
     * @param  $mid
     * @param  $id
     * @param  $condition
     * @param  $shopId
     */
    public function checkCanUse($mid,$id,$condition,$shopId){
        $item = CouponItem::find()->andWhere(['mid'=>$mid,'use_time'=>null,'yj_coupon_item.id'=>$id])->joinWith('coupon',false)->andWhere(['shop_id'=>$shopId,'status'=>1])->one();     
        if (!empty($item)) {
            $coupon = $item->coupon;
            //判断时效
            $now = time();
            if ($now<$coupon['use_start']||$now>$coupon['use_end']) {
                return ['status'=>0,'msg'=>'优惠券使用失败（时效）！，请重新提交订单'];
            }
            //判断使用金额条件
            if ($coupon['product_limiter']==0) {
                if ($condition['money_condition']<$coupon['money_condition']) {
                    return ['status'=>0,'msg'=>'不满足优惠券使用条件！，请重新提交订单'];
                }
            }elseif ($coupon['product_limiter']==1){
                if ($condition['product_ids'][$coupon['product_limiter_id']]<$coupon['money_condition']) {
                    return ['status'=>0,'msg'=>'不满足优惠券使用条件！，请重新提交订单'];
                }
            }
            
            return ['status'=>1,'msg'=>$item];
         
        }else{
            return ['status'=>0,'msg'=>'优惠券不存在！'];
        }
    }
        
    /**
     * 获取用户优惠券列表
     */
    public function getCoupons($uid,$params){
        if(isset($params['num'])&&$params['num']!=0&&$params['num']<50) //如果请求参数包含条数，则限制返回数量。单次查询最大50条
            $num=$params['num'];
        else
            $num=10;
        
        $now = time();    
        if ($params['coupon_status']==1) {//已使用
            $query = CouponItem::find()->andWhere(['mid'=>$uid])->andWhere(['>','use_time',0])->joinWith('coupon',false);
        }elseif ($params['coupon_status']==0){//未使用且可用
            $query = CouponItem::find()->andWhere(['mid'=>$uid,'use_time'=>null])->joinWith('coupon',false,'RIGHT JOIN')->onCondition(['status'=>1])->andOnCondition(['>','use_end',$now])->distinct('coupon_id');
        }elseif ($params['coupon_status']==2){//已失效
            $query = CouponItem::find()->andWhere(['mid'=>$uid,'use_time'=>null])->joinWith('coupon',false,'RIGHT JOIN')->onCondition(['<>','status',1])->orOnCondition(['<','use_end',$now])->distinct('coupon_id');
        }
        
        //Yii::error($query->createCommand()->getRawSql());
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            
            'pagination' => [
                'pageSize' =>$num,               
            ],
        ]);
        $models = $dataProvider->getModels();
        ArrayHelper::multisort($models, 'coupon.use_end',SORT_ASC);
        return $dataProvider;
    }
    /**
     * 判断是否可以领取
     * @param int $couponid
     * @param int $mid
     * @return array
     */
    private function checkCanReceive($couponid,$mid,$conditions=null){
        //判断状态
        $coupon = Coupon::findOne($couponid);
        if ($coupon['status']!=1) {
            return ['status'=>0,'msg'=>'该优惠券不可领取！'];
        }        
        //判断时效
        $now = time();
        if ($now<$coupon['send_start']||$now>$coupon['send_end']) {
            return ['status'=>0,'msg'=>'只能在指定时间段内领取！'];
        }
        //判读领取次数        
        $couponItemCount = CouponItem::find()->andWhere(['mid'=>$mid,'coupon_id'=>$couponid,'use_time'=>null])->count();
        if ($couponItemCount>=$coupon['receive_limiter']) {
            return ['status'=>0,'msg'=>'您已领取过该优惠券！'];
        }
        switch ($coupon->type) {
            case 1://直接领取
                $couponItem = new CouponItem();
                $couponItem->code = Tools::get_order_no();
                $couponItem->mid = $mid;
                $couponItem->coupon_id = $couponid;
                if ($couponItem->save()) {
                    $couponItem = CouponItem::findOne($couponItem->id);
                    $couponItem->code = $this->getCouponCode($couponItem->id);
                    if ($couponItem->save()) {                        
                        return ['status'=>1,'msg'=>'领取成功！'];
                    }else{
                        $couponItem->delete();
                        return ['status'=>0,'msg'=>'系统错误，领取失败！'];
                    }
                }else{
                    return ['status'=>0,'msg'=>current($couponItem->getErrors())];
                }    
                break;
                
                
            case 2://卡密激活
                $code = $conditions['code'];
                    $couponItem = CouponItem::find()->andWhere(['code'=>$conditions['code'],'coupon_id'=>$couponid])->one();
                             
                if (empty($couponItem)) {
                    return ['status'=>0,'msg'=>'卡号错误！'];
                }                
                if (!$couponItem->validatePassword($conditions['password'])) {
                    return ['status'=>0,'msg'=>'卡密错误！'];
                }
                if (!empty($couponItem['use_time'])) {
                    return ['status'=>0,'msg'=>'该券已被使用，无法领取！'];
                }
                if ($couponItem['mid']==$mid) {
                    return ['status'=>0,'msg'=>'你已领过该券，请勿重新领取！'];
                }
                if ($couponItem['is_active']==1) {
                    return ['status'=>0,'msg'=>'该优惠券已被激活，领取失败！'];
                }
                $couponItem->is_active = 1;
                $couponItem->mid = $mid;
                if ($couponItem->save()) {
                    return ['status'=>1,'msg'=>'领取成功！','money'=>$couponItem->money];
                }else{
                    return ['status'=>0,'msg'=>current($couponItem->getErrors())];
                } 
                break;
            default:
                return ['status'=>0,'msg'=>'优惠券类型不存在！'];
                break;
        }
        
        return ['status'=>1,'msg'=>'领取成功！'];
    }
    /**
     * 生成优惠券码
     * @return string
     */
    public function getCouponCode($id){
        $hashids = new Hashids(Yii::$app->params['auth_code'],8);
        $hashID = $hashids->encode($id);
        return $hashID;
    }
    
    /**
     * 获取店铺正在发布的优惠券列表
     */
    public function getShopReceiveCoupons($shopId,$params=null){
        $now = time();
//         if(isset($params['num'])&&$params['num']!=0&&$params['num']<50) //如果请求参数包含条数，则限制返回数量。单次查询最大50条
//             $num=$params['num'];
//         else
//             $num=10;
        
        $query = Coupon::find()->andWhere(['shop_id'=>$shopId,'status'=>1])->andWhere(['<','send_start',$now])->andWhere(['>','send_end',$now]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'use_money' => SORT_ASC
                ]
            ],
            'pagination' => [
                //'pageSize' =>$num,
                'pageSize' =>1000,
            ],
        ]);
        if (isset($params['mid'])) {
            $models = $dataProvider->getModels();
            foreach ($models as $v){
                //判读领取次数
                $couponItemCount = CouponItem::find()->andWhere(['mid'=>$params['mid'],'coupon_id'=>$v['id'],'use_time'=>null])->count();
                if ($couponItemCount>=$v['receive_limiter']) {
                    $v['is_received'] = 1;
                }
            }
        }  
        
        return $dataProvider;
    }
    /**
     * 退款退回优惠券
     */
    public function refundCoupons($orderId,$itemId,$uid){
        $item = CouponItem::find()->andWhere(['order_id'=>$orderId,'id'=>$itemId,'mid'=>$uid])->one();
        $item->use_time = null;
        $item->order_id = null;
        if ($item->update()) {
            return ['status'=>1,'msg'=>'退回成功！'];
        }else{
            return ['status'=>0,'msg'=>'优惠券退回失败！'];
        }
    }
   
    /**
     * 获取分享链接
     */
    public function getShareLink($itemId,$mid){
        $item = CouponItem::find()->andWhere(['id'=>$itemId,'mid'=>$mid])->one();
        if (empty($item)) {
            return ['status'=>0,'msg'=>'参数错误！'];
        }
        $coupon = $item->coupon;       
        $now = time();
        if ($coupon['status']!=1) {
            return ['status'=>0,'msg'=>'该券已关闭！分享失败！'];
        }
        if ($coupon['use_end']<=$now) {
            return ['status'=>0,'msg'=>'该券已过期！分享失败！'];
        }  
        if ($item['is_active']==0) {
            return ['status'=>0,'msg'=>'该券未激活！分享失败！'];
        }
        if (!empty($item['use_time'])) {
            return ['status'=>0,'msg'=>'该券已使用！分享失败！'];
        }
        if ($item['is_share']!=1) {
            $item->is_share = 1;
            $item->save();
        }
        $result = array();
        $result['code'] = $item['code'];
        $result['id']= $itemId;
        return ['status'=>1,'msg'=>$result];    
    }
    /**
     * 处理分享
     */
    public function doShare($mid,$params){
        $tx = Yii::$app->db->beginTransaction();
        try {
            $item = CouponItem::find()->andWhere(['id'=>$params['item_id']])->one();
            if (empty($item)) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'参数错误！'];
            }
            if ($item['is_share']==0) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'该券未分享！领取失败！'];
            }
            if (!empty($item['use_time'])) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'该券已被使用！领取失败！'];
            }
            if ($mid==$item['mid']&&$item['is_share']==1) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'不可领取自己分享的券！'];
            }
            if ($mid==$item['mid']&&$item['is_share']==2) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'你已经领取该券了！'];
            }
            if (!$item->validatePassword($params['password'])) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'领取码错误！'];
            }
            $item->mid = $mid;
            $item->log = $item['log'].$mid.',';
            $item->is_share=2;
            if (!$item->save()) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>current($item->getErrors())];
            }
            $tx->commit();
            return ['status'=>1,'msg'=>'领取成功！'];
        } catch (StaleObjectException $e) {
            // 解决冲突的代码
            $tx->rollBack();
            return ['status'=>0,'msg'=>'来晚了，该券已被领取！'];
        }catch (Exception $e) {            
            $tx->rollBack();
            return ['status'=>0,'msg'=>'系统错误，领取失败！'];
        }
        
    }
    /**
     * 获取优惠金额
     * @param 
     */
    public function getDiscountPrice($couponId,$sku_price){
        
        $coupon = Coupon::findOne($couponId);        
        $use_type = $coupon['use_type'];
        if ($use_type==1) {//1满减2折扣
            return $coupon['use_money'];
        }elseif ($use_type==2){
            $result = bcadd($sku_price*$coupon['use_money']/100, 0,2);
            return $result;
        }
    }
}

