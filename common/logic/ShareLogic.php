<?php
/**
 * Author: vamper  
 * Time: 2018-10-25
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace common\logic;
use common\components\Controller;
use yii;
use yii\helpers\Json;
use common\models\Product;
use common\models\ShareInfo;
use common\models\OrderSku;
use common\models\Order;
use common\models\AccountLog;
use api\modules\v1\models\Member;
class ShareLogic{
	private $share = array('fenxiang');
  	
	//检查是否开启了具体的活动模块
	public function checkOpen($share_type){
		$flag = 0;
		if(in_array($share_type, $this->share)){
			$flag = 1;
		}
		return $flag;
	}
	/**
	 * 分享奖励
	 */
	public function setShareReward($orderSkuId){
		if (!$this->checkOpen('fenxiang')){
			return ['status'=>1,'msg'=>'不用增加积分'];
		}
		
		$orderSku = OrderSku::findOne($orderSkuId);
		$order = Order::findOne($orderSku['order_id']);
		$shareInfo = ShareInfo::find()->where(['mid'=>$order->m_id,'product_id'=>$orderSku['goods_id']])->one();
		$changeScore = intval($orderSku->num*$orderSku->sku_sell_price_real);
		if ($changeScore<=0){
			return ['status'=>1,'msg'=>'不用增加积分'];
		}
		if (!empty($shareInfo)){
		    $shareMid = $shareInfo->share_mid;
		    $changeScore = intval($orderSku->num*$orderSku->sku_sell_price_real);
    		$accountLogic = new AccountLogic();
    		$info = array();
    		$info['order'][] = $order->order_no;
    		$info = Json::encode($info);
    		$changeParams = array();
    		$changeParams['score'] = $changeScore;
    		$account_status=$accountLogic->changeAccount($shareMid, $changeParams, 3,$info,'分享奖励：增加'.$changeScore.'积分' );
    		
    		return $account_status;
		}else{
			return ['status'=>1,'msg'=>'不用增加积分'];
		}
	}
	/**
	 * 更新分享关系	 
	 */
	public function updateShareInfo($mid,$pid,$shareMid){
		if (!$this->checkOpen('fenxiang')||$mid==$shareMid){
			return;
		}
		$shareInfo = ShareInfo::find()->where(['mid'=>$mid,'product_id'=>$pid])->one();
		if (empty($shareInfo)){
			$shareInfo = new ShareInfo();
			$shareInfo->mid = $mid;
			$shareInfo->product_id = $pid;
			$shareInfo->share_mid = $shareMid;
			$shareInfo->save();
			
		}else{
			if ($shareInfo->share_mid!=$shareMid){
				$shareInfo->share_mid = $shareMid;
				$shareInfo->save();
			}
		}
	}
}
