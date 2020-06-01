<?php
/**
 * Author wsyone wsyone@faxmail.com
 * Time:2020年1月13日上午9:40:23
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\models;
use common\models\Coupon;
use yii\db\ActiveQuery;

class CouponQuery extends ActiveQuery
{
    /**
     * @param null $db
     * @return array|Coupon[]
     */
    public function all($db = null)
    {
        return parent::all($db); // TODO: Change the autogenerated stub
    }
    
    /**
     * @param null $db
     * @return array|null|Coupon
     */
    public function one($db = null)
    {
        return parent::one($db); // TODO: Change the autogenerated stub
    }
    
    /**
     * 被删除的
     * @return $this
     */
    public function onlyTrashed()
    {
        return $this->andWhere(['not', ['deleted_at' => null]]);
    }
    /**
     * 未被删除的
     * @return $this
     */
    public function notTrashed()
    {
        return $this->andWhere(['deleted_at' => null]);
    }
    
    /**
     * 待审核的
     * @return $this
     */
    public function pending()
    {
        return $this->andWhere(['status' =>0]);
    }
    /**
     * 审核通过的
     */
    public function active()
    {
        return $this->andWhere(['status' =>1]);
    }
    /**
     * 未删除且审核通过的
     * @return $this
     */
    public function normal()
    {
        return $this->notTrashed()->active();
    }
    
    /**
     * @return $this
     */
    public function my()
    {
        return $this->andWhere(['user_id' => \Yii::$app->user->id]);
    }
    
}