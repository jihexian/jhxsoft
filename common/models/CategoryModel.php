<?php

namespace common\models;

use Yii;
use common\behaviors\CheckShopBehavior;

/**
 * This is the model class for table "{{%category_model}}".
 *
 * @property integer $model_id
 * @property integer $category_id
 * @property string $model_name
 * @property integer $status
 * @property integer $shop_id
 */
class CategoryModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_model}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status','model_id'], 'integer'],
        	[['model_name'], 'required','message'=>'模型名称必填'],
        	[['model_name'], 'unique','message'=>'商品模型名称重复'],
            [['model_name'], 'string', 'max' => 50],
        	[['status'], 'default', 'value' => 1],
        	['status', 'in', 'range' =>[1,0]],
        ];
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
    public function attributeLabels()
    {
        return [
            'model_id' => Yii::t('common', 'ID'),
            'model_name' => Yii::t('common', '模型名称'),
            'status' => Yii::t('common', '0禁止1正常'),
        ];
    }
 	
  
    
	public static function getKeyValuePairs($shopId=null)
    {
    	if ($shopId!=null){
    		$sql = 'SELECT model_id, model_name FROM ' . self::tableName() .'where shop_id='.$shopId. ' ORDER BY model_id ASC ';
    	}else{
    		$sql = 'SELECT model_id, model_name FROM ' . self::tableName() . ' ORDER BY model_id ASC ';
    	}
        return Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_KEY_PAIR);
    }
    
    public function getCategoryModelAttr()
    {
    	/**
    	 * 第一个参数为要关联的字表模型类名称，
    	 *第二个参数指定 通过子表的 id 去关联主表的 id 字段
    	 */
    	return $this->hasMany(CategoryModelAttr::className(), ['model_id' => 'model_id']);
    }
    public function getCategoryModelAttrValue(){
    	return $this->hasMany(CategoryModelAttrValue::className(), ['model_attribute_id' => 'model_attr_id'])->via('categoryModelAttr');;
    }
    
    public function beforeDelete(){
    	if ($this->is_system){
    		$this->addErrors(['model_id'=>'系统默认模型不可删除！']);
    		return false;
    	}
    	$count = ProductModelAttr::find()->where(array('model_id'=>$this->model_id))->count();
    	if ($count>0){
    		$this->addErrors(['model_id'=>'该模型下有关联商品，不可删除！']);
    		return false;
    	}else{
    		return parent::beforeDelete();
    	} 
    	
    	
    }
	
}
