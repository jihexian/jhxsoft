<?php
/**
 * 
 * Author vamper 944969253@qq.com
 * Time:2019年2月18日 上午11:57:03
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */

namespace frontend\models\query;


use yii\db\ActiveQuery;
use frontend\models\Product;

class ProductQuery extends ActiveQuery
{
    public function init(){ 
        $this->alias(Product::tableName());
        $this->andFilterWhere([Product::tableName().'.status' => 1]);
        $this->andFilterCompare(Product::tableName().'.stock', '>0');
        parent::init();
    }

}