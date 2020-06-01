<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%distribut_log}}".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $cid
 * @property integer $level
 * @property integer $goods_id
 * @property string $change_money
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $order_no
 */
class DistributLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%distribut_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'cid', 'level', 'goods_id', 'status','type','m_id'], 'integer'],
            [['change_money'], 'number'],
            [['order_no'], 'string', 'max' => 32],
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
    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
            
            'subMember',//加入后，可不用asArray带出信息
        ]);
        
        
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '用户id',
            'cid' => '下级用户id',
            'level' => '分销等级',
            'goods_id' => '商品id',
            'change_money' => '金额',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'order_no' => Yii::t('common', '订单号'),
            'type'=>'用户类型',
            'm_id'=>'用户'
            
        ];
    }
    
    public function getMember(){
        return $this->hasOne(Member::className(), ['id' => 'pid'])->select(['username','avatarUrl']);
    }
    
    public function getSubMember(){
        return $this->hasOne(Member::className(), ['id' => 'cid'])->select(['username','avatarUrl']);
    }

    public function getProduct(){
        return $this->hasOne(Product::className(),['product_id'=>'goods_id']);
    }
}
