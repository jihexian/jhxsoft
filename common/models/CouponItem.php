<?php

namespace common\models;

use common\models\Member;
use common\models\Order;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%coupon_item}}".
 *
 * @property integer $id
 * @property integer $coupon_id
 * @property integer $is_active
 * @property string $code
 * @property string $password
 * @property integer $mid
 * @property integer $order_id
 * @property integer $use_time
 * @property integer $created_at
 */
class CouponItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id', 'mid', 'order_id', 'use_time','created_at'], 'integer'],
            [['code'], 'unique','message'=>'卡号重复！'],
            [['code'], 'string', 'max' => 255],
            ['coupon_id', 'exist', 'targetClass' => Coupon::className(), 'targetAttribute' => 'id'],
            ['mid', 'exist', 'targetClass' => Member::className(), 'targetAttribute' => 'id'],
            ['order_id', 'exist', 'targetClass' => Order::className(), 'targetAttribute' => 'id'],
            ['is_active','in','range'=>[0,1]],  
            ['is_active','default','value' =>1]   
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'coupon_id' => '优惠券名称',
            'code' => '卡号',
            'password'=>'密码',
            'mid' => '拥有者',
            'order_id' => '关联订单',
            'use_time' => '使用时间',
            'created_at' => '生成时间',
        ];
    }
    
    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
            
            'coupon',//加入后，可不用asArray带出信息
        ]);
        
        
    }
    public function behaviors(){
        $behaviors = [
            [
                'class'=>TimestampBehavior::className(),
            ]
        ];
        return $behaviors;
    }
    
    public function getCoupon(){
        return $this->hasOne(Coupon::className(), ['id'=>'coupon_id']);
    }
    
    public function getMember(){
        return $this->hasOne(Member::className(), ['id'=>'mid']);
    }
    
    public function validatePassword($password){
        $decryptedData = Yii::$app->security->decryptByPassword(base64_decode($this->password),$this->code);
        if ($decryptedData == $password) {
            return true;
        }else{
            return false;
        }
        //return 1;
    }
    
    public function optimisticLock(){
        return "version";
    }
}
