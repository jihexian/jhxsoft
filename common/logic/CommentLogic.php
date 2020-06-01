<?php
/**
 *
 * Author: vamper  
 * Time: 2018-05-30 17:35
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace common\logic;
use common\models\DistributLog;
use common\models\Order;
use common\models\OrderSku;
use common\models\ProductComment;
use Yii;


class CommentLogic{
  
	/**
	 * 获取好评中评差评数量
	 */
	public function getCounts($productId){		
		$result = array();
		//全部
		$result['all_count']=ProductComment::find()->where(['yj_product_comment.status'=>1,'pid'=>0,'goods_id'=>$productId,'appraise'=>3])->count();	
		//好评数
		$result['good_count']=ProductComment::find()->where(['yj_product_comment.status'=>1,'pid'=>0,'goods_id'=>$productId,'appraise'=>3])->count();			
		//中评数
		$result['mid_count']=ProductComment::find()->where(['yj_product_comment.status'=>1,'pid'=>0,'goods_id'=>$productId,'appraise'=>2])->count();
		//差评
		$result['bad_count']=ProductComment::find()->where(['yj_product_comment.status'=>1,'pid'=>0,'goods_id'=>$productId,'appraise'=>1])->count();
		$result['haopinglv']= $result['all_count']==0?1:$result['good_count']/$result['all_count'];
		return $result;	
	}
    
    /**
     * 
     * @param  $data //格式:数组:{'uid','order_sku_id','total_stars','product_comment表其他字段'}
     * @return 
     */
	public function addComment($data){
	    $transaction = Yii::$app->db->beginTransaction();
	    try {
	        $uid = $data['uid'];
	        if(isset($data['shop_id'])){
	        unset($data['shop_id']);
	        }
	        $order_sku=OrderSku::findOne($data['order_sku_id']);
	        $order = Order::find()->where(['id'=>$order_sku['order_id'],'m_id'=>$uid])->one();
	        if(empty($order)||empty($order_sku)){
	            return ['status'=>0,'msg'=>'参数错误'];
	        }
	        if ($order['status']!=4){
	            return ['status'=>0,'msg'=>'该状态不可评价'];
	        }
	        if ($order_sku->is_comment==1){
	            return ['status'=>0,'msg'=>'您已经评价过了'];
	        }
	        $productComment = new ProductComment();        
	        $data['member_id'] = $order->m_id;
	        $data['goods_id'] = $order_sku['goods_id'];
	        if ($data['total_stars']<2){
	            $data['appraise'] = 1;
	        }else if($data['total_stars']>=2&&$data['total_stars']<4){
	            $data['appraise'] = 2;
	        }else if($data['total_stars']>=4&&$data['total_stars']<6){
	            $data['appraise'] = 3;
	        }
	        $productComment->load($data, '');
	        $productComment->save(); 
	        if($productComment->hasErrors()) {
	            $transaction->rollBack();
	            return ['status'=>-1,'msg'=>current($productComment->getFirstErrors())];
	        }
	       
	        if($order->delivery_id==0){ 
	            //购物获取积分
	            $scoreLogic = new ScoreLogic();
	            $scoreStatus=$scoreLogic->addScore($order_sku);
	            if($scoreStatus['status']!=1){
	                $transaction->rollBack();
	                return ['status'=>-1,'msg'=>'更新积分失败'];
	            }   
	        }
	    
	        //分享积分
	    /*     $shareLogic = new ShareLogic();
	        $shareResult = $shareLogic->setShareReward($data['order_sku_id']);
	        if ($shareResult['status']!=1){
	            $transaction->rollBack();
	            return $shareResult;
	        }
	         */
	        //更新order_skus已点评
	        $order_sku->is_comment=1;
	        $order_sku->save();
	        if($order_sku->hasErrors()) {
	            $transaction->rollBack();
	            return ['status'=>-2,'msg'=> current($order_sku->getFirstErrors())];
	        }
	        //更新如果订单关联的order_skus都已经评论完了，改变order表状态
	        
	        $count=OrderSku::find()->where(['order_id'=>$order_sku['order_id'],'is_comment'=>0])->count();    
	        if($count==0) {
	            $order = Order::find()->where(['id' => $order_sku['order_id']])->one();
	            $order->status = 5;
	            $order->save();
	            if ($order->hasErrors()) {
	                $transaction->rollBack();
	                return ['status' => -3, 'msg' =>current($order->getFirstErrors())];
	            }
	            //分销结算抽成
	            $distribut=DistributLog::find()
	            ->where(['and',['status'=>2],['cid'=>$order->m_id],['order_no'=>$order['order_no']]])
	            ->all();
	            if($distribut){
	                $distribut_log=new DistributeLogic();
	                $su= $distribut_log->changestatus($distribut);
	                if($su['status']!=1){
	                    $transaction->rollBack();
	                    return ['status' => 0, 'msg' =>$su['msg']];
	                }
	            }
	            //店铺资金结算（平台抽佣，店铺资金结算）
	            $log=new ShopCommissionLogic();
	            $com=$log->Log(0, $order['id'], $order->m_id);
	            if($com['status']!=1){
	                $transaction->rollBack();

	                return ['status' => 0, 'msg' =>$com['msg']];
	            }
	        }
	        $transaction->commit();
	        return ['status'=>1,'msg'=>'评价成功'];
	    } catch(\Exception $e) {
	        $transaction->rollBack();
	        return ['status'=>0,'msg'=>$e->getMessage()];
	    }
	}
}
