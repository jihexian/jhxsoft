<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%collection}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $member_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Collection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%collection}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'member_id','created_at','updated_at'], 'integer'],
        	['member_id', 'exist', 'targetClass' => Member::className(), 'targetAttribute' => 'id'],
        	['product_id', 'exist', 'targetClass' => Product::className(), 'targetAttribute' => 'product_id'],
        ];
    }

    public function behaviors()
    {
    	$behaviors = [
    			[
    				'class' => TimestampBehavior::className(),    					
    			],
    
    	];
    	return $behaviors;
    
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'member_id' => 'Member ID',
            'created_at' => 'Created At',
        ];
    }
    
    public function getProduct(){
    	return $this->hasOne(Product::className(),['product_id'=>'product_id']);
    }
}
