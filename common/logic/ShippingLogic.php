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
use common\models\Shipping;
use common\models\Skus;
use yii;
use common\models\Address;
use common\models\Product;
use common\models\Region;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
class ShippingLogic{
    public $obj;
    public function __construct()
    {
        $this->obj=new Shipping();
    }



    /**
     * 获取邮费
     * @param $uid
     * @param $addressId
     * @param$carts  cart数组 单个店铺的所有cart
     * @return array 返回邮费
     */
    public function getPrice($uid,$region_id,$carts){

        $postTotal = 0;
        $data=array();
        $productCartsNum = ArrayHelper::map($carts, 'product_id', 'num');
        $productCartsAmount =ArrayHelper::map($carts, 'product_id', 'sale_price_real');
        $productCartsSkus = ArrayHelper::map($carts,'product_id','skus');
        $products = array();
        foreach ($carts as $cart){
            $product = Product::find()->where(['product_id'=>$cart['product_id']])->one();
            $flag = $this->checkFree($product, $region_id,$cart);
            if ($flag){
                continue;
            }
            array_push($products, $product);        
        }
       //按邮费模板拆分数组
        foreach ($products as $vo){
            $data[$vo->shipping_id][]=$vo;
        }
        
        foreach($data as $key =>  $pp){
            $all_total=0;
            $all_weight=0;
            foreach ($pp as $item){
                $all_total += $productCartsNum[$item->product_id];
                $all_weight += $productCartsSkus[$item->product_id]['weight'];
            } 
            $postTotal+=$this->getMergeProductsPrice($key, $region_id,$all_total,$all_weight);
        }
   
   
        return $postTotal;
    }
    
    public function getSinglePrice($uid,$sku_id,$num,$region_id){

    	$sku = Skus::find()->where(array('sku_id'=>$sku_id))->one();
    	if (empty($sku)){
    		throw new Exception('sku不存在');
    	}
    	$postTotal = 0;
    	$products = array();
    	$product = Product::find()->where(['product_id'=>$sku['product_id']])->one();
    	$cart = array();
    	$cart['num'] = $num;
    	$cart['sale_price_real'] = $sku['sale_price'];
    	//判断是否免邮
    	$flag = $this->checkFree($product, $region_id,$cart);
    	if (!$flag){
    		array_push($products, $product);
    	}
    	foreach ($products as $item){
    		$sku_weight = $sku['weight'];
    		$postTotal += $this->getProductPrice($item, $region_id, $num, $sku_weight);
    	}
    	return $postTotal;
    }
    

    

    /**
     * 获取可合并邮费商品的总邮费
     * @return number
     */
    private function getMergeProductsPrice($product,$region_id,$all_total,$all_weight){
       
        $shipping = Shipping::findOne($product);
        $items = $shipping->items; //ShippingSpecifyRegionItem的数据
        $postTotal = 0;
        $item = $this->getItem($items, $region_id);
        $type = $shipping->type;
        $start_num = $item->start_num;
        $start_price = $item->start_price;
        $add_num = $item->add_num;
        $add_price = $item->add_price;
        if ($type==0){//按重量
            $postTotal = $this->getProductPostPrice($all_weight, $start_num, $start_price, $add_num, $add_price);
        }elseif($type==1){//按件
            $postTotal = $this->getProductPostPrice($all_total, $start_num, $start_price, $add_num, $add_price);
        }else{
            throw new Exception('运费模板计费方式不正确！');
        }
        return $postTotal;
        
    }
   	/**
   	 * 获取不可合并的邮费商品的单个商品邮费
   	 * @param  $product
   	 * @param  $region_id
   	 * @param  $productCartsNum
   	 * @param  $productCartsSkus
   	 * @throws Exception
   	 * @return $postTotal 邮费
   	 */
    private function getProductPrice($product,$region_id,$num,$sku_weight){
    	$shipping = Shipping::findOne($product->shipping_id);
    	$items = $shipping->items; //ShippingSpecifyRegionItem的数据
    	$postTotal = 0;   	
    	$item = $this->getItem($items, $region_id);    	
    	$type = $shipping->type;
    	$start_num = $item->start_num;
    	$start_price = $item->start_price;
    	$add_num = $item->add_num;
    	$add_price = $item->add_price;
    	if ($type==0){//按重量
    	    $postTotalWeight = $this->getProductWeight($product, $sku_weight, $num);
    		$postTotal = $this->getProductPostPrice($postTotalWeight, $start_num, $start_price, $add_num, $add_price);
    	}elseif($type==1){//按件
    		$postTotalNum = $num;
    		$postTotal = $this->getProductPostPrice($postTotalNum, $start_num, $start_price, $add_num, $add_price);
    	}else{
    		throw new Exception('运费模板计费方式不正确！');
    	}    	
    	return $postTotal;
    }
    //查看是否包邮
    private function checkFree($product,$region_id,$cart){
    	$flag = false;
    	if($product->is_free){//商品级别包邮
    		$flag = true;
    		return $flag;
    	}else{
    		$shipping = Shipping::findOne($product->shipping_id);
    		if (empty($shipping)){
    			throw new Exception("该商品未设置运费模板，请联系商家添加运费模板！");
    		}
    		$shippingFrees = $shipping->frees;   	
    		if (!empty($shippingFrees)){//模板级别包邮		
    			foreach ($shippingFrees as $free){
    			    $flag = $this->checkShippingFree($free, $cart['num'],  $cart['num']*$cart['sale_price_real'], $region_id);	
					if($flag)
						break;									
    			}    			
    		}else{
    		    $flag= false;
    		}
    		return $flag;
    	}  	
    }
    
