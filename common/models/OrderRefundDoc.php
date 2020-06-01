<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;

use Yii;
use common\models\query\OrderRefundDocQuery;

/**
 * This is the model class for table "{{%order_refund_doc}}".
 *
 * @property string $id
 * @property string $order_id
 * @property string $m_id
 * @property string $goods_id
 * @property string $sku_id
 * @property string $note
 * @property integer $addtime
 * @property integer $status
 * @property integer $dispose_time
 * @property string $admin_user
 * @property string $shop_id
 * @property string $amount
 */
class OrderRefundDoc extends \yii\db\ActiveRecord
{
    
    

    const SCENARIO_CREATE='create';
    const SCENARIO_UPDATE='update';
    public function behaviors()
    {
        return [
            [  // 配置用户信息
                'class' => BlameableBehavior::className(),  // 行为类
                'createdByAttribute' => false,
                'updatedByAttribute' => 'admin_user',
            ],
            [
                'class'=>TimestampBehavior::className(),
                'attributes'=>[
                    ActiveRecord::EVENT_BEFORE_INSERT => ['addtime'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['dispose_time'],
                ]
            ],
           
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_refund_doc}}';
    }
    
    public function scenarios()
    {
      
        
        $scenarios = parent::scenarios();//本行必填，负责没有default场景
        $scenarios[self::SCENARIO_CREATE] =['m_id','note','order_id','type','amount','addtime'];
        $scenarios[self::SCENARIO_UPDATE] =['id','amount','status','message','check_status','admin_user','dispose_time','out_refund_no'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id','type'], 'required', 'on' => ['create']],
            [['order_id','message','check_status','message'], 'required', 'on' => ['update']],
            [['order_id',  'addtime', 'dispose_time', 'shop_id','status','type','refund_time'], 'integer'],
            [['note','message'], 'string'],
            [['out_refund_no'], 'string', 'max' => 64],
            [['amount'], 'required'],
            [['amount'], 'number'],
            [['amount'], 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/','message'=>'支付金额最小单位为分'],
       
          
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'order_id' => Yii::t('common', '订单id'),
            'm_id' => Yii::t('common', '用户ID'),
            'note' => Yii::t('common', '退款理由'),
            'message' => Yii::t('common', '操作备注'),
            'addtime' => Yii::t('common', '时间'),
            'status' => Yii::t('common', '退款状态'),//0:申请退款 1:退款失败 2:退款成功
            'dispose_time' => Yii::t('common', '处理时间'),
            'admin_user' => Yii::t('common', '管理员'),
            'shop_id' => Yii::t('common', '店铺'),
            'amount' => Yii::t('common', '金额'),
            'check_status'=> Yii::t('common', '审核意见'),
            'out_refund_no'=>Yii::t('common','退款清算编号'),
        ];
    }
    public function getOrder(){
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
    
    public function getOrderSku(){
        return $this->hasMany(OrderSku::className(), ['order_id' => 'order_id']);
    }
    
    public function getMember(){
        return $this->hasONe(Member::className(),['id'=>'m_id']);
    }
    /**
     * @inheritdoc
     * @return OrderRefundDocQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderRefundDocQuery(get_called_class());
    }
}
