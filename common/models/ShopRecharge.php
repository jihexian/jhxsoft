<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%shop_recharge}}".
 *
 * @property integer $id
 * @property string $order_no
 * @property string $pay_amount
 * @property integer $score
 * @property string $payment_code
 * @property string $payment_name
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $pay_status
 * @property string $transaction_id
 */
class ShopRecharge extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_recharge}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_no', 'shop_id', 'pay_amount'], 'required'],
            [['shop_id', 'score', 'pay_status'], 'integer'],
            [['pay_amount'], 'number'],
            [['order_no', 'payment_name'], 'string', 'max' => 45],
            [['payment_code'], 'string', 'max' => 15],
            [['transaction_id'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'order_no' => Yii::t('backend', '订单编号'),
            'shop_id' => Yii::t('backend', '店铺id'),
            'pay_amount' => Yii::t('backend', '支付金额'),
            'score' => Yii::t('backend', '积分'),
            'payment_code' => Yii::t('backend', '支付方式'),
            'payment_name' => Yii::t('backend', 'Payment Name'),
            'created_at' => Yii::t('backend', '创建时间'),
            'updated_at' => Yii::t('backend', '更新时间'),
            'pay_status' => Yii::t('backend', '支付状态'),
            'transaction_id' => Yii::t('backend', '交易流水号'),
        ];
    }
}
