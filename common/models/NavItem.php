<?php

namespace common\models;

use common\behaviors\PositionBehavior;
use Yii;

/**
 * This is the model class for table "{{%nav_item}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property integer $status
 */
class NavItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%nav_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'url'], 'required'],
            [['status', 'nav_id', 'order', 'target'], 'integer'],
            [['title', 'url'], 'string', 'max' => 128],
            ['icon','string'],	
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'nav_id' => Yii::t('common', 'NAV ID'),
            'title' => Yii::t('common', 'Title'),
            'url' => Yii::t('common', 'Url'),
            'target' => '是否新窗口打开',
            'status' => Yii::t('common', 'Status'),
            'order' => Yii::t('common', 'Order'),
            'icon' => Yii::t('common', 'Icon'),
        ];
    }

    public function attributeHints()
    {
        return [
            'url' => '格式: /site/index a=1&b=2',
            'icon'=>'图标尺寸为：80*80'
        ];
    }

    public function behaviors()
    {
        return [
            'positionBehavior' => [
                'class' => PositionBehavior::className(),
                'positionAttribute' => 'order',
                'groupAttributes' => [
                    'nav_id'
                ],
            ],
        ];
    }

    public function getNav()
    {
        return $this->hasOne(Nav::className(), ['id' => 'nav_id']);
    }
}
