<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%shipping_company}}".
 *
 * @property string $id
 * @property string $company_name
 * @property integer $sort
 * @property string $code
 * @property integer $status
 * @property integer $create_at
 * @property integer $update_at
 */
class ShippingCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shipping_company}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort', 'status', 'create_at', 'update_at'], 'integer'],
            [['company_name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 45],
            [['company_name','code'],'required'],
            ['sort','default','value'=>99]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'company_name' => Yii::t('common', 'Company Name'),
            'sort' => Yii::t('common', 'Sort'),
            'code' => Yii::t('common', 'Code'),
            'status' => Yii::t('common', 'Status'),
            'create_at' => Yii::t('common', 'Create At'),
            'update_at' => Yii::t('common', 'Update At'),
        ];
    }
    
    public function attributeHints(){
        ArrayHelper::merge(parent::activeAttributes(), [
            'code'=>'编码参考https://baijiahao.baidu.com/s?id=1644991101794218331',
        ]); 
    }
   
}
