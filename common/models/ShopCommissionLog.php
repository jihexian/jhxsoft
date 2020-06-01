<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "{{%shop_commission_log}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $order_no
 * @property integer $m_id
 * @property integer $shop_id
 * @property string $money
 * @property string $percentage
 * @property string $desc
 * @property integer $created_at
 * @property integer $updated_at
 */
class ShopCommissionLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_commission_log}}';
    }

    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
                'fupin'=>function ($model) {
                return empty($model->tags)?null : $model->tags;  //标签
                },             
                ]);
    }
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['m_id','shop_id','type'], 'integer'],
            [['money', 'percentage','pay_amount'], 'number'],
            [['order_no'], 'string', 'max' => 32],
            [['desc'], 'string', 'max' => 255],
            ['shop_id','required','message' => '店铺id不能为空'],
            [['money','percentage','pay_amount'], 'match', 'pattern' => '/^(-)?[0-9]+(.[0-9]{1,2})?$/','message'=>'支付金额最小单位为分'],
           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type'=>'类型',
            'order_no' => '订单编号',
            'm_id' => '下单用户',
            'shop_id' => '店铺id',
            'pay_amount'=>'支付金额',
            'money' => '实际收入金额',
            'percentage' => '平台费用',
            'desc' => '备注',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    public function behaviors()
    {
        $behaviors = [
                [
                        'class' => TimestampBehavior::className(),
                ],
                
        ];
        return $behaviors;
        
    }
    
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'm_id']);
    }
    public function getShop(){
        return $this->hasOne(Shop::className(), ['id'=>'shop_id']);
    }
    public function getShopcategory() {
        return $this->hasMany(ShopCategory::className(), ['id' => 'category_id'])->
        via('shop');
    }
  
  
}
