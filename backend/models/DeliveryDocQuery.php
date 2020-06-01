<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[\common\models\DeliveryDoc]].
 *
 * @see \common\models\DeliveryDoc
 */
class DeliveryDocQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\DeliveryDoc[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\DeliveryDoc|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
