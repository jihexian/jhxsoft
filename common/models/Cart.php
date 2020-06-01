<?php

namespace common\models;
use Yii;
use yii\helpers\ArrayHelper;
use common\helpers\Util;
use yii\behaviors\TimestampBehavior;
use common\modules\promotion\models\FlashSale;
use common\behaviors\PromotionBehavior;
/**
 * This is the model class for table "{{%cart}}".
 *
 * @property string $id
 * @property string $user_id
 * @property string $session_id
 * @property string $product_id
 * @property string $product_sn
 * @property string $product_name
 * @property string $market_price
 * @property string $sale_price
 * @property string $sale_price_real
 * @property integer $num
 * @property string $sku_id
 * @property string $sku_value
 * @property string $bar_codeCart
 * @property integer $selected
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $prom_type
 * @property integer $prom_id
 * @property integer $shop_id
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart}}';
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [
            PromotionBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                
                'updatedAtAttribute' => 'update_time'
            ],
        ];
        return $behaviors;

    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id', 'num', 'create_time', 'update_time', 'prom_id','prom_type','shop_id'], 'integer'],
            [['market_price', 'sale_price', 'sale_price_real'], 'number'],
            [['session_id'], 'string', 'max' => 128],
            [['sku_values'], 'string', 'max' => 300],
            [['product_sn'], 'string', 'max' => 60],
            [['product_name'], 'string', 'max' => 120],
            [['sku_id', 'bar_code'], 'string', 'max' => 64],
          /*  [['selected', 'prom_type'], 'string', 'max' => 1],*/
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '购物车表'),
            'user_id' => Yii::t('app', '用户id'),
            'session_id' => Yii::t('app', 'session'),
            'product_id' => Yii::t('app', '商品id'),
            'product_sn' => Yii::t('app', '商品货号'),
            'product_name' => Yii::t('app', '商品名称'),
            'market_price' => Yii::t('app', '市场价'),
            'sale_price' => Yii::t('app', '本店价'),
            'sale_price_real' => Yii::t('app', '会员折扣价'),
            'num' => Yii::t('app', '购买数量'),
            'sku_id' => Yii::t('app', '对应sku_item表的sku_id'),
            'sku_values' => Yii::t('app', '商品规格组合名称'),
            'bar_code' => Yii::t('app', '商品条码'),
            'selected' => Yii::t('app', '购物车选中状态'),
            'create_time' => Yii::t('app', '加入购物车的时间'),
            'update_time' => Yii::t('app', 'Update Time'),
            'prom_type' => Yii::t('app', '0 普通订单,1 限时抢购, 2 团购 , 3 促销优惠'),
            'prom_id' => Yii::t('app', '活动id'),
        ];
    }

    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
            'prom_status',
            'proming_status',
            'prom_price',
            'prom_sku_id',
            'skus',//加入后，可不用asArray带出skus信息
        ]);


    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Member::className(), ['id' => 'user_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasMany(Product::className(), ['product_id' => 'product_id']);
    }
    /**
     * [getSkus description]
     * @return string description
     */
    public function getSkus(){
        return $this->hasOne(Skus::className(),['sku_id'=>'sku_id']);
    }
  
    public function getShop(){
        return $this->hasOne(Shop::className(),['id'=>'shop_id']);
    }

}
