<?php
/**
 * Created by PhpStorm.
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-05-30 17:35
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace common\logic;
use yii;
use common\models\Product;
use common\models\AttributeValue;
use yii\helpers\ArrayHelper;
use common\models\Attribute;
use common\models\Collection;
use common\models\ProductComment;
use common\models\ProductCommentSearch;
use common\models\Skus;
class ProductLogic{
 
      public function getDetail($productId){
     	
        $skus = $this->getSkus($productId);
		//其他业务逻辑
		//浏览量加一
     	$product = Product::find()->where(['product_id'=>$productId])->one();
		$product->updateCounters(['visit' => 1]);
     	//点评列表
         $query=ProductComment::find()->alias('p');
         $comment_count=$query->where(['p.status'=>1,'pid'=>0,'goods_id'=>$productId])->count();
         //好评数
         $good_comment_count=$query->where(['and',['p.status'=>1,'pid'=>0,'goods_id'=>$productId],['in','total_stars',[4,5]]])->count();
         
          $ff= empty($comment_count)?1:$good_comment_count/$comment_count;
          $comment=$query->where(['p.status'=>1,'pid'=>0,'goods_id'=>$productId])->orderBy('created_at desc')->limit(3)->all();

      /*    foreach ($comment as $key=>$vo){

         	 $imagestr = substr($vo['image'], 0);
         	 $image = json_decode($imagestr,true);
            
             $comment[$key]['image']=$image;
         } */
		 $arr =['count'=>$comment_count,'comment'=>$comment,'haopinglv'=>$ff]; 
         return array_merge($arr, $skus);
     } 

     
     
  
     public function getSkus($productId){
     	$product = Product::find()->where(['product_id'=>$productId])->one();
//      	$product->imagesAddPrefix();
     	$skus = Skus::find()->where(['product_id'=>$productId])->all();
     	//$skus = Util::ImagesAddPrefix($skus);
     	//$skus = Util::ImagesAddPrefix($skus,'thumbImg');
     	$skus = ArrayHelper::toArray($skus);
     	$attributeArray = array();//临时对象
     	$attributeValueArray = array();//临时对象
     	$attributeValuesIds = array();////临时对象
     	foreach ($skus as $key=>$sku){
     		//unset($sku['sku_values']);//js部分需要去掉sku_values json值，否则解析不了
     		$skuId = $sku['sku_id'];
     		$skuValuesId = explode('_', $skuId);//每个sku的value值数组
     		unset($skuValuesId[0]);//去掉prodcut_id
     		foreach ($skuValuesId as $k => $valuesId){
     			if (in_array($valuesId,$attributeValuesIds)){
     				continue;
     			}else{
     			    //查询规格对应的值
     				$v = AttributeValue::find()->where(['value_id'=>$valuesId])->one();
     				array_push($attributeValuesIds, $valuesId);
     				if (!isset($attributeValueArray[$k])){
     					$attributeValueArray[$k] = array();
     				}
     				//$attributeValueArray数组
     				array_push($attributeValueArray[$k], $v);		
     				if (!isset($attributeArray[$k])){
     					$attributeArray[$k] = Attribute::find()->where(['attribute_id'=>$v->attribute_id])->one();
     				   
     				 
     				}
     			}
     		}
     	}
     	$attributes = array_values($attributeArray);//该商品的规格
     	$attributeValues = array();//该商品的规格值
     	foreach ($attributeValueArray as $key=>$values){
     		$attributeValues = ArrayHelper::merge($attributeValues, $values);
     	}
     	$attributes = ArrayHelper::toArray($attributes);
     	$attributeValues = ArrayHelper::toArray($attributeValues);
     	foreach ($attributes as &$attr){
     		$attr['child'] = array();
     		foreach ($attributeValues as $value){
     			$value['is_select'] = 0;
     			$value['stock'] = 0;
     			if ($attr['attribute_id']==$value['attribute_id']){
     			   if($attr['usage_mode']==3&&strtotime($value['value_str'])>strtotime(date('Y-m-d'))+24*3600){
     			        $value['active']=1;
     			    }elseif($attr['usage_mode']!=3){
     			        $value['active']=1;
     			    }else{
     			        $value['active']=0;
     			    }
     			    array_push($attr['child'], $value);
     			}
     		}
     	}
     	return ['product'=>$product,'skus'=>$skus,'attributes'=>$attributes];
     }

}
