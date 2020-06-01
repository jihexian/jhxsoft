<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%category_model_attr_value}}".
 *
 * @property integer $model_attr_value_id
 * @property integer $model_attribute_id
 * @property string $value_str
 * @property integer $sort
 * @property integer $status
 * @property string $img_url
 */
class CategoryModelAttrValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_model_attr_value}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_attribute_id'], 'integer'],
            [['value_str', 'img_url'], 'string', 'max' => 255],        	
        	['value_str', //只有 name 能接收错误提示，数组['name','shop_id']的场合，都接收错误提示
        		'unique', 'targetAttribute'=>['value_str','model_attribute_id'] ,
        		'comboNotUnique' => '属性值重复！' //错误信息
        	],
        	[['value_str'],'required','message'=>'属性值必填'],
            [['sort', 'status'], 'string', 'max' => 3],
        	[['sort'], 'default', 'value' => 0],
        	[['status'], 'default', 'value' => 1],
        	['status', 'in', 'range' =>[1,0]],
        	['model_attribute_id', 'exist', 'targetClass' => CategoryModelAttr::className(), 'targetAttribute' => 'model_attribute_id'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'model_attr_value_id' => Yii::t('common', 'ID'),
            'model_attribute_id' => Yii::t('common', '属性名'),
            'value_str' => Yii::t('common', '属性值'),
            'sort' => Yii::t('common', '排序'),
            'status' => Yii::t('common', ''),
            'img_url' => Yii::t('common', '图片地址'),
        ];
    }
    public function beforeDelete(){
    	$count = ProductModelAttr::find()->where(array('model_attr_value_id'=>$this->model_attr_value_id))->count();
    	if ($count>0){
    		$this->addErrors(['model_id'=>'属性值('.$this->value_str.')下有关联商品，不可删除！']);
    		return false;
    	}else{
    		return parent::beforeDelete();
    	}   
    	
    }
}