    /**
     * 查看一个包邮条件是否满足
     * @param  $free
     * @param  $num
     * @param  $amount
     * @param  $region_id
     * @return boolean
     */
    private function checkShippingFree($free,$num,$amount,$region_id){
    	$flag = true;
    	//包邮基本条件
    	if ($free->free_type==1){ //按件数包邮
    		if(!($num>=$free->free_count)){
    			$flag =  false;
    			return $flag;
    		}    	
    	}elseif ($free->free_type==2){ //按金额包邮
    		if (!($amount>=$free->free_amount)){
    			$flag =  false;
    			return $flag;
    		}
    	}else{    //按件数+金额包邮
    		if (!($num>=$free->free_count&&$amount>=$free->free_amount)){
    			$flag =  false;
    			return $flag;
    		}
    	}
    	//包邮地区条件
    	$regions = $free->regions;
    	$regionsIds = explode(',', substr($regions, 0,strlen($regions)-1));
    	$regionsIds = array_values($regionsIds);
    
    	  if (in_array($region_id, $regionsIds)){
    			return true;
    		}

    	return false;  	
    }
	/**
	 * 获取选中商品的单品总重
	 * @param $product
	 * @param该商品选中的sku $sku_weigth 
	 * @param总量 $num
	 */
    private function getProductWeight($product,$sku_weigth,$num){
    	$totalWeight = 0;
    	return $sku_weigth*$num;    	
    }
    /**
     * 获取选中单品的邮费
     * @param $total商品总量 
     * @param  $start_num模板首费 
     * @param  $start_price首价
     * @param  $add_num续件 
     * @param  $add_price续费 
     */
    private function getProductPostPrice($total,$start_num,$start_price,$add_num,$add_price){
    	$postTotal = 0;
    	if ($total>$start_num&&$add_num>0){
    		$postTotal = $start_price + intval(intval($total-$start_num)/intval($add_num))*$add_price;
    	}else{
    		$postTotal = $start_price;
    	}
    	return $postTotal;
    }
    /**
     * 查找对应的邮费地区模板
     * 
     * @param  $items
     * @param  $region_id
     * @return  $returnItem
     */
    private function getItem($items,$region_id){
    	$defaultRegionItem;
    	$returnItem;
    	foreach ($items as $item){//查看地区是否在特殊地区邮费列表
    		if ($item->is_default==1){//排除默认邮费
    			$defaultRegionItem = $item;
    			continue;
    		}
    		//特殊
    		$regions = $item->regions;
    		$regionsIds = explode(',', substr($regions, 0,strlen($regions)-1));
    		if (in_array($region_id, $regionsIds)){
    		    $returnItem = $item;
    			}
    	}
    	if(!isset($returnItem)){//如果没有特殊地区
    		$returnItem = $defaultRegionItem;
    	}
    	return $returnItem;
    }

}