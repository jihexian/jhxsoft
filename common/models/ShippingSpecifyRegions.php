<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%shipping_specify_regions}}".
 *
 * @property integer $shipping_id
 * @property integer $item_id
 * @property integer $region_id
 */
class ShippingSpecifyRegions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shipping_specify_regions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shipping_id', 'item_id', 'region_id'], 'integer'],
            [['item_id', 'region_id'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
    	return [
            'shipping_id' => Yii::t('common', 'Shipping ID'),
            'item_id' => Yii::t('common', 'Item ID'),
            'region_id' => Yii::t('common', 'Region ID'),
        ];
    }
}
