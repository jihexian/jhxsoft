<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com
 * Time:2018年11月14日 上午10:27:05
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace frontend\controllers;

use Yii;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use frontend\common\controllers\Controller;
use common\logic\CartLogic;
use common\models\Cart;
use common\logic\SkusLogic;
use yii\helpers\Json;
use common\models\Address;
use common\logic\ShippingLogic;
use common\helpers\Tools;
use common\models\Order;
use common\models\Member;
use common\models\AccountLog;
use common\models\Skus;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use common\logic\ScoreLogic;
use common\logic\OrderLogic;
use common\models\OrderSku;
use common\logic\AccountLogic;
use common\modules\coupon\models\CouponItem;
use common\models\Village;
use common\models\RegionLocal;

/**
 * Cart controller.
 */
class CartController extends Controller
{
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','ajax-list','del','confirm','ajax-price','checkout','add','price','cart4','calculator','change'],
                        'roles' => ['@'],
                    ],
                    
                ],
                
            ],
        ];
    }
	
    /**
      * 购物车首页.
     *
     * @return mixed
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
        //return Json::encode(['items'=>$list,'sale_real_total'=>$saleRealTotal,'buy_total'=>$buyTotal]);        
        
        return $this->render('index',['items'=>$list,'sale_real_total'=>$saleRealTotal,'buy_total'=>$buyTotal]);      
    }
  
    
    public function actionDel(){
        $uid = Yii::$app->user->id;
        if (Yii::$app->request->isPost) {
            $ids = Yii::$app->request->post('ids');//操作
            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach ($ids as $id){                    
                    $cart = Cart::find()->where(['user_id'=>$uid,'id'=>$id,'type'=>0])->one();
                    if (empty($cart)) {
                        $transaction->rollBack();
                        return Json::encode(['status'=>0,'msg'=>'参数错误！']);
                    }
                    if(!$cart->delete()){
                        $transaction->rollBack();
                        return Json::encode(['status'=>0,'msg'=>'系统错误，删除失败！']);
                    }
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }            
            return Json::encode(['status'=>1,'msg'=>'删除成功！']);
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
            $data['prom_type'] = $cart->prom_type;
            $data['stock_num'] = $cart->num + $num;
            $skusStatus = $skusLogic->validateSku($data);
            if($skusStatus['status']==1){
                $cart->num = $cart->num + $num;
                if ($cart->save()) {
                    return Json::encode(['status'=>1,'msg'=>'修改成功']);
                }else{
                    return Json::encode(['status'=>0,'msg'=>'修改失败']);
                }
            }else{
                return Json::encode($skusStatus);
            }
        }
    }
    

    public function actionCheckout(){
        $uid = Yii::$app->user->id;
        if (Yii::$app->request->isPost) {


            $params = Yii::$app->request->post();
            $cartLogic = new CartLogic();            
            $result = $cartLogic->checkout($uid, $params);
            return Json::encode($result);

        }
    }
    
    public function actionAdd(){
        $data = array();
        $data['stock_num'] = Yii::$app->request->post('num');//商品数量
        $data['sku_id']=Yii::$app->request->post('sku_id');
        $data['prom_type']=Yii::$app->request->post('prom_type');//营销活动类型
        $data['prom_id']=Yii::$app->request->post('prom_id');//商品参加的活动
        $data['retry']=Yii::$app->request->post('retry',0);//是否重选
        $data['user_id'] = Yii::$app->user->id;
        $data['session_id'] = Yii::$app->session->id;
        $data['type'] = Yii::$app->request->post('type',0);//type=0添加到购物车，1，直接购买
        $cartLogic = new CartLogic();
        $result = $cartLogic->addCart($data);
        $num = $cartLogic->getNum($data['user_id']);
        return Json::encode(array_merge($result,['num'=>$num]));
    }

    
    public function actionOneAdd(){
        $data = array();
        $data['stock_num'] =1;//商品数量
        $productId=Yii::$app->request->post('goods_id');
        $skus = Skus::find()->where(['product_id'=>$productId])->orderBy('sku_id desc')->one();
        $data['sku_id']=$skus['sku_id'];
        $data['prom_type']=Yii::$app->request->post('prom_type');//营销活动类型
        $data['prom_id']=Yii::$app->request->post('prom_id');//商品参加的活动
        $data['retry']=0;//是否重选
        $data['user_id'] = Yii::$app->user->id;
        $data['session_id'] = Yii::$app->session->id;
        $data['type'] =0;
        $cartLogic = new CartLogic();
        $result = $cartLogic->addCart($data);
        $num = $cartLogic->getNum($data['user_id']);
        return Json::encode(array_merge($result,['num'=>$num]));
    }
    /**
     * 选择支付方式 
     */
    public function actionCart4($parent_sn){
       
        $orders= Order::find()->where(['m_id'=>Yii::$app->user->id,'parent_sn'=>$parent_sn])->all();
        if (empty($orders)) {
            throw new NotFoundHttpException('订单不存在');
        }
        $su=array();
        $total=0;
        foreach ($orders as $order) {
            if($order['payment_status']==1){
                throw new Exception('订单已经支付');
            }
            $total+=$order['pay_amount']; 
           
        }  
        $su['pay_amount']=$total;
        $su['create_time']=$orders[0]['create_time'];
        return  $this->render('cart4',[
            'order'=>$su,
            'parent_sn'=>$parent_sn,
        ]);
    }
    
    /**
     * [计算购物车已经选择的商品总价，并返回全部购物车商品信息]
     * @return array $cart
     *
     */
    public function actionCalculator(){
        //购车车所有的信息
        $cart_ids = Yii::$app->request->post('cart_ids',[]);
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
        
        return Json::encode(['status'=>1,'total'=>$cartProps['sale_real_total']]);
    }
    
    /**
     * 生成订单页面接口
     *@return item:以shopId为索引的购物车数组，包含了运费等信息，scoreMoney:积分可兑换金额，amoun订单总金额
     */
    public function actionConfirm(){
        
        $cartLogic = new CartLogic();
        $uid=Yii::$app->user->id;
        $type = Yii::$app->request->get('type',0);
        $cartId = Yii::$app->request->get('id');
        $point_id=yii::$app->request->get('point_id');
        $addressId = Yii::$app->request->get('address');
       
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
        $shopCarts = $cartLogic->getExtraShopCartsWithShipping($shopCarts, $address, $uid,$point_id);
        $shopCarts = $cartLogic->getExtraShopCartsWithCoupon($shopCarts, $uid);
        if ($type==1) {
            //立即购买，验证是否有商品异常
            foreach ($shopCarts as $carts){
                foreach ($carts['data'] as $cart)
                if ($cart['skusStatus']['status']!=1) {
                    Yii::$app->session->setFlash('error', $cart['skusStatus']['msg'].'请重新购买');
                    return $this->goBack();
                }
            }
        }
        foreach ($shopCarts as $cart){            
            isset($cart['shipping_price'])? $shippingPrice = $cart['shipping_price']:$shippingPrice = 0;
            $amount += $shippingPrice + $cart['sale_real_total'];
            $buyTotal += $cart['buy_total'];
        }
        $scoreMoney = 0;
        //判断是否可用积分兑换
        $scoreLogic = new ScoreLogic();
        $member = Member::findOne(['id'=>$uid]);
        $score = $member['score'];
        $use4Money = $scoreLogic->use4Money($score);
        if($use4Money['status']==1){
            $scoreMoney = $use4Money['msg'];
            $scoreMoney >$amount? $scoreMoney = $amount:0;
        }

        $delivery_id=yii::$app->request->get('delivery_id',0);
        $point_id=yii::$app->request->get('point_id');
        return $this->render('confirm',
            ['items'=>$shopCarts,'scoreMoney'=> bcadd($scoreMoney, 0,2),'amount'=>bcadd($amount, 0,2),'buy_total'=>$buyTotal,'address'=>$address,
                'type'=>$type,'id'=>$cartId,'delivery_id'=>$delivery_id,'point_id'=>$point_id,'addressId'=>$addressId
        ]);     
    }

}
