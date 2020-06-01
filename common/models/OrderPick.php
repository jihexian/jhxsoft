<?php
/**
 * 
 * 
 * Author wsyone wsyone@faxmail.com
 * Time:2019年11月25日下午4:19:19
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\models;
use Yii;

/**
 * This is the model class for table "{{%order_pick}}".
 *
 * @property integer $id
 * @property string $order_id
 * @property string $pick_id
 * @property string $code
 * @property string $name
 * @property integer $mobile
 * @property string $remark
 * @property string $updated_at
 * @property string $created_at
 */
class OrderPick extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_pick}}';
    }
    public function scenarios()
    {
        $scenarios = parent::scenarios();//本行必填，负责没有default场景
        $scenarios['create']=['order_id','pick_id','code','created_at'];
        $scenarios['update']=['mobile','name','remark','updated_at'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'pick_id'], 'required'],
            [['mobile','order_id','pick_id'], 'integer'],
            [['mobile'], 'match', 'pattern' => '/^1[0-9]{10}$/','message'=>'手机号格式不正确！'],
            [['code', 'name'], 'string', 'max' => 45],
            [['remark'], 'string', 'max' => 245],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'order_id' => Yii::t('backend', '订单id'),
            'pick_id' => Yii::t('backend', '自提点id'),
            'code' => Yii::t('backend', 'Code'),
            'name' => Yii::t('backend', 'Name'),
            'mobile' => Yii::t('backend', 'Mobile'),
            'remark' => Yii::t('backend', 'Remark'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }
    public function getPick(){
        return $this->hasOne(Pick::className(), ['id'=>'pick_id']);  
    }
    
    public function getOrder(){
        return $this->hasOne(Order::className(), ['id'=>'order_id']);
    }
    
}
