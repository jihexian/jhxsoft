<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%recharge}}".
 *
 * @property integer $id
 * @property string $order_no
 * @property integer $m_id
 * @property string $pay_amount
 * @property integer $payment_code
 * @property string $payment_name
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $pay_status
 * @property string $transaction_id
 */
class Recharge extends \yii\db\ActiveRecord
{
    
    public $_username;
    public function behaviors(){
        return [
                
                [
                        'class'=>TimestampBehavior::className(),
                        'attributes' => [
                                // 当insert时,自动把当前时间戳填充填充指定的属性(created_at),
                                // 当然, 以下键值也可以是数组,
                                // eg: ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                                // 当update时,自动把当前时间戳填充指定的属性(updated_at)
                                ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                        ],
                ],
         
        ];
    }
    

 
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%recharge}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['order_no','pay_amount', 'm_id','payment_code'], 'required'],

            [['m_id',  'pay_status'], 'integer'],
            [['order_no', 'payment_name'], 'string', 'max' => 45],
            [['transaction_id'], 'string', 'max' => 36],
            [['payment_code'], 'string', 'max' => 15],
            ['pay_amount', 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/','message'=>'支付金额最小单位为分'],

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
            'm_id' => Yii::t('backend', '用户'),
            'pay_amount' => Yii::t('backend', '支付金额'),
            'payment_code' => Yii::t('backend', '支付方式'),
            'payment_name' => Yii::t('backend','支付方式'),
            'created_at' => Yii::t('backend', '创建时间'),
            'updated_at' => Yii::t('backend', '更新时间'),
            'pay_status' => Yii::t('backend', '支付状态'),
            'transaction_id' => Yii::t('backend', '支付流水号'),
        ];
    }
    
    public  function payStatus($status){
        switch ($status){
            case 0:$txt='待支付';break;
            case 1:$txt='已支付';break;
            case 2:$txt='订单关闭';break;
            default:$txt='未知状态';break;
           
        }
        return $txt;
    }
    
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'm_id']);
    }
    
    public function getUsername()
    {
        if ($this->member) {
            return $this->member->username; 
        }
        return 'Unknown';
    }
   
}
