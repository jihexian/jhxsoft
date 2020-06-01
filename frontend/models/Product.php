<?php

namespace frontend\models;


use frontend\models\query\ProductQuery;

/**
 * 
 * @author Administrator
 *
 */
class Product extends \common\models\Product
{
    public static function find(){
        return new ProductQuery(get_called_class());
    }
}
