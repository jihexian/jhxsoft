<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_data}}".
 *
 * @property integer $order_id
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $use_start_time
 * @property integer $use_end_time
 */
class OrderData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_data}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_time', 'end_time', 'use_start_time', 'use_end_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => Yii::t('backend', 'Order ID'),
            'start_time' => Yii::t('backend', '活动开始时间'),
            'end_time' => Yii::t('backend', '活动结束时间'),
            'use_start_time' => Yii::t('backend', '使用开始时间 '),
            'use_end_time' => Yii::t('backend', '使用截止时间'),
        ];
    }
}
