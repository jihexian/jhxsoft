<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年12月18日 下午5:51:54
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\models;

use Yii;

/**
 * This is the ActiveQuery class for [[\common\models\Order]].
 *
 * @see \common\models\Order
 */
class OrderQuery extends \yii\db\ActiveQuery
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
    
    /*public function active()
     {
     return $this->andWhere('[[status]]=1');
     }*/
    
    
    /**
     * @inheritdoc
     * @return \common\models\Order[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }
    
    /**
     * @inheritdoc
     * @return \common\models\Order|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    
    /**
     * 被删除的
     * @return $this
     */
    public function onlyTrashed()
    {
        return $this->andWhere(['not', ['is_del' => 1]]);
    }
    /**
     * 未被删除的
     * @return $this
     */
    public function notTrashed()
    {
        return $this->andWhere(['is_del' => 0]);
    }
    /**
     * 退款中
     * @return \common\models\OrderQuery
     */
    public function refuseing(){
        return $this->andWhere(['status' => 10]);
    }
    
    /**
     * 发货单
     * @return \common\models\OrderQuery
     */
    public function shipping(){
        return $this->andWhere(['and',['in','status',[2,3,4,11]],['in','delivery_status',[0,4]]]);
    }
    
} 
