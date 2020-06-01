<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%store_region}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $region_id
 */
class StoreRegion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_region}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'region_id'], 'required'],
            ['region_id','unique','message'=>'地区数据已经被其它仓库占用'],
            [['store_id', 'region_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'store_id' => Yii::t('common', 'Store ID'),
            'region_id' => Yii::t('common', 'Region ID'),
        ];
    }
}
