<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%platform_coupon_log}}".
 *
 * @property integer $id
 * @property string $money
 * @property integer $copuon_id
 * @property string $third_no
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $sort
 */
class PlatformCouponLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%platform_coupon_log}}';
    }
   
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money', 'coupon_id', 'third_no'], 'required'],
            [['id', 'coupon_id', 'status', 'sort'], 'integer'],
            [['money'], 'number'],
            [['third_no'], 'string', 'max' => 32],
        ];
    }

    
    /**
     * {@inheritdoc}
     */
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
            'money' => Yii::t('common', 'Money'),
            'coupon_id' => Yii::t('common', '优惠券Id'),
            'third_no' => Yii::t('common', '订单编号'),
            'status' => Yii::t('common', '0:生成 1已支付 2退款'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'sort' => Yii::t('common', 'Sort'),
        ];
    }
}
