<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%shipping}}".
 *
 * @property string $shipping_id
 * @property string $name
 * @property string $desc
 * @property integer $type
 * @property integer $shop_id
 * @property integer $sort
 * @property integer $status
 * @property integer $free_condition
 * @property integer $is_free
 * 
 */
class Shipping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shipping}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'sort'], 'integer'],
            [['name'], 'required'],        		
            [['desc'], 'string', 'max' => 100],
            [['type', 'status', ], 'string', 'max' => 1],
        	[['is_free','free_condition'],'in','range'=>[1,0]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shipping_id' => 'ID',
            'name' => '模板名称',
            'desc' => '模板描述',
            'type' => '计费方式',
            'sort' => '排序',
            'status' => '该配送方式是否被禁用，1，可用；0，禁用',
            'free_condition' => '是否指定包邮（选填）',
        ];
    }
    
    public function getItems(){
    	return $this->hasMany(ShippingSpecifyRegionItem::className(), ['shipping_id' => 'shipping_id']);
    }
    public function getFrees(){
    	return $this->hasMany(ShippingFree::className(), ['shipping_id' => 'shipping_id']);
    }
    
    
    public static function getShippingPrice($regionId){
    	$cartData = Cart::findCartByUser();
    	   	
    }
    public function beforeDelete(){
    	 
    	if ($this->is_system){
    		$this->addErrors(['shipping_id'=>'系统默认模板不可删除！']);
    		return false;
    	}
    	return parent::beforeDelete();
    }
    
    public static function getKeyValuePairs()
    {
    	$sql = 'SELECT shipping_id, name FROM ' . self::tableName() . ' ORDER BY shipping_id ASC ';
    	return Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_KEY_PAIR);
    }
}
