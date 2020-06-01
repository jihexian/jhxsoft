<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年12月18日 下午5:32:05
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\models;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use common\models\OrderQuery;
use common\models\OrderLog;
use common\behaviors\SoftDeleteBehavior;
use Yii;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use common\modules\coupon\models\CouponItem;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property string $id
 * @property integer $m_id
 * @property string $order_no
 * @property integer $payment_code
 * @property string $payment_name
 * @property integer $payment_status
 * @property string $payment_no
 * @property integer $delivery_id
 * @property string $delivery_name
 * @property string $delivery_time
 * @property integer $delivery_status
 * @property integer $shop_id
 * @property integer $is_shop_checkout
 * @property integer $status
 * @property string $full_name
 * @property string $tel
 * @property string $prov
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $sku_price
 * @property string $sku_price_real
 * @property string $delivery_price
 * @property string $delivery_price_real
 * @property string $discount_price
 * @property string $order_price
 * @property string $pay_amount
 * @property integer $coupons_id
 * @property string $coupons_price
 * @property integer $integral
 * @property string $integral_money
 * @property string $user_money
 * @property string $m_desc
 * @property string $admin_desc
 * @property integer $create_time
 * @property integer $paytime
 * @property integer $sendtime
 * @property integer $completetime
 * @property integer $is_del
 * @property string $invoice_title
 * @property string $taxpayer
 * @property integer $is_distribut
 * @property string $paid_money
 * @property integer $update_time
 * @property string $parent_sn
 * @property integer $prom_type
 * @property integer $prom_id
 * @property integer $version
 */
