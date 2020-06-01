<?php
/**
 * 
 * Author: vamper  
 * Time: 2018-07-19 16:49
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace common\logic;
use common\components\Controller;
use common\models\Cart;
use common\models\Skus;
use common\helpers\Tools;
use common\models\OrderSku;
use yii;
use common\models\Order;
use common\models\Member;
use common\models\OrderRefundDoc;
class AnalysisLogic{
    public $obj;
    public function __construct()
    {
        $this->obj=new Cart();
    }
	/**
	 * 获取销售总额，包括退款的订单
	 */
    public function getOrderAmount($type,$data){
    	$result = array();
    	$range = $this->getRange($data, $type);
    	$result['labels'] = $range;
    	$result['orderAmount'] = array();
    	foreach ($range as $vo){
    		$searchRange = $this->getSearchRange($type, $vo);
    		$beginTime = $searchRange['beginTime'];
    		$endTime = $searchRange['endTime'];
    		$orderAmount = Order::find()->andWhere(['payment_status'=>1])
    			->andWhere(['>','create_time',$beginTime])
    			->andWhere(['<','create_time',$endTime])->sum('pay_amount');    		
    	    array_push($result['orderAmount'], empty($orderAmount)? 0:$orderAmount);    
    	}
    	
    	return $result;
    }
    /**
     * 获取成功交易的订单总数，包括退款的订单
     */
    public function getOrderCount($type,$data){
    	$result = array();
    	$range = $this->getRange($data, $type);
    	$result['labels'] = $range;
    	$result['orderCount'] = array();
    	foreach ($range as $vo){
    		$searchRange = $this->getSearchRange($type, $vo);
    		$beginTime = $searchRange['beginTime'];
    		$endTime = $searchRange['endTime'];
    		$orderCount = Order::find()->andWhere(['payment_status'=>1])
    		->andWhere(['>','create_time',$beginTime])
    		->andWhere(['<','create_time',$endTime])->count();
    		array_push($result['orderCount'], empty($orderCount)? 0:$orderCount);    
    	}
    	 
    	return $result;
    }
    /**
     * 获取退款成功总额,仅统计退款成功的订单
     */
    public function getRefundAmount($type,$data){
    	$result = array();
    	$range = $this->getRange($data, $type);
    	$result['labels'] = $range;
    	$result['orderRefundAmount'] = array();
    	foreach ($range as $vo){
    		$searchRange = $this->getSearchRange($type, $vo);
    		$beginTime = $searchRange['beginTime'];
    		$endTime = $searchRange['endTime'];
    		$orderRefundAmount = OrderRefundDoc::find()->andWhere(['status'=>2])
    		->andWhere(['>','addtime',$beginTime])
    		->andWhere(['<','addtime',$endTime])->sum('amount');
    		array_push($result['orderRefundAmount'], empty($orderRefundAmount)? 0:$orderRefundAmount
    		    );    
    	}    	 
    	return $result;
    	 
    }
    /**
     * 获取退款成功个数,仅统计退款成功的个数
     */
    public function getRefundCount($type,$data){
        $result = array();
        $range = $this->getRange($data, $type);
        $result['labels'] = $range;
        $result['orderRefundCount'] = array();
        foreach ($range as $vo){
            $searchRange = $this->getSearchRange($type, $vo);
            $beginTime = $searchRange['beginTime'];
            $endTime = $searchRange['endTime'];
            $orderRefundCount = OrderRefundDoc::find()->andWhere(['status'=>2])
            ->andWhere(['>','addtime',$beginTime])
            ->andWhere(['<','addtime',$endTime])->count();
            array_push($result['orderRefundCount'], empty($orderRefundCount)? 0:$orderRefundCount
                );
        }
        return $result;
        
    }
    /**
     * 获取待退款总额,仅统计待退款的订单
     */
    public function getRefundUndoAmount($type,$data){
        $result = array();
        $range = $this->getRange($data, $type);
        $result['labels'] = $range;
        $result['orderRefundUndoAmount'] = array();
        foreach ($range as $vo){
            $searchRange = $this->getSearchRange($type, $vo);
            $beginTime = $searchRange['beginTime'];
            $endTime = $searchRange['endTime'];
            $orderRefundUndoAmount = OrderRefundDoc::find()->andWhere(['status'=>0])
            ->andWhere(['>','addtime',$beginTime])
            ->andWhere(['<','addtime',$endTime])->sum('amount');
            array_push($result['orderRefundUndoAmount'], empty($orderRefundUndoAmount)? 0:$orderRefundUndoAmount
                );
        }
        return $result;
        
    }
    /**
     * 获取待退款个数,仅统计待退款的个数
     */
    public function getRefundUndoCount($type,$data){
        $result = array();
        $range = $this->getRange($data, $type);
        $result['labels'] = $range;
        $result['orderRefundUndoCount'] = array();
        foreach ($range as $vo){
            $searchRange = $this->getSearchRange($type, $vo);
            $beginTime = $searchRange['beginTime'];
            $endTime = $searchRange['endTime'];
            $orderRefundUndoCount = OrderRefundDoc::find()->andWhere(['status'=>0])
            ->andWhere(['>','addtime',$beginTime])
            ->andWhere(['<','addtime',$endTime])->count();
            array_push($result['orderRefundUndoCount'], empty($orderRefundUndoCount)? 0:$orderRefundUndoCount
                );
        }
        return $result;        
    }
    
    
    /**
     * 获取关注总数
     */
    public function getFollowCount($type,$data){
    	$result = array();
    	$range = $this->getRange($data, $type);
    	$result['labels'] = $range;
    	$result['followCount'] = array();
    	foreach ($range as $vo){
    		$searchRange = $this->getSearchRange($type, $vo);
    		$beginTime = $searchRange['beginTime'];
    		$endTime = $searchRange['endTime'];
    		$memberCount = Member::find()->andWhere(['status'=>1])
    		->andWhere(['>','register_time',$beginTime])
    		->andWhere(['<','register_time',$endTime])->count();
    		array_push($result['followCount'], empty($memberCount)? 0:$memberCount);    		
    	}
    	
    	return $result;
    }
    /**
     * 获取商品销售额总数
     */
    public function getProductCount($data){
        $params = [':beginTime' => strtotime($data['beginTime']), ':endTime' => strtotime($data['endTime']." +24 hours"),':shop_id'=>Yii::$app->session->get('shop_id')];
        $result = Yii::$app->db->createCommand('select sum(r.amount) as amount,sum(r.num) as total,name from (select sku_sell_price_real*num as amount,p.name,num,p.product_id from yj_order od left join  yj_order_sku o on od.id = o.order_id left join yj_product p on o.goods_id = p.product_id where od.shop_id=:shop_id and od.payment_status=1 and o.is_refund<>2 and od.create_time>=:beginTime and od.create_time<=:endTime group by p.product_id,o.id ) as r group by r.product_id;')
        ->bindValues($params)
        ->queryAll();        
        return $result;
    	
    }
    /**
     * 获取商品退款总数
     */
    public function getProductRefundCount(){
    	 
    }
    /**
     * 获取商品销售总额
     */
    public function getProductAmount(){
    	 
    }
    /**
     * 获取商品退款总额
     */
    public function getProductRefundAmount(){
    
    }
    /**
     * 获取商品分类销售总数
     * 
     */
    public function getCategoryCount($data){
    	
        $params = [':beginTime' => strtotime($data['beginTime']), ':endTime' => strtotime($data['endTime']." +24 hours"),':shop_id'=>Yii::$app->session->get('shop_id')];    	
    	$result = Yii::$app->db->createCommand('select sum(r.amount) as amount,sum(r.num) as total ,r.cat_name from (select sku_sell_price_real*num as amount,c.category_id,c.cat_name,num from yj_order od left join  yj_order_sku o on od.id = o.order_id left join yj_product p on o.goods_id = p.product_id left join yj_product_category c on p.cat_id = c.category_id where od.shop_id=:shop_id and od.payment_status=1 and o.is_refund<>2 and od.create_time>=:beginTime and od.create_time<=:endTime group by c.category_id,o.id) as r group by r.category_id')
    	->bindValues($params)
    	->queryAll();
//     	$commandQuery = clone $result;
    	
//     	$result = $commandQuery->createCommand()->getRawSql(); 
    	return $result;
    }
    /**
     * 获取商品分类销售总额
     */
    public function getCategoryAmount(){
    	 
    }
    /**
     * 获取商品分类退款总数
     */
    public function getCategoryRefundCount(){
    	 
    }
    /**
     * 获取商品分类退款总额
     */
    public function getCategoryRefundAmount(){
    	 
    }  

    /**
     * 获取间距(chart横坐标的labels)
     * @param  $start
     * @param  $end
     * @return array
     */
    private function getRange($data,$type){
    	$start = $data['beginTime'];
    	$end = $data['endTime'];
    	$i = 0;
    	$range = array();
    	switch ($type){
    		case 2://按月
    			$end = date('Y-m', strtotime($end));
    			do {
    				$currentDate = date('Y-m', strtotime($start . ' + ' . $i . ' month'));
    				$range[] = $currentDate;
    				$i++;
    			} while ($currentDate < $end);
    			break;
    		case 3://按年
    			$end = date('Y', strtotime($end)); 
    			do {
    				$currentDate = date('Y', strtotime($start . ' + ' . $i . ' year'));
    				$range[] = $currentDate;
    				$i++;
    			} while ($currentDate < $end);
    			break;
    		default://按天
    			$end = date('Y-m-d', strtotime($end)); 
    			do {
    				$currentDate = date('Y-m-d', strtotime($start . ' + ' . $i . ' day'));
    				$range[] = $currentDate;
    				$i++;
    			} while ($currentDate < $end);
    			break;
    	}    	
    	
    	return $range;
    }
    /**
     * 获取搜索区间
     * @param  $type :1 日,2月,3年
     * @param  $data
     */
    private function getSearchRange($type,$data){
    	$result = array();
    	$time = strtotime($data);
    	switch ($type){
    		case 2:
    			$result['beginTime'] = mktime(0, 0, 0, date('m',$time), 1, date('Y',$time));
    			$result['endTime'] = mktime(23, 59, 59, date('m',$time), 31, date('Y',$time));
    			break;
    		case 3:
    			
    			$result['beginTime'] =  mktime(0, 0, 0, 1, 1,  date('Y',$time));
    			$result['endTime'] = mktime(23, 59, 59, 12, 31,  date('Y',$time));
    			break;
    		default:
    			$result['beginTime'] = mktime(0, 0, 0, date('m',$time), date('d',$time), date('Y',$time));
    			$result['endTime'] = mktime(23, 59, 59, date('m',$time), date('d',$time), date('Y',$time));
    			break;    			
    	}
    	return $result;
    }
    

}