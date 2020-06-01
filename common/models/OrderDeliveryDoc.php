<?php

namespace common\models;
use common\models\ShippingCompany;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%order_delivery_doc}}".
 *
 * @property string $id
 * @property string $order_id
 * @property string $m_id
 * @property string $shop_id
 * @property integer $addtime
 * @property string $delivery_code
 * @property integer $express_company_id
 * @property string $note
 * @property string $admin_user
 */
class OrderDeliveryDoc extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    
    public function behaviors(){
        return [
            
            [
                'class'=>TimestampBehavior::className(),
                'attributes'=>[
                    ActiveRecord::EVENT_BEFORE_INSERT => ['addtime'],
                  
                ]
            ]
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_delivery_doc}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'm_id', 'delivery_code', 'shipping_code','shipping_name'], 'required'],
            //[['delivery_code'], 'unique', 'message' => '已经被使用'],
            [['order_id', 'm_id', 'shop_id', 'addtime','admin_user', ], 'integer'],
            [['note','shipping_code','shipping_name'], 'string'],
            [['delivery_code'], 'match', 'pattern' => '/^[a-zA-Z0-9]{5,18}$/','message'=>'快递单号格式不对'],
            [['delivery_code'],'unique','message'=>'{attribute}已经被占用了'],
          
           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'order_id' => Yii::t('common', '订单ID'),
            'm_id' => Yii::t('common', '用户ID'),
            'shop_id' => Yii::t('common', '店铺ID'),
            'addtime' => Yii::t('common', '创建时间'),
            'delivery_code' => Yii::t('common', '物流单号'),
            'shipping_code' => Yii::t('common', '物流编码'),
            'shipping_name' => Yii::t('common', '物流公司'),
            'note' => Yii::t('common', '备注信息'),
            'admin_user' => Yii::t('common', '管理员id'),
        ];
    }
    
    public function getshippingCompany()
    {
        return $this->hasOne(ShippingCompany::className(), ['code' => 'shipping_code']);
    }
    
    public function getorder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
    
}
