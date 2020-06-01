<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%village_commission_log}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $order_no
 * @property integer $m_id
 * @property integer $shop_id
 * @property string $money
 * @property string $percentage
 * @property integer $village_id
 * @property string $desc
 * @property integer $created_at
 * @property integer $updated_at
 */
class VillageCommissionLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%village_commission_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['m_id', 'shop_id', 'village_id'], 'integer'],
            [['money', 'percentage'], 'number'],
            [['order_no'], 'string', 'max' => 32],
            [['desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        
            'order_no' => '订单编号',
            'm_id' => '用户id',
            'shop_id' => '店铺id',
            'money' => '下单金额',
            'percentage' => '扶贫基金',
            'village_id' => '扶贫点',
            'desc' => 'Desc',
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
    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }
    public function getVillage()
    {
        return $this->hasOne(RegionLocal::className(), ['id' => 'village_id']);
    }
}
