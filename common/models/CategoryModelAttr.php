<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%category_model_attr}}".
 *
 * @property integer $model_attr_id
 * @property string $attr_name
 * @property integer $type
 * @property integer $search
 * @property integer $model_id
 * @property integer $status
 * @property string $img_url
 * @property string $sort
 */
class CategoryModelAttr extends \yii\db\ActiveRecord
{
	CONST TYPE_RADIO = 1;
	CONST TYPE_CHECKBOX = 2;
	CONST TYPE_SELECTOR = 3;
	//CONST TYPE_INPUT = 4;
	CONST SEARCH_YES = 1;
	CONST SEARCH_NO = 0;
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_model_attr}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attr_name'], 'string', 'max' => 50],
        	[['attr_name'], 'required','message'=>'属性名称必填'],
        	[['img_url'], 'string', 'max' => 255],
        	[['model_id','status','sort'], 'integer'],   
        	['attr_name', //只有 name 能接收错误提示，数组['name','shop_id']的场合，都接收错误提示
        		'unique', 'targetAttribute'=>['attr_name','model_id'] ,
        		'comboNotUnique' => '属性名重复！' //错误信息
        	],
        	['model_id', 'exist', 'targetClass' => CategoryModel::className(), 'targetAttribute' => 'model_id'],
            [['status'], 'default', 'value' => 1],
        	['status', 'in', 'range' =>[1,0]],
        	[['sort'], 'default', 'value' => 0],
        	[['search'], 'default', 'value' => 0],
        	['search', 'in', 'range' =>[1,0]],
        	[['type'], 'default', 'value' => 1],
        	['type', 'in', 'range' =>[1,2,3]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'model_attr_id' => Yii::t('common', 'ID'),
            'attr_name' => Yii::t('common', '属性名称'),
            'type' => Yii::t('common', '控件的类型'),
            'search' => Yii::t('common', ''),
        	'model_id' => Yii::t('common', '模型'),
        ];
    }
    public static function getSearchStatusList()
    {
    	return [
    			self::SEARCH_YES => '支持',
    			self::SEARCH_NO => '不支持',
    	];
    }
    public static function getTypeList()
    {
    	return [
    			self::TYPE_CHECKBOX => '复选框',
    			self::TYPE_RADIO => '单选框',
    			self::TYPE_SELECTOR => '下拉框',
    			//self::TYPE_INPUT => '输入框',
    	];
    }
    public function getCategoryModelAttrValue()
    {
    	/**
    	 * 第一个参数为要关联的字表模型类名称，
    	 *第二个参数指定 通过子表的 id 去关联主表的 id 字段
    	 */
    	return $this->hasMany(CategoryModelAttrValue::className(), ['model_attribute_id' => 'model_attr_id']);
    }
    
    public function beforeDelete(){
    	
    	$count = ProductModelAttr::find()->where(array('model_attr_id'=>$this->model_attr_id))->count();
    	if ($count>0){
    		$this->addErrors(['model_id'=>'该模型属性下有关联商品，不可删除！']);
    		return false;
    	}else{    		
    		$flag = CategoryModelAttrValue::deleteAll(array('model_attribute_id'=>$this->model_attr_id));
    		if ($flag === false){
    			return false;
    		}
    	}
    	 
    	return parent::beforeDelete();
    }
}
