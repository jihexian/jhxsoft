<?php
/**
 * 
 * Author vamper 944969253@qq.com
 * Time:2019年2月18日 上午11:57:03
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */

namespace common\models\query;


use common\models\Product;
use common\models\Shop;
use Yii;
use yii\db\ActiveQuery;

class ProductQuery extends ActiveQuery
{
    public function init(){
        $this->alias(Product::tableName());
        $module = Yii::$app->id;
        if ($module=='backend'||$module=='seller') {
            $shopId = Yii::$app->session->get('shop_id');
            if (!empty($shopId)) {
                $this->andFilterWhere(['shop_id' => $shopId]);
            }
        }
        parent::init();
    }

    /**
     * 被删除的
     * @return $this
     */
    public function onlyTrashed()
    {
        return $this->andWhere(['is_del'=>1]);
    }
    /**
     * 未被删除的
     * @return $this
     */
    public function notTrashed()
    {
        return $this->andWhere(['is_del'=>0]);
    }
}