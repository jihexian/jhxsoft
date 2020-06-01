<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%shipping_free}}".
 *
 * @property integer $free_id
 * @property integer $shipping_id
 * @property integer $free_type
 * @property string $free_amount
 * @property string $free_count
 * @property integer $delivery_type_id
 * @property string $regions
 * @property string $regions_str
 */
class ShippingFree extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shipping_free}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shipping_id', 'delivery_type_id'], 'integer'],
            [['free_amount', 'free_count'], 'number'],
        	[['free_amount', 'free_count'], 'compare', 'compareValue' => 0, 'operator' => '>='],
            [['free_type'], 'in', 'range' => [1,2,3]],
        	[['regions','regions_str'], 'required','message'=>'包邮地区不能为空'],
        	[['free_amount', 'free_count'], 'requiredCondition','skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'free_id' => Yii::t('common', 'ID'),
            'free_type' => Yii::t('common', '免邮条件类型：1，件数 2，金额，3，件数+金额'),
            'free_amount' => Yii::t('common', '免邮金额'),
            'free_count' => Yii::t('common', '免邮件数'),
            'delivery_type_id' => Yii::t('common', ''),
        ];
    }
    
    public function requiredCondition($attribute, $params)
    {
    	if ($this->free_type==1){
    		if (empty($this->free_count)){
    			$this->addError($attribute,'包邮条件必填');
    		}    		
    	}elseif ($this->free_type==2){
    		if (empty($this->free_amount)){
    			$this->addError($attribute,'包邮条件必填');
    		}
    	}else{
    		if (empty($this->free_amount)&&empty($this->free_count)){
    			$this->addError($attribute,'包邮条件必填');
    		}
    	}
    }
}
