<?php
/**
 * 购物车逻辑实现接口
 * @author wsyone wsyone@foxmail.com
 * @date 2018年5月7日下午5:32:53
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: https://www.jihexian.com
 */
namespace api\modules\v1\controllers;
use api\common\controllers\Controller;
use common\models\Cart;
use common\models\Skus;
use common\models\Product;
use common\logic\CartLogic;
use common\logic\SkusLogic;
use common\models\Address;
use common\models\Order;
use common\models\Member;
use common\logic\ShippingLogic;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use common\helpers\Tools;
use yii;
use common\models\OrderLog;
use common\models\AccountLog;
use common\logic\AccountLogic;
use common\logic\ScoreLogic;
use common\logic\OrderLogic;
use common\helpers\Util;
use common\models\Shop;
use yii\helpers\Json;
use common\models\OrderSku;


class CartController extends Controller
{


    public $userId;
    public $user;

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
     * 购物车列表
     * @sku_id
     * @return  
     */
    public function actionIndex()
    {
        //列出购物车数据
        $cartLogic = new CartLogic();
        
        $uid=Yii::$app->user->id;
        $carts = $cartLogic->findCartByUser($uid);
        //$carts = $cartLogic->addImage($carts); 
        $carts = $cartLogic->getShopCarts($carts);  
        
        $list = $cartLogic->getExtraShopCarts($carts);
        $saleRealTotal = 0;
        $buyTotal = 0;
        foreach ($list as $v){
            $saleRealTotal += $v['sale_real_total'];
            $buyTotal += $v['buy_total'];
        }
        return ['items'=>$list,'sale_real_total'=>$saleRealTotal,'buy_total'=>$buyTotal];
    }
    
    /**
     * 购物车添加
     *
     */
    public function actionAdd(){
        $data = array();
        $data['stock_num'] = Yii::$app->request->post('num');//商品数量
        $data['sku_id']=Yii::$app->request->post('sku_id');        
        $data['prom_type']=Yii::$app->request->post('prom_type');//营销活动类型
        $data['prom_id']=Yii::$app->request->post('prom_id');//商品参加的活动
        $data['retry']=Yii::$app->request->post('retry',1);//是否重选
        $data['user_id'] = Yii::$app->user->id;
        $data['session_id'] = Yii::$app->session->id;
        $data['type'] = Yii::$app->request->post('type',0);//type=0添加到购物车，1，直接购买
        $cartLogic = new CartLogic();
        return $cartLogic->addCart($data);       
    }
   

    /**
     * 删除购物车单商品条目
     * @return 
     */
    public function actionDelete(){
        $uid = Yii::$app->user->id;
        if (Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('id');//操作
            $cart = Cart::find()->where(['user_id'=>$uid,'id'=>$id,'type'=>0])->one();
            if (empty($cart)) {
                return ['status'=>0,'msg'=>'id错误！'];
            }
            if($cart->delete()){
                return ['status'=>1,'msg'=>'删除成功！'];
            }else{
                return ['status'=>0,'msg'=>'系统错误，删除失败！'];
            }            
        }
    }
    /**
     * 购物车修改数量
     * @return 
     */
    public function actionChange(){
        $uid = Yii::$app->user->id;
        if (Yii::$app->request->isPost) {
            $num = Yii::$app->request->post('num');//正数增加，负数减少
            $id = Yii::$app->request->post('id');//操作
            $cart = Cart::find()->where(['user_id'=>$uid,'id'=>$id,'type'=>0])->one();
            $skusLogic = new SkusLogic($cart['sku_id']);
            $data = array();
            $data['prom_type'] = $cart->prom_type;
            $data['prom_id'] = $cart->prom_id;
            $data['stock_num'] = $cart->num + $num;
            $skusStatus = $skusLogic->validateSku($data);
            if($skusStatus['status']==1){
                $cart->num = $cart->num + $num;
                if ($cart->save()) {
                    return ['status'=>1,'msg'=>'修改成功'];
                }else{
                    return ['status'=>0,'msg'=>'修改失败'];
                }
            }else{
                return $skusStatus;
            }
        }
    }
    /**
     * 生成订单
     * @return string
     */
    public function actionCheckout(){
        $uid = Yii::$app->user->id;
        if (Yii::$app->request->isPost) {
            $params = Yii::$app->request->post();
            $cartLogic = new CartLogic();            
            $result = $cartLogic->checkout($uid, $params);
            return $result;
        }
    }

