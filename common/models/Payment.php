<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%payment}}".
 *
 * @property string $id
 * @property string $name
 * @property string $logo
 * @property integer $type
 * @property string $class_name
 * @property string $desc
 * @property integer $status
 * @property integer $sortnum
 * @property string $config
 * @property integer $client_type
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class_name'], 'required'],
            [['desc', 'config'], 'string'],
            [['sortnum'], 'integer'],
            [['name', 'class_name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 255],
            [['type', 'status', 'client_type'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', '支付名称'),
            'logo' => Yii::t('backend', 'logo'),
            'type' => Yii::t('backend', '1:线上、2:线下'),
            'class_name' => Yii::t('backend', '支付类名称'),
            'desc' => Yii::t('backend', '描述'),
            'status' => Yii::t('backend', '安装状态 1启用 0禁用'),
            'sortnum' => Yii::t('backend', '排序'),
            'config' => Yii::t('backend', '配置参数,json数据对象'),
            'client_type' => Yii::t('backend', '1:PC端 2:移动端 3:通用'),
        ];
    }
}
