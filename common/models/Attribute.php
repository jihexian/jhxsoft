<?php

namespace common\models;

use Yii;
use common\behaviors\CheckShopBehavior;

/**
 * This is the model class for table "{{%attribute}}".
 *
 * @property integer $attribute_id
 * @property integer $type_id
 * @property string $attribute_name
 * @property integer $sort
 * @property integer $usage_mode
 * @property integer $is_system
 * @property integer $shop_id
 */
class Attribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attribute}}';
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
            [['type_id', 'sort', 'usage_mode','is_system'], 'integer'],
            [['attribute_name'], 'string', 'max' => 45],
        	[['attribute_name'],'required','message'=>'规格名称必填'],
        //	[['attribute_name'], 'unique', 'message' =>'规格名称已存在'],
        	['type_id', 'exist', 'targetClass' => ProductType::className(), 'targetAttribute' => 'type_id'],
        	['usage_mode', 'default', 'value' =>1], 
        	['usage_mode', 'in', 'range' =>[1,2,3]],
        	['is_system', 'default', 'value' =>0],
        	['is_system', 'in', 'range' =>[0,1]],
        ];
        
    }
    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attribute_id' => Yii::t('common', 'Attribute ID'),
            'type_id' => Yii::t('common', '关联的product_type'),
            'attribute_name' => Yii::t('common', '规格名称'),
            'sort' => Yii::t('common', '排序'),
            'usage_mode' => Yii::t('common', '类型：1文字，2图片'),
        ];
    }
    
    public function getAttributeValue()
    {
    	/**
    	 * 第一个参数为要关联的字表模型类名称，
    	 *第二个参数指定 通过子表的 id 去关联主表的 id 字段
    	 */
    	return $this->hasMany(AttributeValue::className(), ['attribute_id' => 'attribute_id']);
    }
}
