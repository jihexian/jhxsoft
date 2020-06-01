<?php
/**
 * 
 * Author vamper 944969253@qq.com
 * Time:2018年11月30日 下午4:03:39
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\logic;

use Yii;
use yii\helpers\Json;
use common\models\AccountLog;

;
class ScoreLogic{
    //如果前台使用积分结算，获取购物车勾选的商品所需要积分总数
    public function getCartScore($carts){
        //获取购物车商品总额
        foreach ($carts as $key=>$cart){
            
        }
    }
    /**
     * 使用积分抵扣金额
     * @param $num:使用积分数量
     * @return float
     */
    public function use4Money($num){
        //积分使用开关
        $status = 1;
        if (!$status) {
            return ['status'=>0,'msg'=>'商城未开启积分抵扣金额功能'];
        }
        if ($num<=0) {
            return ['status'=>0,'msg'=>'使用积分必须大于0'];
        }
        $ratio = $this->getRatio();
        if ($ratio<=0) {
            return ['status'=>0,'msg'=>'商城未设置积分兑换比例'];
        }
        $changeMoney = bcadd($num/$ratio, 0,2);
        if ($changeMoney<=0) {
            return ['status'=>0,'msg'=>'兑换积分不足'];
        }
        return ['status'=>1,'msg'=>bcadd($changeMoney, 0,2)];;
    }
    /**
     * 使用金额转换积分
     * @param $money金额
     * @return float
     */
    public function change2Score($money){
        //积分使用开关
        $status = 1;
        if (!$status) {
            return ['status'=>0,'msg'=>'商城未开启积分抵扣金额功能'];
        }
        if ($money<=0) {
            return ['status'=>0,'msg'=>'转化金额必须大于0'];
        }
        $ratio = $this->getRatio();
        if ($ratio<=0) {
            return ['status'=>0,'msg'=>'商城未设置积分兑换比例'];
        }
        $changeScore = bcadd($money*$ratio,0,0);
        
        return ['status'=>1,'msg'=>$changeScore];;
    }
    
    
    /**
     * 获取积分兑换比例
     * 兑换单位为元
     * @return integer ：1块钱可兑换的积分数量
     */
    public function getRatio(){
        return Yii::$app->config->get('site_credits_exchange');
    }
    
    /**
     * 购买商品、点评后获取积分
     * @param  $sku_id
     */
    public function addScore($order_sku){
        $ratio=$order_sku->product->score*0.01;//获取商品积分比例
        $changeScore=round($ratio*$order_sku->sku_sell_price_real*$order_sku->num,0);//订单特定skuid 评价后能获取的积分总值,四舍五入取整
       
        if($changeScore>0){
            $accountLogic = new AccountLogic();
            $info = array();
            $info['order'][] = $order_sku->order->order_no;
            $info = Json::encode($info);
            $changeParams = array();
            $changeParams['score'] = $changeScore;
            $account_status=$accountLogic->changeAccount($order_sku->order->m_id, $changeParams, 3,$info,'购物奖励：增加'.$changeScore.'积分' );    
            return $account_status;
        }else{
            return ['status'=>1,'msg'=>'不用增加积分'];
        }
      
    }
}
