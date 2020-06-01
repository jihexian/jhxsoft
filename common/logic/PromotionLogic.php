<?php
/**
 * Created by PhpStorm.
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-05-30 17:35
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace common\logic;
use common\components\Controller;
use yii;
use common\models\Product;
use common\models\Skus;
use common\modules\promotion\models\FlashSale;
class PromotionLogic{
	private $promotion = array('qianggou');
  	//检查库存
	public function checkCanBuy($promId,$promType){
		
	}
	//检查是否开启了具体的活动模块
	public function checkOpen($prom_type){
		$flag = 0;
		switch ($prom_type){
			case 1://抢购
				if(in_array('qianggou', $this->promotion)){
					$flag = 1;
				}
				break;
			case 2:
				break;
			case 3:
				break;
			case 4:
				break;
			default:
				break;
		}
		return $flag;
	}

	//检查是否有正在参加营销活动
	public function checkProm($productId){
		$product = Product::findOne($productId);
		if (isset($product->proms)&&count($product->proms)>0){
			return true;
		}else{
			return false;
		}
	}
	
	public function checkSkusProming($skuId,$prom_type){
	    switch ($prom_type){
	        case 1://抢购
	            $flashSale = FlashSale::find()->where(['sku_id'=>$skuId])->andWhere(['in','status',[0,1]])->one();
	            if (!empty($flashSale)&&($flashSale->proming_status!=2&&$flashSale->proming_status!=-1)) {
	                return ['status'=>1,'msg'=>'正在进行'.$flashSale['title'].'抢购活动！'];
	            }
	            break;
	        case 2:
	            break;
	        case 3:
	            break;
	        case 4:
	            break;
	        default:
	            break;
	    }
	    return ['status'=>0,'msg'=>'无正在进行的活动！'];
	}
	
	
}