    /**
     * [计算购物车已经选择的商品总价，并返回全部购物车商品信息]
     * @return array $cart
     *
     */
    public function actionCalculator(){
        //购车车所有的信息
        $cart_ids = Yii::$app->request->post('cart_ids');
        $uid = yii::$app->user->id;
        $cartLogic = new  CartLogic();
        $carts = $cartLogic->findCartByUser($uid);
        $ids = array_keys(ArrayHelper::index($carts, 'id'));
        //过滤
        $selectIds = array_intersect($cart_ids, $ids);
        //先更新全部为0
        Cart::updateAll(['selected' =>0], ['user_id'=>$uid,'type'=>0]);
        //再更新为1
        Cart::updateAll(['selected' =>1], ['in','id',$selectIds]);        
        $selectCarts = $cartLogic->findSelected($uid);
        $cartProps = $cartLogic->getSelectdCartExtraProps($selectCarts);  
        
        return ['status'=>1,'total'=>$cartProps['sale_real_total']];
    }
    

    /**
     * [actionEestory 清空购物车]
     * @return 
     */
    public function actionEestory(){
        if(Cart::deleteAll(['user_id' => Yii::$app->user->id])){
            return ['status' => 1, 'msg' => '删除成功', 'result' => ''];
        }else{
            return ['status' => 0, 'msg' => '删除失败', 'result' => ''];
        }

    }
    /**
     * 生成订单页面接口
     *@return item:以shopId为索引的购物车数组，包含了运费等信息，scoreMoney:积分可兑换金额，amoun订单总金额
     */
    public function actionGetdata(){
        
        $cartLogic = new CartLogic();
        $uid=Yii::$app->user->id;
        $type = Yii::$app->request->post('type');
        $cartId = Yii::$app->request->post('id');
        $addressId = Yii::$app->request->post('address_id');
        
        if($addressId){                     //使用选择 的地址
            $address=Address::find()->where(['id'=>$addressId,'uid'=>$uid])->one();
        }else {
            $address=Address::find()->where(['is_default'=>1,'uid'=>$uid])->one();//使用默认
        }
        
        $amount = 0 ;//订单总价
        $buyTotal = 0;//商品数量总计
        if ($type==0) {//从购物车购买
            $selectCart = $cartLogic->findSelected($uid);
                       
        }else{
            //立即购买
            $selectCart = $cartLogic->findByBuy($uid,$cartId);
        } 
        //$selectCart = $cartLogic->addImage($selectCart);
        $shopCarts = $cartLogic->getShopCarts($selectCart);
        $shopCarts = $cartLogic->getExtraShopCartsWithShipping($shopCarts, $address, $uid);
        foreach ($shopCarts as $cart){
            isset($cart['shipping_price'])? $shippingPrice = $cart['shipping_price']:$shippingPrice = 0;
            $amount += $shippingPrice + $cart['sale_real_total'];
            $buyTotal += $cart['buy_total'];
        } 
        $scoreMoney = 0;
        //判断是否可用积分兑换
        $scoreLogic = new ScoreLogic();
        $member = Member::findOne($uid);
        $score = $member['score'];
        $use4Money = $scoreLogic->use4Money($score);
        if($use4Money['status']==1){            
            $scoreMoney = $use4Money['msg'];
            $scoreMoney >$amount? $scoreMoney = $amount:0;
        }
        
        return ['items'=>$shopCarts,'scoreMoney'=> bcadd($scoreMoney, 0,2),'amount'=>bcadd($amount, 0,2),'buy_total'=>$buyTotal];
    }


