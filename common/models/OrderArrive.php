<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%order_arrive}}".
 *
 * @property integer $id
 * @property string $order_no
 * @property string $pay_amount
 * @property integer $m_id
 * @property integer $payment_status
 * @property integer $shop_id
 * @property integer $is_shop_checkout
 * @property string $order_price
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user_id
 * @property string $remark
 * @property string $payment_no
 * @property integer $payment_code
 * @property string $payment_name
 * @property integer $paytime
 */
class OrderArrive extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_arrive}}';
    }

    public function behaviors(){
        return [
            
            [
                'class'=>TimestampBehavior::className(),
               
            ],
            
        
        ];
    }
    
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_no'], 'string', 'max' => 32],
            [['order_no'], 'unique'],
            [['payment_code','pay_amount','shop_id'],'required'],
            [['pay_amount', 'order_price'], 'number'],
            [['m_id', 'shop_id', 'user_id', 'pay_time','payment_status', 'is_shop_checkout'], 'integer'],
            [[ 'remark'], 'string', 'max' => 255],
            [['payment_code'], 'string', 'max' => 15],
            [['payment_name'], 'string', 'max' => 50],  
            [['pay_amount','order_price'], 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/','message'=>'支付金额最小单位为分'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_no' => 'Order No',
            'pay_amount' => '支付金额',
            'm_id' => '用户',
            'payment_status' => 'Payment Status',
            'shop_id' => 'Shop ID',
            'is_shop_checkout' => 'Is Shop Checkout',
            'order_price' => 'Order Price',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'user_id' => '操作管理员',
            'remark' => '备注',
            'payment_no' => 'Payment No',
            'payment_code' => '支付方式',
            'payment_name' => 'Payment Name',
            'pay_time' => '支付时间',
        ];
    }
    
    public function getShop(){
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }
}
