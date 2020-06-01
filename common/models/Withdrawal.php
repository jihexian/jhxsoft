<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use common\behaviors\SoftDeleteBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%withdrawal}}".
 *
 * @property integer $id
 * @property integer $m_id
 * @property string $pay_amount
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $pay_time
 * @property string $bank_name
 * @property string $bank_card
 * @property string $realname
 * @property string $remark
 * @property string $taxfee
 * @property integer $status
 * @property string $transaction_id
 * @property string $error_code
 */
class Withdrawal extends \yii\db\ActiveRecord
{
    
    

 
    public function behaviors(){
        return [
                
                [
                        'class'=>TimestampBehavior::className(),
                        'attributes' => [
                                // 当insert时,自动把当前时间戳填充填充指定的属性(created_at),
                                // 当然, 以下键值也可以是数组,
                                // eg: ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                                ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                                // 当update时,自动把当前时间戳填充指定的属性(updated_at)
                                ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                        ],           
                ],
                
             
        ];
    }
    
    public function optimisticLock()
    {
        return 'ver';
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%withdrawal}}';
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();//本行必填，不写的话就会报如上错误
        $scenarios['create'] = ['m_id','order_no','pay_amount','bank_name','bank_card','type'];
        $scenarios['bank_create'] = ['m_id','order_no','pay_amount','bank_name','bank_card','realname','type'];
        $scenarios['update'] = ['order_no','remark','status'];
        return $scenarios;
    }
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['m_id', 'pay_time','status','ver','type'], 'integer'],
            [['pay_amount', 'taxfee'], 'number'],
            [['bank_name',  'remark', 'error_code'], 'string', 'max' => 255],
            [[ 'transaction_id','bank_card'], 'string', 'max' => 100],
            [['payment_code','order_no','realname'], 'string', 'max' => 32],
            [['order_no'], 'unique'],
            ['type','default','value'=>0],
            [['order_no','bank_name','bank_card','pay_amount'],'required','on' => ['create']],
                [['order_no','bank_name','bank_card','pay_amount','realname'],'required','on' => ['bank_create']],
            [['pay_amount'], 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/','message'=>'支付金额最小单位为分'],
            ['pay_amount', 'compare', 'compareValue' => 100, 'operator' => '>='], 
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', '提现申请表'),
            'type'=>'提现类型',//0为余额 1为分销金额
            'm_id' => Yii::t('backend', '用户'),
            'pay_amount' => Yii::t('backend', '提现金额'),
            'created_at' => Yii::t('backend', '申请时间'),
            'updated_at' => Yii::t('backend', '审核时间'),
            'pay_time' => Yii::t('backend', '支付时间'),
            'payment_code'=>'支付方式code',
            'bank_name' => Yii::t('backend', '银行名称'),// 如支付宝 微信 中国银行 农业银行等
            'bank_card' => Yii::t('backend', '银行账号'),//支付宝账号
            'realname' => Yii::t('backend', '开户姓名'),
            'remark' => Yii::t('backend', '提现备注'),
            'taxfee' => Yii::t('backend', '税收手续费'),
            'status' => Yii::t('backend', '状态'),//-1删除作废0申请中1审核通过2付款成功3付款失败4审核失败
            'transaction_id' => Yii::t('backend', '付款对账流水号'),
            'error_code' => Yii::t('backend', '付款失败错误代码'),
        ];
    }
    public function getStatus($status){
        switch ($status){
     
            case 0:$txt='申请中';break;
            case 1:$txt='完成提现';break;
            case 2:$txt='拒绝申请';break;
            default:$txt='未知状态';break;
            
        }
        return $txt;
    }
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'm_id']);
    }
    public function getUsername()
    {
        if ($this->member) {
            return $this->member->username;
        }
        return 'Unknown';
    }
    
}
