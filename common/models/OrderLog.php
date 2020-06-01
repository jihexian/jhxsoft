<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%order_log}}".
 *
 * @property string $id
 * @property string $order_no
 * @property integer $action_user
 * @property integer $order_status
 * @property integer $shipping_status
 * @property integer $pay_status
 * @property string $action_note
 * @property integer $create_time
 * @property string $status_desc
 */
class OrderLog extends \yii\db\ActiveRecord
{
    public $num;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_log}}';
    }

    /**
    * @自动更新时间
    */

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['create_time'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_no'], 'required'],

            [['user_id', 'order_status', 'shipping_status', 'pay_status', 'create_time','shop_id'], 'integer'],
            [['action_note','action_user'], 'string', 'max' => 255],
            [['status_desc'], 'string', 'max' => 45],
            [['order_no'], 'string', 'max' => 32],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'order_no' => Yii::t('backend', 'Order ID'),
            'action_user' => Yii::t('backend', 'Action User'),
            'user_id' => Yii::t('backend', '操作人id'),
            'order_status' => Yii::t('backend', 'Order Status'),
            'shipping_status' => Yii::t('backend', 'Shipping Status'),
            'pay_status' => Yii::t('backend', 'Pay Status'),
            'action_note' => Yii::t('backend', 'Action Note'),
            'create_time' => Yii::t('backend', 'Create Time'),
            'status_desc' => Yii::t('backend', 'Status Desc'),
            'shop_id'=>'店铺',
        ];
    }
    
    /**
     * @return array 订单状态
     */
    public  function getStatusList()
    {
        return [
            3=> '待核销',
            5=> '已核销',
            6=> '已退款',
        ];
    }
    public function getOrder(){
        return $this->hasOne(Order::className(),['order_no'=>'order_no']);
    }
    public function getShop(){
        return $this->hasOne(Shop::className(),['id'=>'shop_id']);
    }

  /*   public function getNum(){
        $this->num=Order::get_num($this->order_no);
        return $this->num;
    } 
    
    public function afterFind(){
        $this->num = $this->getNum();
    }*/

}
