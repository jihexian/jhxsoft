<?php
/**
 * 
 */

namespace backend\models;
use backend\models\query\ProductQuery;
class Product extends \common\models\Product
{
    public function afterFind(){
        //处理图片信息
        if (strlen($this->image)>0){
            $images =  $this->image;
            $this->image = json_decode($images,true);
        }
    }
    
    public static function find(){
        return new ProductQuery(get_called_class());
    }
}