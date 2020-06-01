<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%order_sku}}".
 *
 * @property string $id
 * @property string $order_id
 * @property string $order_no
 * @property integer $goods_id
 * @property string $goods_name
 * @property integer $sku_id
 * @property string $sku_no
 * @property string $sku_image
 * @property string $sku_thumbImg
 * @property string $sku_market_price
 * @property string $sku_sell_price
 * @property string $sku_sell_price_real
 * @property integer $sku_weight
 * @property string $sku_value
 * @property integer $is_send
 * @property integer $is_refund
 * @property integer $shop_id
 * @property integer $prom_type
 * @property integer $prom_id
 */
class OrderSku extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_sku}}';
    }

    
    
    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
              
               'order' => function ($model) {
                return $model->order;
                }
                ]);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'goods_id', 'sku_id'], 'required'],
            [['order_id', 'goods_id','num', 'shop_id','is_comment','is_send', 'is_refund','prom_type','prom_id'], 'integer'],
            [['sku_weight'], 'number'],
            [['sku_weight'], 'default', 'value' =>0],
            [['sku_market_price', 'sku_sell_price_real','sku_sell_price'], 'number'],
            [['sku_value','order_no'], 'string'],
            [['sku_id'], 'string','max'=>50],
            [['goods_name'], 'string', 'max' => 100],
            [['sku_no'], 'string', 'max' => 50],
            [['sku_image','sku_thumbImg'], 'string', 'max' => 255],
            [['sku_market_price','sku_sell_price_real','sku_sell_price'], 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/','message'=>'支付金额最小单位为分'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', '订单id'),
            'goods_id' => Yii::t('app', '商品id'),
            'goods_name' => Yii::t('app', '商品名称'),
            'sku_id' => Yii::t('app', 'skuid'),
            'sku_no' => Yii::t('app', 'sku编码'),
            'sku_image' => Yii::t('app', '商品图片'),
            'num' => Yii::t('app', '商品数量'),
            'sku_market_price' => Yii::t('app', '市场价格单位分'),
            'sku_sell_price_real' => Yii::t('app', '支付价格单位分'),
            'sku_weight' => Yii::t('app', '商品重量'),
            'sku_value' => Yii::t('app', '规格属性数组'),
            'is_send' => Yii::t('app', '是否已发货 0、未发货;1、已发货;'),
            'is_refund' => Yii::t('app', '是否退款0.为正常,1.退款中,2.退款完成3换货中4换货完成5维修中6维修完成7拒绝'),
            'is_comment' => Yii::t('app', '是否评价 0.待评价,1.已评价'),
            'shop_id' => Yii::t('app', '商品所属店铺id'),
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function getProduct(){
        return $this->hasOne(Product::className(),['product_id'=>'goods_id']);
    }

    public function getProductComment(){
        return $this->hasMany(Product::className(), ['order_sku_id' => 'id']);
    }

}
