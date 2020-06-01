<?php
/**

 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace common\logic;
use common\components\Controller;
use yii;
use common\models\Skus;
use common\models\Product;
use common\models\StoreRegion;
use common\models\StoreStock;
/**
 * 先调用hasSku判断商品是否下架，如果是活动商品，再调用checkPromOpen判断商品是否还在活动期间，
 * @author Administrator
 *
 */
class SkusLogic{
	private $sku;
    
	public function __construct($sku_id){
		$this->sku = Skus::findOne(['sku_id'=>$sku_id]);		
	}
	/**
	 * 检查是否有库存
	 * @param integer $stock_num 
	 * @param number $region_id
	 * @return boolean
	 */
	public function checkStock($stock_num,$region_id=0){//商品库存不足
		if (!empty($this->sku->prom)){
		    return $stock_num>$this->sku->prom->goods_num-$this->sku->prom->buy_num-$this->sku->prom->order_num? true:false;
		}elseif($region_id!=0){
		 //判断配送区域是否仓库库存
		    $relationship= $this->multiple_store($region_id, $this->sku->sku_id);
    		 if($relationship){
    		     return $stock_num>$relationship->stock? true:false;
    		 }else{  //没有分仓库库存
    		     return $stock_num>$this->sku->stock? true:false;
    		 }
		}else{
		  return $stock_num>$this->sku->stock? true:false;
		}
	}
	public function checkPrice($price){//商品价格跟加入购物车时比较是否有变动 true有变动
		if (!empty($this->sku->prom)&&$this->sku->prom->proming_status==1){
			return $price==$this->sku->prom->price? false:true;
		}else{
		    if (Yii::$app->user->identity->type==3&&$this->sku->plus_price>0) {
		        return $price==$this->sku->plus_price? false:true;
		    }
			return $price==$this->sku->sale_price? false:true;
		}
	}

	public function getPrice(){
        if (!empty($this->sku->prom)){
            return $this->sku->prom->price;
        }else{
            return $this->sku->sale_price;
        }
    }
	public function checkPromOpen($prom_type,$prom_id){//商品活动是否还在进行
		if ($prom_id==0||$prom_type==0){//不验证活动
			$this->sku->prom = null;
			return true;
		}else{
			if (!empty($this->sku->prom)&&$this->sku->prom->id==$prom_id){
				if ($this->sku->prom->proming_status==1){
					return true;
				}else{
					$this->sku->prom = null;
					return false;
				}
			}else{
				return false;
			}
		}				
	}
	
	public function hasSku(){//商品规格是否已下架
		if (empty($this->sku)){
			return false;
		}else{
			$productId = $this->sku->product_id;
			$product = Product::find()->where(['product_id'=>$productId,Product::tableName().'.status'=>1])->one();
			if(!empty($product)){
			     $shop = $product->shop;
    			if ($shop['status']!=1){
    				return false;
    			}else{
    				return true;
    			}
			}else{
			    return false;
			}
		}
	}
	
	public function getSku(){
		return $this->sku;
	}
	public function validateSku($data){
	    if(empty($this->sku)){
	        return ['status'=>0,'msg'=>'商品规格已失效！'];
	    }
		isset($data['prom_type'])? $promType = $data['prom_type']:$promType =0;
		isset($data['prom_id'])? $promId = $data['prom_id']:$promId =0;
        isset($data['price'])? $price = $data['price']:$price =0;
        isset($data['stock_num'])? $stock_num = $data['stock_num']:$stock_num = 0;
        isset($data['region_id'])? $region_id = $data['region_id']:$region_id =0;
        if(isset($data['active'])&&$data['active']==0){
            return ['status'=>-1,'msg'=>'商品规格'.$this->sku->sku_values.'已下架！'];
        }
		if (!$this->hasSku()){
		    return ['status'=>-1,'msg'=>'商品'.$this->sku->product->name.'已下架'];
		}
		if (!$this->checkPromOpen($promType,$promId)){		    
			return ['status'=>-2,'msg'=>'商品'.$this->sku->product->name.'活动已结束！'];
		}

		if ($stock_num&&$this->checkStock($stock_num,$region_id)){
		    return ['status'=>-3,'msg'=>'商品'.$this->sku->product->name.'库存不足'];
		}

		if ($price&&$this->checkPrice($price)){
		    return ['status'=>-4,'msg'=>'商品'.$this->sku->product->name.'价格变动'];
		}
		return ['status'=>1,'msg'=>'验证成功'];
	}

	private function multiple_store($region_id,$sku_id){
	    $store=StoreRegion::findOne(['region_id'=>$region_id]);
	    if(!$store){
	        return false;
	    }
	    $relationship= StoreStock::find()->where(['sku_id'=>$sku_id,'store_id'=>$store->store_id])->one();
	    if($relationship){
	        return $relationship;
	    }else{  //没有分仓库库存
	        return false;
	    }
	}
	
}