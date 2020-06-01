<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%shop_withdraw}}".
 *
 * @property integer $id
 * @property string $money
 * @property integer $shop_id
 * @property integer $apply_id
 * @property string $type
 * @property string $account
 * @property string $name
 * @property string $bank
 * @property integer $status
 * @property string $mark
 * @property integer $updated_at
 * @property integer $created_at
 */
class ShopWithdraw extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_withdraw}}';
    }
    public function scenarios()
    {
        $scenarios = parent::scenarios();//本行必填，不写的话就会报如上错
        $scenarios[ 'create']= ['money', 'shop_id', 'apply_id','status','bank','account','name','order_no','version'];
        $scenarios['update'] = ['status','version','transaction_id','pay_time'];
        return $scenarios;
    
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['money','taxfee'], 'number'],
            [['shop_id', 'apply_id', 'status','vesion','pay_time'], 'integer'],
            [['type', 'name','payment_code'], 'string', 'max' => 30],
            [['account'], 'string', 'max' => 50],
            ['transaction_id','string','max'=>100],
            [['bank', 'mark','error_code'], 'string', 'max' => 255],
            [['bank','account','name','shop_id','money','order_no'],'required',],
            [['money'], 'match', 'pattern' => '/^(-)?[0-9]+(.[0-9]{1,2})?$/','message'=>'支付金额最小单位为分'],
            ['money','compare', 'compareValue' => 100, 'operator' => '>=','on' => 'create'],
        ];
    }
    
    
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'money' => '金额',
            'shop_id' => '店铺id',
            'apply_id' => '申请人',
            'type' => '提现收款方式',
            'account' => '收款帐号',
            'name' => '收款人',
            'bank' => '银行',
            'status' => '状态',
            'mark' => '备注',
            'updated_at' => '审核时间',
            'created_at' => '申请时间',
            'taxfee' => Yii::t('backend', '手续费'),
            'payment_code'=>'支付方式code',
            'transaction_id' => Yii::t('backend', '付款对账流水号'),
            'error_code' => Yii::t('backend', '付款失败错误代码'),
        ];
    }
    
    public function getShop(){
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }
    public function getMember(){
        return $this->hasOne(Member::className(), ['id' => 'apply_id']);
    }
    public static function renderStatus($flag){
        switch ($flag){
            case 0: $status='待审核';break;
            case 1: $status='打款成功';break;
            case 2:$status='审核未通过';break;
            default:$status='未知状态';break;
        }
        return $status;
    }
    

}
