<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%shipping_free_regions}}".
 *
 * @property integer $shipping_id
 * @property integer $free_id
 * @property integer $region_id
 */
class ShippingFreeRegions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shipping_free_regions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shipping_id', 'free_id', 'region_id'], 'required'],
            [['shipping_id', 'free_id', 'region_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shipping_id' => Yii::t('common', 'Shipping ID'),
            'free_id' => Yii::t('common', 'Free ID'),
            'region_id' => Yii::t('common', 'Region ID'),
        ];
    }
}
