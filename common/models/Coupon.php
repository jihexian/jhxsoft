<?php

namespace common\models;

use common\behaviors\CheckShopBehavior;
use common\behaviors\SoftDeleteBehavior;
use common\models\Shop;
use Yii;
use yii\behaviors\TimestampBehavior;
use backend\models\Product;

/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $use_type
 * @property string $use_money
 * @property string $money_condition
 * @property integer $create_num
 * @property integer $send_num
 * @property integer $send_start
 * @property integer $send_end
 * @property integer $use_start
 * @property integer $use_end
 * @property integer $created_at
 * @property integer $shop_id
 * @property integer $status
 * @property integer $product_limiter
 * @property string $product_limiter_id
 * @property integer $receive_limiter
 */
class Coupon extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['use_money', 'money_condition'], 'number'],
            [['send_start','send_end','use_start','use_end'], 'filter', 'filter' => function($value) {
                return is_numeric($value) ? $value : strtotime($value);
            }, 'skipOnEmpty' => true],
            [['name','send_start','send_end','use_start','use_money','use_end'],'required'],
            [['create_num', 'send_num','shop_id','status'], 'integer'],            
            [['name'], 'string', 'max' => 255],
            ['product_limiter','in','range'=>[0,1,2]],
            [['product_limiter_id','receive_limiter','deleted_at'],'integer'],
            [['type', 'use_type'], 'integer'],
            [['status','use_type','receive_limiter'], 'default', 'value' => 1],
            ['send_end', 'compare', 'compareAttribute' => 'send_start', 'operator' => '>','message'=>'发放截止时间必须大于发放开始时间'],
            ['use_end', 'compare', 'compareAttribute' => 'use_start', 'operator' => '>','message'=>'使用截止时间必须大于使用开始时间'],
            ['use_end', 'compare', 'compareAttribute' => 'send_end', 'operator' => '>','message'=>'使用截止时间必须大于领取截止时间'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [    
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
            [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'deleted_at' => function ($model) {return time();}
            ],
            'restoreAttributeValues' => [
                'deleted_at' => null
            ],
            'invokeDeleteEvents' => false // 不触发删除相关事件
            ],
            CheckShopBehavior::className(),
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '模板名称',
            'type' => '获取方式',
            'use_type' => '优惠形式',
            'use_money' => '抵扣额度/抵扣比例',
            'money_condition' => '使用门槛(满x元可用)',
            'create_num' => '发放总量',
            'send_num' => '已领取数量',
            'send_start' => '发放开始时间',
            'send_end' => '发放结束时间',
            'use_start' => '使用开始时间',
            'use_end' => '使用结束时间',
            'created_at' => '创建时间',
            'shop_id' => '店铺id',
            'status'=>'状态',
            'product_limiter'=>'商品限定',
            'receive_limiter'=>'最多可同时拥有可用券数量',
            'deleted_at'=>'删除时间'
        ];
    }
    
    public static function getTypes(){
        return [1=>'主动领取',2=>'卡密激活'];
    }
    public static function getProductLimiters(){
        //return [0=>'无限定',1=>'单商品',2=>'单类目'];
        return [0=>'无限定',1=>'单商品'];
    }
    public static function getUseTypes(){
        return [1=>'满减',2=>'折扣'];
    }
    public static function getStatusEnum(){
        return [1=>'启用',2=>'禁用',0=>'删除'];
    }
    
    public function getShop(){
        return $this->hasOne(Shop::className(), ['id'=>'shop_id']);
    }
    
    public function getProduct(){
        if ($this->product_limiter==1) {
            return $this->hasOne(Product::className(), ['product_id'=>'product_limiter_id']);            
        }else{
            return null;
        }
    }
    
    public function attributes ()
    {
        $attributes = parent::attributes();
        $attributes[] = 'is_received';
        return $attributes;
    }  
   
    /**
     * @inheritdoc
     * @return CouponQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return Yii::createObject(CouponQuery::className(), [get_called_class()]);
    }
    
}