class Order extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }


    public function behaviors(){
        return [
          
            [
                'class'=>TimestampBehavior::className(),
                'attributes'=>[
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ]
            ],
            
            [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'is_del' => 1
                ],
                'restoreAttributeValues' => [
                    'is_del' => 0
                ],
                'invokeDeleteEvents' => false // 不触发删除相关事件
            ]
        ];
    }
    
    public function fields()
    {
        $fields = parent::fields();
        $fields['shop_name'] = function (){
            return $this->shop->name;
        };
     
        return $fields;
    }
 
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();//本行必填，负责没有default场景
        $scenarios[self::SCENARIO_CREATE] =['m_id','order_no','payment_code','sku_price', 'delivery_price','sku_price_real', 'delivery_price_real', 'order_price', 'pay_amount','integral_money','integral'];
        $scenarios[self::SCENARIO_UPDATE] =['id','pay_amount','delivery_price_real','delivery_time','order_no','status','integral_money','integral','discount_price'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['m_id',  'delivery_id', 'shop_id', 'is_shop_checkout',  'coupons_id', 'integral', 'create_time', 'paytime', 'sendtime', 'completetime', 'update_time', 'prom_type', 'prom_id','version'], 'integer'],
            [['sku_price', 'sku_price_real', 'delivery_price', 'delivery_price_real','payment_status','is_del', 'delivery_status', 'discount_price', 'order_price', 'pay_amount', 'coupons_price', 'integral_money', 'user_money', 'paid_money'], 'number'],
            [['order_no'], 'string', 'max' => 32],
            [['order_no'], 'unique'],
            [['payment_code'], 'string', 'max' => 15],
            [['status'],'in','range' => [0,1,2,3,4,5,6,7,8,9,10,11]],
            [['payment_name', 'delivery_name', 'taxpayer'], 'string', 'max' => 45],
            [[ 'is_distribut'], 'string', 'max' => 1],
            [['payment_no',  'full_name', 'tel'], 'string', 'max' => 50],
            [['province_id', 'city_id', 'region_id'], 'integer'],
            [['province_id', 'city_id', 'region_id'], 'required'],
            [['address'], 'string', 'max' => 200],
            [['m_desc', 'admin_desc'], 'string', 'max' => 255],
            [['invoice_title'], 'string', 'max' => 145],
            [['parent_sn'], 'string', 'max' => 80],
            [['pay_amount'], 'compare', 'compareValue' => 0, 'operator' => '>='],
            [['m_desc', 'admin_desc','invoice_title'],'safe'],
            [['sku_price', 'sku_price_real', 'delivery_price', 'delivery_price_real', 'order_price', 'pay_amount', 'coupons_price', 'integral_money', 'user_money', 'paid_money'], 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/','message'=>'支付金额最小单位为分'],
            [['discount_price',], 'match', 'pattern' => '/^(-)?[0-9]+(.[0-9]{1,2})?$/','message'=>'支付金额最小单位为分'],
            ['tel','match','pattern' => '/^(((\\+\\d{2}-)?0\\d{2,3}-\\d{7,8})|((\\+\\d{2}-)?(\\d{2,3}-)?([1]\\d{10})))$/','message'=>'手机号格式不正确！'],
            ['extend', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'm_id' => Yii::t('common', '用户id'),
            'order_no' => Yii::t('common', '订单号'),
            'payment_code' => Yii::t('common', '支付方式'),//0为货到付款
            'payment_name' => Yii::t('common', '支付方式名称'),
            'payment_status' => Yii::t('common', '支付状态'),//0未支付1已经支付
            'payment_no' => Yii::t('common', '第三方支付交易号'),
            'delivery_id' => Yii::t('common', '配送方式'),
            'delivery_name' => Yii::t('common', '快递名称'),
            'delivery_time' => Yii::t('common', '配送时间'),
            'delivery_status' => Yii::t('common', '发货状态'),//0未发货1已发货2为部分发货
            'shop_id' => Yii::t('common', '店铺id'),
            'is_shop_checkout' => Yii::t('common', '结算货款'),//是否给店铺结算货款 0:未结算;2:等待结算1:已结算
            'status' => Yii::t('common', '订单状态'),// 1生成订单,2支付订单,3已经发货,4完成订单,5已经评价6退款,7部分退款8用户取消订单,9作废订单,10退款中
            'full_name' => Yii::t('common', '收货人'),
            'tel' => Yii::t('common', '电话'),
            'province_id' => Yii::t('common', '省'),
            'city_id' => Yii::t('common', '市'),
            'region_id' => Yii::t('common', '区'),
            'address' => Yii::t('common', '详细地址'),
            'sku_price' => Yii::t('common', '商品市场总价单位'),
            'sku_price_real' => Yii::t('common', '商品销售价格单位'),
            'delivery_price' => Yii::t('common', '物流原价单位'),
            'delivery_price_real' => Yii::t('common', '物流支付价格单位'),
            'discount_price' => Yii::t('common', '改价金额单位'),
            'order_price' => Yii::t('common', '订单总金额单位'),
            'pay_amount' => Yii::t('common', '应付总价'),//订单总价order_price+邮费价格deliver_price+改价金额+活动减价+积分抵扣-用户使用余额
            'coupons_id' => Yii::t('common', '优惠券id'),
            'coupons_price' => Yii::t('common', '优惠券金额'),
            'integral' => Yii::t('common', '使用积分'),
            'integral_money' => Yii::t('common', '积分抵扣金额'),
            'user_money' => Yii::t('common', '用户使用余额'),
            'm_desc' => Yii::t('common', '用户备注'),
            'admin_desc' => Yii::t('common', '管理员备注'),
            'create_time' => Yii::t('common', '下单时间'),
            'paytime' => Yii::t('common', '支付时间'),
            'sendtime' => Yii::t('common', '发货时间'),
            'completetime' => Yii::t('common', '完成时间'),
            'is_del' => Yii::t('common', '0为正常1为删除'),
            'invoice_title' => Yii::t('common', '发票抬头'),
            'taxpayer' => Yii::t('common', '税务识别号'),
            'is_distribut' => Yii::t('common', '是否已分成'),
            'paid_money' => Yii::t('common', '订金'),
            'update_time' => Yii::t('common', 'Update Time'),
            'parent_sn' => Yii::t('common', '父单单号'),
            'prom_type' => Yii::t('common', 'Prom Type'),
            'prom_id' => Yii::t('common', 'Prom ID'),
        ];
    }

    public function getOrderSku()
    {
        return $this->hasMany(OrderSku::className(), ['order_id' =>'id']);
    }
    
    public function getShop(){
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }
    
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'm_id']);
    }
    public function getCouponItem()
    {
        return $this->hasOne(CouponItem::className(), ['id' => 'coupons_id']);
    }
    
    public function getOrderDeliveryDoc(){
        return $this->hasOne(OrderDeliveryDoc::className(),['order_id'=>'id']);
    }
   
    public function getOrderRefundDoc(){
        return $this->hasMany(OrderRefundDoc::className(), ['order_id' => 'id']);
    }
    public function getData(){
        return $this->hasOne(OrderData::className(), ['order_id'=>'id']);
    }
    
    public function getOrderPick(){
        return $this->hasOne(OrderPick::className(),['order_id'=>'id']);
    }

    /**
     * @return array 订单状态
     */
    public  function getStatusList()
    {
        return [
          
            1=> '待支付',
            2=> '待发货',
            3=> '已发货',
            4=> '待评价',
            5=> '已评价',
            6=> '已退款',
            7=> '部分退款',
            8=> '用户取消',
            9=> '超时作废',
            10=> '退款中',
            11=>'退款失败',
        ];
    }
    public  function getPayStatusList()
    {
        return [

            0=> '待支付',
            1=> '已支付',
        ];
    }
    
    public static function get_num($order_no){
        $num=0;
        $data=self::findOne(['order_no'=>$order_no]);
        foreach($data['orderSku'] as $vo){
            $num+=$vo['num'];
        }
        return $num;
    }
    /**
     * @inheritdoc
     * @return ActiveQuery
     */
    public static function find()
    {
        return Yii::createObject(OrderQuery::className(), [get_called_class()]);
    }
    
    public function optimisticLock(){
        return "version";
    }
    
    public function getProvince(){
        return $this->hasOne(Region::className(),['id'=>'province_id']);
        
    }
    public function getCity(){
        return $this->hasOne(Region::className(),['id'=>'city_id']);
    }
    public function getRegion(){
        return $this->hasOne(Region::className(),['id'=>'region_id']);
    }
    
 
}
