<?php

namespace common\models;

use common\behaviors\CheckShopBehavior;
use common\behaviors\SoftDeleteBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%card}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $money
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 */
class Card extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%card}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['type', 'status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '卡券名称',
            'type' => '类型',
            'money' => '金额',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'status' => '状态',
        ];
    }
    
    public function behaviors()
    {
        $behaviors = [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
            [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'status' => 2
                ],
                'restoreAttributeValues' => [
                    'status' => 1
                ],
            ],
        ];
        return $behaviors;
    }
    
    public function getTypes(){    
        
        return [1=>'充值卡'];
    }
}