    /**
     * 生成订单页面接口
     *@return item:以shopId为索引的购物车数组，包含了运费等信息，scoreMoney:积分可兑换金额，amoun订单总金额
     */
    public function actionConfirm(){
        
        $cartLogic = new CartLogic();
        $uid=Yii::$app->user->id;
        if (Yii::$app->request->isPost) {
            $type = Yii::$app->request->post('type');
            $cartId = Yii::$app->request->post('id');
            $addressId = Yii::$app->request->post('address_id');
        }else{
            $type = Yii::$app->request->get('type');
            $cartId = Yii::$app->request->get('id');
            $addressId = Yii::$app->request->get('address_id');
        }
        
        if($addressId){                     //使用选择 的地址
            $address= Address::find()->where(['id'=>$addressId,'uid'=>$uid])->one();
        }else {
            $address=Address::find()->where(['is_default'=>1,'uid'=>$uid])->one();//使用默认
        }
       
        $amount = 0 ;//订单总价
        $buyTotal = 0;//商品数量总计
        if ($type==0) {//从购物车购买
            $selectCart = $cartLogic->findSelected($uid);
        }else{
            //立即购买
            $selectCart = $cartLogic->findByBuy($uid,$cartId);
        }
       /*  以,shop_id作为索引重构数组 */
        $shopCarts = $cartLogic->getShopCarts($selectCart);
      /*   在此处检测过sku信息 */
        $shopCarts = $cartLogic->getExtraShopCartsWithShipping($shopCarts, $address, $uid);
        $shopCarts = $cartLogic->getExtraShopCartsWithCoupon($shopCarts, $uid);
        if ($type==1) {
            //立即购买，验证是否有商品异常
            foreach ($shopCarts as $carts){
                foreach ($carts['data'] as $cart){
                    if ($cart['skusStatus']['status']!=1) {
                        return ['status'=>$cart['skusStatus']['status'],'msg'=>$cart['skusStatus']['msg'].'请重新购买'];
                    }
                }
                    
            }
        }
        foreach ($shopCarts as $cart){
            isset($cart['shipping_price'])? $shippingPrice = $cart['shipping_price']:$shippingPrice = 0;
            $amount += $shippingPrice + $cart['sale_real_total'];
            $buyTotal += $cart['buy_total'];
            foreach ($cart['data'] as $v){
                if ($v['skusStatus']['status']!=1) {
                    return ['status'=>$v['skusStatus']['status'],'msg'=>$v['skusStatus']['msg'].'请重新购买'];
                }
            }
        }
        $scoreMoney = 0;
        //判断是否可用积分兑换
        $scoreLogic = new ScoreLogic();
        $member = Member::findOne($uid);
        $score = $member['score'];
        $use4Money = $scoreLogic->use4Money($score);
        if($use4Money['status']==1){
            $scoreMoney = $use4Money['msg'];
            $scoreMoney >$amount? $scoreMoney = $amount:0;
        }
        //         print_r($shopCarts);exit();
        return ['status'=>1,'items'=>$shopCarts,'scoreMoney'=> bcadd($scoreMoney, 0,2),'amount'=>bcadd($amount, 0,2),'buy_total'=>$buyTotal];
        
        //return ['items'=>$shopCarts,'scoreMoney'=> bcadd($scoreMoney, 0,2),'amount'=>bcadd($amount, 0,2),'buy_total'=>$buyTotal];
//         return $this->render('confirm',['items'=>$shopCarts,'scoreMoney'=> bcadd($scoreMoney, 0,2),'amount'=>bcadd($amount, 0,2),'buy_total'=>$buyTotal,'address'=>$address,
//             'type'=>$type,'id'=>$cartId
//         ]);
    }

}