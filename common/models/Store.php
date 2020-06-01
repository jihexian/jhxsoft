<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%store}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $tel
 * @property string $addr
 * @property integer $sort
 * @property integer $created_at
 * @property integer $updated_at
 */
class Store extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','regions'], 'required'],
            ['name','unique'],
            [['sort','status'], 'integer'],
            [['name', 'code'], 'string', 'max' => 45],
            [['tel'], 'string', 'max' => 15],
            [['addr'], 'string', 'max' => 245],
            [['regions'],'string']
        ];
    }
  
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
            
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', '仓库名称'),
            'code' => Yii::t('common', '仓库编号'),
            'tel' => Yii::t('common', '电话'),
            'addr' => Yii::t('common', '地址'),
            'sort' => Yii::t('common', '排序'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'status'=>Yii::t('common', '状态'),
            'regions'=>'配送地区范围'
        ];
    }

    /**
     * @inheritdoc
     * @return StoreQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StoreQuery(get_called_class());
    }
}
