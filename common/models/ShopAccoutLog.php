<?php

namespace common\models;

use Yii;

use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "{{%shop_accout_log}}".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $money
 * @property string $type
 * @property integer $order_id
 * @property integer $updated_at
 * @property integer $created_at
 * @property string $comment
 */
class ShopAccoutLog extends \yii\db\ActiveRecord
{

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
    public static function tableName()
    {
        return '{{%shop_accout_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['shop_id', 'type','score'], 'integer'],
            [['money','pay_amount','change_money'], 'number'],
            ['order_no','string','max'=>32],
            [['comment'], 'string', 'max' => 100],
            [['pay_amount'], 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/','message'=>'支付金额最小单位为分'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'shop_id' => Yii::t('backend', '店铺id'),
            'pay_amount'=>yii::t('backend','资金变动'),
            'money' => Yii::t('backend', '变动前余额'),
            'change_money'=>'资金变动',
            'score'=>'积分变动',
            'type' => Yii::t('backend', 'type'),
            'order_no'=>Yii::t('backend', 'order_no'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_at' => Yii::t('backend', 'Created At'),
            'comment' => Yii::t('backend', 'Comment'),
        ];
    }
    /**
     * @inheritdoc
     * @return ShopAccoutLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShopAccoutLogQuery(get_called_class());
    }
    
    public   function getType($type){
        switch ($type){
            case 1:$msg='订单收入';break;
            case 2:$msg='到店支付';break;
            case 3:$msg='提现';break;
            case 4:$msg='退款';break;
            case 5:$msg='退货';break;
            case 6:$msg='积分增加';break;
            case 7:$msg='积分消费';break;
            default:$msg='未知状态';break;
          
        }
        return $msg;
    }
    
}
