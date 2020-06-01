<?php

namespace common\models;
use Yii;

/**
 * This is the model class for table "{{%sku_item}}".
 *
 * @property string $sku_id
 * @property integer $attribute_id
 * @property integer $value_id
 */
class SkuItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sku_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sku_id'], 'required'],
            [['attribute_id', 'value_id'], 'integer'],
            [['sku_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sku_id' => Yii::t('common', 'Sku ID'),
            'attribute_id' => Yii::t('common', 'Attribute ID'),
            'value_id' => Yii::t('common', 'Value ID'),
        ];
    }
    
    public function getAttri(){
        return $this->hasOne(Attribute::className(),['attribute_id'=>'attribute_id']);
    }
    
    public function getAttributeValue(){
        return $this->hasOne(AttributeValue::className(),['value_id'=>'value_id']);
    }
    
}
