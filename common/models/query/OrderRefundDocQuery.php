<?php

namespace common\models\query;
use common\models\OrderRefundDoc;
use Yii;

/**
 * This is the ActiveQuery class for [[\common\models\OrderRefundDoc]].
 *
 * @see \common\models\OrderRefundDoc
 */
class OrderRefundDocQuery extends \yii\db\ActiveQuery
{
    public function init(){
        $module = Yii::$app->id;
        if ($module=='backend'||$module=='seller') {
            $shopId = Yii::$app->session->get('shop_id');
            if (!empty($shopId)) {
                $this->alias(OrderRefundDoc::tableName());
                $this->andFilterWhere([OrderRefundDoc::tableName().'.shop_id' => $shopId]);
            } 
        }
        
     
         
        parent::init();
    }
    

    /**
     * @inheritdoc
     * @return \common\models\OrderRefundDoc[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\OrderRefundDoc|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
