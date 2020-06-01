<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%shipping_specify_region_item}}".
 *
 * @property integer $item_id
 * @property integer $shipping_id
 * @property string $start_num
 * @property string $start_price
 * @property string $add_num
 * @property string $add_price
 * @property integer $is_default
 * @property integer $delivery_type_id
 * @property string $regions
 * @property string $regions_str
 */
class ShippingSpecifyRegionItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shipping_specify_region_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shipping_id'], 'integer'],
            [['start_num', 'start_price', 'add_num', 'add_price'], 'number','skipOnEmpty' => false, 'skipOnError' => false],
        	[['start_num', 'start_price'], 'compare', 'compareValue' => 0, 'operator' => '>=','skipOnEmpty' => false, 'skipOnError' => false],
            [['is_default'], 'in', 'range' => [0,1]],
            [['delivery_type_id'], 'in', 'range' => [1,2,3,4]],        	
        	[['regions','regions_str'], 'requiredRegions','skipOnEmpty' => false, 'skipOnError' => false],        	
        	[['delivery_type_id'], 'default', 'value' => 1],
        	[['is_default'], 'default', 'value' => 0],
        ];
    }

    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => Yii::t('common', 'Item ID'),
            'shipping_id' => Yii::t('common', 'Shipping ID'),
            'start_num' => Yii::t('common', '收件'),
            'start_price' => Yii::t('common', '首费'),
            'add_num' => Yii::t('common', ' 续件'),
            'add_price' => Yii::t('common', '续费'),
            'is_default' => Yii::t('common', '是否默认'),
            'delivery_type_id' => Yii::t('common', '1,快递2，ems，3，顺丰4，平邮'),
        	'regions'=> Yii::t('common', '地区列表')
        ];
    }
    
    public function requiredRegions($attribute, $params)
    {
    	if ($this->is_default==0){
    		if (empty($this->regions)){
    			$this->addError($attribute,'地区必填');
    		}
    	}
    }
    
}
