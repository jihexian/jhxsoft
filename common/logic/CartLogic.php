<?php
/**
 * Created by PhpStorm.
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-05-31 16:49
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace common\logic;
use common\components\Controller;
use common\models\Cart;
use common\models\Product;
use common\models\Shop;
use common\models\Skus;
use common\helpers\Tools;
use common\helpers\Util;
use common\models\OrderSku;
use common\modules\promotion\models\FlashSale;
use yii;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\Address;
use common\modules\coupon\models\CouponItem;
use common\models\Order;
use yii\base\Exception;
use common\components\job\JobOrder;
use common\models\OrderPick;
use common\models\Pick;
use common\logic\DistributeLogic;
use common\models\OrderData;
class CartLogic{
    public $obj;
    public function __construct()
    {
        $this->obj=new Cart();
        
    }
    
    /**
     * 获取选中的购物车商品的总价，折扣等信息.若carts为单个店铺的数据，多个店铺数据总数要多次调用后叠加，若carts为findCartByUser则为所有店铺的总数
     * market_total:市场总价格,buy_total:购买总数,weight:购买总重量,sale_total:商城标价总价,sale_real_total:商城实际销售总价
     */
    public function getSelectdCartExtraProps($carts){
        $buy_total = 0;
        $market_total = 0;
        $sale_total = 0;
        $sale_real_total = 0;
        $weight=0;
        foreach ($carts as $key => $value) {
            $skusLogic = new SkusLogic($value['sku_id']);
            $skusData = array();
            $skusData['prom_type'] = $value['prom_type'];
            $skusData['prom_id'] = $value['prom_id'];
            $skusData['price'] = $value['sale_price_real'];
            $skusData['stock_num'] = $value['num'];
            $skusStatus = $skusLogic->validateSku($skusData);
            $value['skusStatus'] = $skusStatus;
            if ($value['skusStatus']['status']!=1) {
                continue;
            }
            $buy_total += $value['num'];//购买总数
            $weight += $value['skus']['weight']*$value['num'];//总重量
            if($value['selected']==1){
                $market_total+=$value['market_price']*$value['num'];//市场总价
                $sale_total+=$value['sale_price']*$value['num'];//商城标价总价
                $sale_real_total+=$value['sale_price_real']*$value['num'];//商城实际销售总价
            }
        }
        return ['market_total'=>bcadd($market_total, 0,2),'buy_total'=>$buy_total,'weight'=>$weight,'sale_total'=>bcadd($sale_total, 0,2),'sale_real_total'=>bcadd($sale_real_total, 0,2)];
    }
    /**
     *
     * @param  $carts array shopCarts
     * @return array
     */
    public function getExtraShopCarts($carts){
        $cartLogic = new CartLogic();
        $list = array();
        foreach ($carts as $shopId=>&$shopCarts){
            $list[$shopId]['shop'] = Shop::findOne($shopId)->getAttributes(['id','name']);
            foreach ($shopCarts as &$cart){
                $skusLogic = new SkusLogic($cart['sku_id']);
                $skusData = array();
                $skusData['prom_type'] = $cart['prom_type'];
                $skusData['prom_id'] = $cart['prom_id'];
                $skusData['price'] = $cart['sale_price_real'];
                $skusData['stock_num'] = $cart['num'];
                $skusStatus = $skusLogic->validateSku($skusData);
                $cart['skusStatus'] = $skusStatus;
            }
            $list[$shopId]['data'] = $shopCarts;
            //$list[$shopId]['shipping_price'] = 0;
            $cartsProps = $cartLogic->getSelectdCartExtraProps($shopCarts);
            $list[$shopId]['market_total'] = $cartsProps['market_total'];
            $list[$shopId]['sale_total'] = $cartsProps['sale_total'];
            $list[$shopId]['sale_real_total'] = $cartsProps['sale_real_total'];
            $list[$shopId]['buy_total'] = $cartsProps['buy_total'];
            $list[$shopId]['shop'] = Shop::findOne($shopId)->getAttributes(['id','name']);
        }
        return $list;
    }
    /**
     * 在此处检测sku信息
     * @param  $carts array shopCarts
     * @param array  $address
     * @param  $uid
     * @param $point_id自提点id
     * @return array
     */
    public function getExtraShopCartsWithShipping($shopCarts,$address,$uid,$point_id=0){
        $shippingLogic = new ShippingLogic();
        $list = array();
        foreach ($shopCarts as $shopId=>&$carts){
            // $list[$shopId]['shop'] = Shop::findOne($shopId)->getAttributes(['id','name','is_village']);
            foreach ($carts as &$cart){
                $skus =Skus::findOne(['sku_id'=>$cart['sku_id']]);
                $skusData = array();
                $skusData['prom_type'] = $cart['prom_type'];
                $skusData['prom_id'] = $cart['prom_id'];
                $skusData['price'] = $cart['sale_price_real'];
                $skusData['stock_num'] = $cart['num'];
                $skusData['stock_num'] = $cart['num'];
                $skusData['region_id']=isset($address['region_id'])?$address['region_id']:0;
                foreach ($skus->skuItem as $vo){
                    if($vo->attri->usage_mode==3){   //判断是否是时间类型，如果是
                        $skusData['active']=strtotime($vo->attributeValue->value_str)>(strtotime(date('Y-m-d'))+24*3600) ?1:0;
                    }
                }
                //验证sku是否正常
                $skusLogic=new SkusLogic($cart['sku_id']);
                $skusStatus = $skusLogic->validateSku($skusData);
                $cart['skusStatus'] = $skusStatus;
            }
            $list[$shopId]['data'] = $carts;
            $list[$shopId]['shipping_price'] = 0;
            $list[$shopId]['sale_real_total'] = 0;
            $list[$shopId]['buy_total'] = 0;
            //判断自提点是否免费
            $pick=array();
            $point_id&&$pick=Pick::findOne(['id'=>$point_id,'is_free'=>1]);
            if($pick||empty($address)){
                $shippingPrice =0;
            }else{
                $shippingPrice = $shippingLogic->getPrice($uid, $address['region_id'], $carts);
            }
            $cartsProps = $this->getSelectdCartExtraProps($carts);
            $list[$shopId]['shipping_price'] = $shippingPrice;
            $list[$shopId]['sale_real_total'] = $cartsProps['sale_real_total'];
            $list[$shopId]['buy_total'] = $cartsProps['buy_total'];
            $list[$shopId]['market_total'] = $cartsProps['market_total'];
            $list[$shopId]['sale_total'] = $cartsProps['sale_total'];
            $list[$shopId]['shop'] = Shop::findOne($shopId);
            $list[$shopId]['order_price'] = bcadd($list[$shopId]['sale_real_total'] + $list[$shopId]['shipping_price'], 0,2) ;
        }
        return $list;
    }
    /**
     * 加上优惠券列表
     * @param  $shopCarts
     * @param  $uid
     */
    public function getExtraShopCartsWithCoupon($shopCarts,$uid){
        
        $couponLogic = new CouponLogic();        
        foreach ($shopCarts as $shopId=>&$carts){
            $productIds = array();
            foreach ($carts['data'] as $cart){
                if ($cart['proming_status']!=1) {
                    if (array_key_exists($cart['product_id'],$productIds)) {
                        $productIds[$cart['product_id']] += $cart['sale_price_real'] * $cart['num'];
                        
                    }else{
                        $productIds[$cart['product_id']] = $cart['sale_price_real'] * $cart['num'];
                    }
                }
            }
            $shopCarts[$shopId]['coupon'] = array();
            $condition = array();
            $condition['product_ids'] = $productIds;
            $condition['money_condition'] = $shopCarts[$shopId]['sale_real_total'];//目前判断金额采取订单实际销售金额，包含扣除掉某些已经参加活动的商品金额。
            $condition['product_ids'] = $productIds;
            $shopCoupons = $couponLogic->getCanUseShopCoupons($uid, $shopId);
            $shopCarts[$shopId]['coupon'] = $couponLogic->getAbleShopCoupons($shopCoupons,$condition);
        }
        return $shopCarts;
    }
    /**
     *
     * @param  $shopCarts
     * @param  $uid
     * @param  $shopData 一个以shop_id为key，coupon_item_id为value的数组
     * @return array
     */
    public function setShopCartsCoupon($shopCarts,$uid,$shopCouponData){
        $couponLogic = new CouponLogic();
        foreach ($shopCarts as $shopId=>&$carts){
            $productIds = array();
            foreach ($carts['data'] as $cart){
                if ($cart['proming_status']!=1) {
                    if (array_key_exists($cart['product_id'],$productIds)) {
                        $productIds[$cart['product_id']] += $cart['sale_price_real']*$cart['num'];
                        
                    }else{
                        $productIds[$cart['product_id']] = $cart['sale_price_real']*$cart['num'];
                    }
                }
            }
            
            $shopCarts[$shopId]['coupon'] = array();
            if (empty($shopCouponData[$shopId])) {
                continue;
            }
            $condition = array();
            $condition['money_condition'] = $carts['sale_real_total'];//目前判断sale_real_total。
            $condition['product_ids'] = $productIds;
            $result = $couponLogic->checkCanUse($uid, $shopCouponData[$shopId], $condition, $shopId);
            if ($result['status']!=1) {
                return $result;
            }else{
                $shopCarts[$shopId]['coupon'] = $result['msg'];
            }
        }
        return ['status'=>1,'msg'=>$shopCarts];
    }
    
    /**
     * @param  $shopExtraCarts:shopExtraCartsWithShipping
     * @param $use4Money：使用的积分总额
     */
    public function setShopCartsIntegralMoney($shopExtraCarts,$use4Money){
        if($use4Money<=0){
            //无积分兑换
            foreach ($shopExtraCarts as $shopId=>&$shopCarts){
                $shopCarts['scoreMoney'] = 0;
                $shopCarts['score'] = 0;
            }
            return ['status'=>1,'msg'=>$shopExtraCarts];
        }
        
        
        $orderAmount = 0;
        foreach ($shopExtraCarts as $shopId=>$shopCarts){
            $orderAmount = $shopCarts['order_price'];
        }
        $scoreLogic = new ScoreLogic();
        $change2Score = $scoreLogic->change2Score($use4Money);
        if ($change2Score['status']!=1) {
            return $change2Score;
        }
        $usedScore = 0;
        foreach ($shopExtraCarts as $shopId=>&$shopCarts){
            if (!end($shopExtraCarts)) {
                $persent = bcadd($shopCarts['order_price']/$orderAmount, 0,0);
                $useScore = bcadd($change2Score['msg']*$persent, 0,0);
                $shopCarts['score'] = $useScore;
                $use4MoneyShop = $scoreLogic->use4Money($useScore);
                if ($use4MoneyShop['status']!=1) {
                    return $use4MoneyShop;
                }
                $shopCarts['scoreMoney'] = $use4MoneyShop['msg'];
                $usedScore += $useScore;
            }else{
                $shopCarts['score'] = $change2Score['msg']-$usedScore;
                $use4MoneyShop = $scoreLogic->use4Money($change2Score['msg']-$usedScore);
                if ($use4MoneyShop['status']!=1) {
                    return $use4MoneyShop;
                }
                $shopCarts['scoreMoney'] = $use4MoneyShop['msg'];
            }
        }
        return ['status'=>1,'msg'=>$shopExtraCarts];
        
    }
    /**
     * 获取用户购物车列表
     * @param  $uid
     */
    public function findCartByUser($uid){
        $carts = Cart::find()->joinWith('skus',false)->where(['user_id'=>$uid,'type'=>0])->orderBy('shop_id asc,create_time asc')->all();
        return Json::decode(Json::encode($carts));
    }
    /**
     * 获取用户选中的购物车列表，type=1
     * @param  $uid
     */
    public function findSelected($uid){
        $carts = Cart::find()->joinWith('skus',false)->where(['user_id'=>$uid,'type'=>0,'selected'=>1])->orderBy('shop_id asc,create_time asc')->all();
        return Json::decode(Json::encode($carts));
    }
    public function findByBuy($uid,$id){
        $carts = Cart::find()->joinWith('skus',false)->where(['user_id'=>$uid,'type'=>1,'id'=>$id])->orderBy('create_time desc')->all();
        return Json::decode(Json::encode($carts));
    }
    /**
     * 以,shop_id作为索引重构数组
     * @param  $uid
     */
    public function getShopCarts($carts){
        return Util::array_key_array($carts, 'shop_id');;
    }
    /**
     * cart列表加上图片路径
     * @param  $carts
     * @return $carts
     */
    public function addImage($carts){
        foreach ($carts as $key => $value) {
            /*商品封面*/
            $product_image=Tools::get_product_image($carts[$key]['skus']['product_id']);
            $carts[$key]['skus']['image']=$value['skus']['image']!=''?Yii::$app->params['domain'].$value['skus']['image']:$product_image;
            $carts[$key]['skus']['thumbImg']=$value['skus']['thumbImg']!=''?Yii::$app->params['domain'].$value['skus']['thumbImg']:$product_image;
        }
        return $carts;
    }
    
    
    /**
     * 添加购物车
     * @param  $data[user_id,sku_id,num,prom_type,prom_id,type,session_id]
     */
    public function addCart($data){
        
        //购物车有数据，添加数量
        $cart = Cart::find()->where(['sku_id'=>$data['sku_id'],'user_id'=>$data['user_id'],'type'=>$data['type']])->one();
        if($data['type']!=1&&$data['retry']!=1){
            empty($cart)? 0:$data['stock_num']= $cart['num'] + $data['stock_num'];//如果购物车已存在商品，库存需要验证总数
        }
        //validate sku
        $skuLogic = new SkusLogic($data['sku_id']);
        $valid = $skuLogic->validateSku($data);
        if ($valid['status']!=1) {
            return $valid;
        }
        if(empty($cart)){
            //add
            $skus = Skus::findOne($data['sku_id']);
            $product = $skus->product;
            $cart = new Cart();
            $cart->session_id = $data['session_id'];
            $cart->user_id = $data['user_id'];
            $cart->product_id = $skus->product_id;
            $cart->market_price= $skus->market_price;
            $cart->sale_price= $skus->sale_price;
            //如果是活动商品
            if (!empty($skus->prom)&&$skus->prom->proming_status==1) {
                $cart->sale_price_real = $skus->prom->price;
            } else {
                if (Yii::$app->user->identity->type==3&&$skus->plus_price>0) {
                    $cart->sale_price_real = $skus->plus_price;
                }else{
                    $cart->sale_price_real = $skus->sale_price;
                }
            }
            $cart->sku_id = $data['sku_id'];
            $cart->num = $data['stock_num'];
            $cart->prom_type = $data['prom_type'];
            $cart->prom_id = $data['prom_id'];
            $cart->product_name = $product['name'];
            $cart->product_sn = $product['product_sn'];
            $cart->type = $data['type'];
            //直接购买和加入购物车逻辑区分
            if ($data['type']) {
                $cart->selected = 1;
            }else{
                $cart->selected = 0;
            }
            $cart->shop_id = $product['shop_id'];
            $str = Tools::get_skus_value($skus['sku_values']);
            //插入属性值
            $cart->sku_values = $str;
            if ($cart->save()) {
                if ($data['type']) {
                    return ['status' => 1, 'msg' => $cart->id];
                }else{
                    return ['status' => 1, 'msg' => '添加成功'];
                }
            } else {
                return ['status' => 0, 'msg'=>'添加失败'];
            }
        }else{
            //modify
            //直接购买和加入购物车逻辑区分
            if ($data['type']) {
                $cart->create_time = time();
                $cart->num = $data['stock_num'];
                $skus = Skus::findOne($data['sku_id']);
                $cart->market_price= $skus->market_price;
                $cart->sale_price= $skus->sale_price;
                //如果是活动商品
                if (!empty($skus->prom)&&$skus->prom->proming_status==1) {
                    $cart->sale_price_real = $skus->prom->price;
                } elseif(yii::$app->user->identity->type==3&& $skus->plus_price>0){
                    $cart->sale_price_real = $skus->plus_price;
                }else{
                    $cart->sale_price_real = $skus->sale_price;
                }
                $cart->prom_type = $data['prom_type'];
                $cart->prom_id = $data['prom_id'];
                if($cart->save()){
                    return ['status' => 1, 'msg' => $cart->id];
                }else{
                    return ['status' => 0, 'msg' => '添加失败'];
                }
            }else{
                $cart->num = $data['stock_num'];
                if ($data['retry']) {
                    $skus = Skus::findOne($data['sku_id']);
                    $cart->market_price= $skus->market_price;
                    $cart->sale_price= $skus->sale_price;
                    //如果是活动商品
                    if (!empty($skus->prom)&&$skus->prom->proming_status==1) {
                        $cart->sale_price_real = $skus->prom->price;
                    } else {
                        $cart->sale_price_real = $skus->sale_price;
                    }
                }
                $cart->prom_type = $data['prom_type'];
                $cart->prom_id = $data['prom_id'];
                if($cart->save()){
                    return ['status' => 1, 'msg' => '添加成功'];
                }else{
                    return ['status' => 0, 'msg' => '添加失败'];
                }
            }
        }
    }
    /**
     * 判断添加的sku_id是否已经存在购物车列表中
     * @param $sku_id
     * @return array
     */
    public function isInCart($sku_id)
    {
        $cart = Cart::find()->where(['and', 'sku_id="' .$sku_id . '"', 'user_id=' . Yii::$app->user->id])->one();
        if($cart){
            return $cart;//购物车里有记录
        }else{
            return false;//购物车里没有记录
        }
        
    }
    
    
    /**
     * @return array
     * 购物车提交过来的数据，下单时进行order_sku数据插入及删除购物车数据
     * @throws yii\db\Exception
     */
    public function dealCart($order_id,$order_no,$CartArr){
        $ids=[];
        $pp=array();
        foreach($CartArr as $key=>$va){
            $ids[]=$va['product_id'].',';
        }
        $skus=Cart::find()->where(['and','user_id' =>Yii::$app->user->id, ['in','id',$ids]])->joinWith('skus')->asArray()->all();
        if(!empty($skus)){
            foreach ($skus as $key=>$va) {
                $order_sku[$key]['order_id']=$order_id;
                $order_sku[$key]['order_no']=$order_no;
                $order_sku[$key]['goods_id']=$va['product_id'];
                $order_sku[$key]['goods_name']=$va['product_name'];
                $order_sku[$key]['sku_id']=$va['sku_id'];
                $order_sku[$key]['sku_no']=$va['skus']['sku_num'];//sku商品条形码
                $order_sku[$key]['sku_image']=$va['skus']['image'];
                $order_sku[$key]['sku_thumbImg']=$va['skus']['thumbImg'];
                $order_sku[$key]['num']=$va['num'];//数量
                $order_sku[$key]['sku_market_price']=$va['skus']['market_price'];
                $order_sku[$key]['sku_sell_price_real']=$va['skus']['sale_price'];
                $order_sku[$key]['sku_value']=$va['sku_values'];
            }
            $connection = \Yii::$app->db;
            $tx = $connection->beginTransaction();
            //数据批量入库
            $tt= $connection->createCommand()->batchInsert(
                OrderSku::tableName(),
                ['order_id','order_no','goods_id','goods_name','sku_id','sku_no','sku_image','sku_thumbImg','num','sku_market_price','sku_sell_price_real','sku_value'],//字段
                $order_sku
                )->execute();
                if(!empty($tt)){
                    Cart::deleteAll(['and','user_id' =>Yii::$app->user->id, ['in','id',$ids]]);
                    $tx->commit();
                    return 1;
                }}else{
                    $tx->rollBack();
                    return -1;
                }
    }
    
    /**
     * 直接购物处理的逻辑
     */
    public function dealBuy($order_id,$order_no,$sku_id,$num){
        $skus=Skus::find()->joinWith('product')->where(['sku_id'=>$sku_id])->asArray()->one();
        
        $orderSku = new OrderSku();
        
        $orderSku->order_id=$order_id;
        $orderSku->order_no=$order_no;
        $orderSku->goods_id=$skus['product_id'];
        $orderSku->goods_name=$skus['product']['name'];
        $orderSku->sku_id=$skus['sku_id'];
        $orderSku->sku_no=$skus['sku_num'];//sku商品条形码
        $orderSku->sku_image=$skus['image'];
        $orderSku->sku_thumbImg=$skus['thumbImg'];
        $orderSku->num=$num;//数量
        $orderSku->sku_market_price=$skus['market_price'];
        $orderSku->sku_sell_price_real=$skus['sale_price'];
        $orderSku->sku_value=Tools::get_skus_value($skus['sku_values']);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $orderSku->save();
            $transaction->commit();
            return 1;
        } catch (\Exception $e) {
            $transaction->rollBack();
            //Yii::$app->session->setFlash('error', $e->getMessage());
            return -1;
        }
        
    }
    
    /**
     * 获取直接购买价格
     */
    public function buyPrice($sku_id){
        $data=Skus::findOne($sku_id);
        if(!empty($data['prom'])){
            $sale_price=$data['prom']['price'];
        }else{
            $sale_price=$data['sale_price'];
        }
        return ['sale_price'=>$sale_price,'market_price'=>$data['market_price']];
        
    }
    
    /**
     * 获取优惠券金额
     */
    public function  getCouponPrice($id=0){
        return 0;
        
    }
    /**
     * 获取满减金额
     */
    public function getPromPrice($id=0){
        return 0;
    }
    /**
     * 获取积分兑换金额
     */
    public function getIntegralPrice($num=0){
        return 0;
    }
    /**
     * 获取购物车商品数量,不是商品总数，是不同的sku商品总数
     */
    
    public function getNum($uid){
        if(empty($uid)){
            return 0;
        }
        $carts = $this->findCartByUser($uid);
        return count($carts);
    }
    
    /**
     * 确认下单
     * @param  $mid
     * @param  $params
     * @return
     * */
    public function checkout($uid,$params){
        $shopData = $params['shops'];//shop数组包含有一下内容shop = array('id'=>5,'citem_id'=>0,'cart_ids'=>[],'discount'=>200,'shippingPrice'=>20,'mark'=>'留言'):citem_id为使用优惠券id
        $type = isset($params['type'])? $params['type']:0;//0购物车下单1直接购买
        $scoreMoney = isset($params['scoreMoney'])? $params['scoreMoney']:0;
        $postCartIds = array();
        $shopCouponData = array();
        foreach ($shopData as $key=>$shop){
            $cart_ids = $shop['cart_ids'];
            $postCartIds = ArrayHelper::merge($postCartIds, $cart_ids);
            if(isset($shop['citem_id'])){
                $shopCouponData[$shop['id']] = $shop['citem_id'];//使用的优惠券列表id
            }  
        }
        $address=array();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
        if(isset($params['aid'])){  //实物配送
            $aid=$params['aid'];
            $addr = Address::find()->where(['uid'=>$uid,'id'=>$aid])->one();
            if (empty($addr)) {
                return ['status'=>0,'msg'=>'收货地址不存在！'];
            }
            $address['full_name']= $addr['userName'];
            $address['province_id']=$addr['province_id'];
            $address['city_id']=$addr['city_id'];
            $address['region_id']=$addr['region_id'];
            $address['address']=$addr['detailInfo'];
            $address['tel']=$addr['telNumber'];
        }else{                        //虚拟物品无需配送
            $aid=0;
            $address['full_name']=isset($params['name'])?$params['name']:'';
            $address['tel']=isset($params['mobile'])?$params['mobile']:'';
        }
        $address['delivery_id']=$params['delivery_id'];//正常快递
        $orderLogic = new OrderLogic();
        if ($type==0) {
            //从购物车购买
            $selectCart = $this->findSelected($uid);
        }else{
            //立即购买
            $selectCart = $this->findByBuy($uid,current($postCartIds));
        }
        if(empty($selectCart)){
            return ['status'=>0,'msg'=>'购物车已无数据，请返回购物车查看！'];
        }
        //验证cart_ids
        $selectIds = array_keys(ArrayHelper::index($selectCart, 'id'));
        $diffIds = array_diff($selectIds,$postCartIds);
        if (!empty($diffIds)) {
            return ['status'=>0,'msg'=>'购物车信息不一致，请返回购物车查看！'];
        }
      
        $shopCarts = $this->getShopCarts($selectCart);//以shop_id为id重构数组
        if(isset($params['point_id'])){
            $point_id=$params['point_id'];
        }else{
            $point_id=0;
        }
        $shopExtraCarts = $this->getExtraShopCartsWithShipping($shopCarts, $address, $uid,$point_id);// 在此处检测过sku信息
        foreach ($shopExtraCarts as $shopCarts){
            foreach ($shopCarts['data'] as $cart){
                if ($cart['skusStatus']['status']!=1) {
                    return $cart['skusStatus'];
                }
            }      
        }
        //积分验证
        $scoreLoigc = new ScoreLogic();
        if ($scoreMoney>0) {
            $change2score = $scoreLoigc->change2Score($scoreMoney);
            if ($change2score['status']!=1) {
                return Json::encode($change2score);
            }
            $member = Yii::$app->user->identity;
            if ($change2score['msg'] > $member['score']) {
                return ['status' => 0, 'msg' => '用户积分不足，请重试！'];
            }
        }
        $shopExtraCartsStatus = $this->setShopCartsIntegralMoney($shopExtraCarts, $scoreMoney);
        if($shopExtraCartsStatus['status']!=1){
            return $shopExtraCartsStatus;
        }
        $shopExtraCarts = $shopExtraCartsStatus['msg'];
        //$shopExtraCarts设置优惠券
        $shopExtraCartsStatus = $this->setShopCartsCoupon($shopExtraCarts, $uid,$shopCouponData);
        if($shopExtraCartsStatus['status']!=1){
            return $shopExtraCartsStatus;
        }
        $shopExtraCarts = $shopExtraCartsStatus['msg'];
        $orders = $orderLogic->getOrdersByCarts($shopExtraCarts);
        //post表单显示的数据进行验证有没有必要
         try {
            $transaction = Yii::$app->db->beginTransaction();
            $parentSn = 'pn_'.Tools::get_order_no();
            foreach ($orders as $key=> $shopOrder){   
                $order = $shopOrder['order'];
                //公共参数
                $order['m_id'] = $uid;
                $order['order_no'] = Tools::get_order_no();
                $order['parent_sn']=$parentSn;
                $order['is_shop_checkout']=1;//默认已经结算
                $order['payment_code']='weixin';//支付方式，默认为微信支付
                $order['shop_id']=$key;
                $order=array_merge($order,$address);
                foreach ($shopData as $data){
                    if ($data['id']==$key) {
                        //留言
                        $order['m_desc'] = $data['mark'];
                    }
                }
                $saveOrder = new Order();
                $saveOrder->loadDefaultValues();
                $saveOrder->load($order,'');
                $flagOrder = $saveOrder->save();  
                if (!$flagOrder) {
                    $transaction->rollBack();
                    return ['status'=>0,'msg'=>current($saveOrder->getFirstErrors())];
                }
                
                //活动期限及使用截止时间
      /*           $orderData=new OrderData();
                $product=Product::findOne(['product_id'=>$shopOrder['orderSkus'][0]['goods_id']]);
                if($product['start_time']&&$product['end_time']&&$product['use_start_time']&&$product['use_end_time']){
                    $orderData->order_id=$saveOrder->id;
                    $orderData->start_time=$product['start_time'];
                    $orderData->end_time=$product['end_time'];
                    $orderData->use_start_time=$product['use_start_time'];
                    $orderData->use_end_time=$product['use_end_time'];
                    $orderData->save();
                    if($orderData->hasErrors()){
                        $transaction->rollBack();
                        return ['status'=>0,'msg'=>'更新活动时间出错'];
                    }
                } */
                
                try {
                    $jobOrder=new JobOrder();
                    $jobOrder->id=$saveOrder->id;
                    $jobOrder->m_id=$uid;
                    Yii::$app->queue->delay(7200)->push($jobOrder);
                } catch (Exception $e) {
                }
            
                //coupons修改状态
                if(!empty($order['coupons_id'])){
                    $couponId = $order['coupons_id'];
                    $couponItem = CouponItem::findOne($couponId);
                    $couponItem->order_id = $saveOrder->id;
                    $couponItem->use_time = time();
                    if (!$couponItem->save()) {
                        $transaction->rollBack();
                        return ['status'=>0,'msg'=>'系统错误，优惠券更新失败！'];
                    }
                }
               //自提点
                if($order['delivery_id']==2&&isset($params['point_id'])&&$params['point_id']!=0){  //
                   $pick=new  OrderPick();
                   $pick->loadDefaultValues();
                   $pick->setScenario('create');
                   $pick->pick_id=$params['point_id'];
                   $pick->order_id=$saveOrder->id;
                   $pick->save();
                   if($pick->hasErrors()){
                       $transaction->rollBack();
                       return ['status'=>0,'msg'=>current($pick->getFirstErrors())];
                   }
                }
                //orderskus
                $orderSkus = $shopOrder['orderSkus'];
                foreach ($orderSkus as $orderSku){
                    $orderSku['order_id'] = $saveOrder->id;
                    $orderSku['order_no'] = $saveOrder->order_no;
                    $saveOrderSku = new OrderSku();
                    $saveOrderSku->load($orderSku,'');
                    $saveOrderSku->loadDefaultValues();
                    $flagOrderSku = $saveOrderSku->save();
                    if (!$flagOrderSku) {
                        $transaction->rollBack();
                        return ['status'=>0,'msg'=>current($saveOrderSku->getFirstErrors())];
                    }
                    /*     //分销不绑定关系
                        $product=Product::findOne(['product_id'=>$orderSku['goods_id']]);
                        $distributeOpen = Yii::$app->config->get('distribute_open');//判断分销模块是否开启
                        //判断是否有分销金额,生成分销记录
                        if($distributeOpen&&$product['distribute_money']>0){
                            $distribute=new DistributeLogic();
                            $distribute->goods_distribut(0,yii::$app->user->id, $product,$orderSku);
                        } */
                 
                }
                //下单减库存
                $stock=$orderLogic->min_stock($orderSkus);
                if($stock['status']!=1){
                    $transaction->rollBack();
                    return ['status'=>0,'msg'=>'库存更新失败！'];
                }
                //减掉积分兑换逻辑
                if ($saveOrder['integral']>0) {
                    $accountLogic = new AccountLogic();
                    $changeScore = $order['integral'];
                    $info = array();
                    $info['order'][] = $saveOrder->order_no;
                    $info = Json::encode($info);
                    $changeParams = array();
                    $changeParams['score'] = -$changeScore; 
                    $accountStatus = $accountLogic->changeAccount($uid, $changeParams, 1,$info, '订单消费'.$changeScore.'积分');
                    if($accountStatus['status']!=1){
                        $transaction->rollBack();
                        return $accountStatus;
                    }
                }
            }
            //删除购物车信息
            if (Cart::deleteAll(['in','id',$selectIds])!=count($selectIds)) {
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'购物车信息删除失败！'];
            }
            $transaction->commit();
            return ['status'=>1,'msg'=>$parentSn];
        }catch (StaleObjectException $e) {
            // 解决冲突的代码
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'积分变更失败！'];
        }catch (Exception $e){
            $transaction->rollBack();
            return ['status'=>0,'msg'=>$e->getMessage()];
        } 
    }
    
}