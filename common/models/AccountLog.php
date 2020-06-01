<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\modules\user\models\User;

/**
 * This is the model class for table "{{%account_log}}".
 *
 * @property string $id
 * @property string $member_id
 * @property string $money
 * @property integer $score
 * @property integer $change_score
 * @property string $change_money
 * @property string $created_at
 * @property integer $type
 * @property string $desc
 * @property string $info
 * @property string $user_id
 */
class AccountLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_log}}';
    }
    public function behaviors()
    {
    	$behaviors = [
    			TimestampBehavior::className(),
    	];
    	return $behaviors;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['member_id', 'score', 'change_score',  'user_id','type'], 'integer'],

            [['money', 'change_money'], 'number'],
            [['desc'], 'string', 'max' => 255],   
            [['info'], 'string'],   
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'id'),
            'member_id' => Yii::t('common', '用户id'),
            'money' => Yii::t('common', '用户余额'),
            'score' => Yii::t('common', '用户积分'),
            'change_score' => Yii::t('common', '变动积分'),
            'change_money' => Yii::t('common', '变动金额'),
            'created_at' => Yii::t('common', '变动时间'),
            'type' => Yii::t('common', '变动类型'),
            'desc' => Yii::t('common', '描述'),
            'info' => Yii::t('common', '数据'),
            'user_id' => Yii::t('common', '管理员id'),
        	'member' => Yii::t('common', '用户昵称'),
        	'user'=>Yii::t('common', '操作管理员'),
        ];
    }
    public function  getMember(){
    	return Member::find()->select('username')->where(['id' => $this->member_id])->scalar();
    }
    public function  getUser(){
    	return User::find()->select('username')->where(['id' => $this->user_id])->scalar();
    }
    
    public function getTypeList(){
        return [1=>'订单消费',2=>'充值',3=>'活动赠送',4=>'管理员操作',5=>'到店支付',6=>'分销金额',7=>'订单退回',8=>'提现',9=>'红包',10=>'红包退回'];
    }
    //显示热销
    public function renderType(){
        
        switch($this->type){
            case 1:$txt='订单消费';break;
            case 2:$txt='充值';break;
            case 3:$txt='活动赠送';break;
            case 4:$txt='管理员操作';break;
            case 5:$txt='到店支付';break;
            case 6:$txt='分销金额';break;
            case 7:$txt='订单退回';break;
            case 8:$txt='提现';break;
            case 9:$txt='红包';break;
            case 10:$txt='红包退回';break;
            default:$txt='其它';break;
        }
        return $txt;
    }
    
}
