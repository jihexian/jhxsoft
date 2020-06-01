<?php

namespace common\models;

use Yii;
use common\behaviors\CheckShopBehavior;

/**
 * This is the model class for table "{{%attribute_value}}".
 *
 * @property integer $value_id
 * @property integer $attribute_id
 * @property integer $sort
 * @property string $value_str
 * @property string $image_url
 * @property integer $is_system
 * @property integer $shop_id

 */
class AttributeValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attribute_value}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
    	return [
    			CheckShopBehavior::className(),
    	];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id', 'sort','is_system'], 'integer'],
            [['value_str'], 'string', 'max' => 255],
        	[['value_str'],'required','message'=>'规格值名称必填'],
        	'value_str'=>[['value_str'], 'unique', 'message' =>'规格值名称已存在'],
            [['image_url'], 'string', 'max' => 255],
        	['is_system', 'default', 'value' =>0],
        	['is_system', 'in', 'range' =>[0,1]],
        	['attribute_id', 'exist', 'targetClass' => Attribute::className(), 'targetAttribute' => 'attribute_id'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'value_id' => Yii::t('common', 'Value ID'),
            'attribute_id' => Yii::t('common', 'Attribute ID'),
            'sort' => Yii::t('common', '排序'),
            'value_str' => Yii::t('common', '规格值(文字/图片)'),
            'image_url' => Yii::t('common', '对应的商品展示图片路径'),
        ];
    }
    

    
}
