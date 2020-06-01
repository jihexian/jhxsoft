<?php
/**
 * 
 * Author vamper 944969253@qq.com
 * Time:2019年2月18日 上午11:57:03
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */

namespace common\models\query;


use Yii;
use yii\db\ActiveQuery;
use common\models\ShopWithdraw;

class ShopWithdrawQuery extends ActiveQuery
{
    public function init(){
        $module = Yii::$app->id;
        if ($module=='backend'||$module=='seller') {
            $shopId = Yii::$app->session->get('shop_id');
            if (!empty($shopId)) {
                $this->andFilterWhere(['shop_id' => $shopId]);
            }
        }
        
        parent::init();
    }
    
    public static function find(){
        return new ShopWithdraw(get_called_class());
    }

}