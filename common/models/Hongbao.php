<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%hongbao}}".
 *
 * @property integer $id
 * @property integer $mid
 * @property integer $status
 * @property string $money
 * @property string $rest_money
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $version
 * @property string $info
 * @property integer $type
 * @property integer $send_num
 * @property string $code
 * @property string $password
 * @property string $sum_money
 */
class Hongbao extends \yii\db\ActiveRecord
{
    
    public $_received;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%hongbao}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mid', 'version'], 'integer'],
            ['money','required','message'=>'金额必填'],
            ['send_num','required','message'=>'人数必填'],
            ['type','required','message'=>'类型必选'],
            [['money', 'rest_money','sum_money'], 'number'],
            ['money', 'compare', 'compareValue' => 0.01, 'operator' => '>=','message'=>'单个红包金额必须大于0.01元'],
            ['money', 'compare', 'compareValue' => 200, 'operator' => '<=','message'=>'单个红包金额最大200元'],
            ['send_num', 'compare', 'compareValue' => 100, 'operator' => '<=','message'=>'最多可发100个人领'],
            [['info'], 'string'],
            [['status', 'type', 'send_num'], 'integer'],
            [['code', 'password'], 'string', 'max' => 255],
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
            'member',
            'received'
        ]);
        
        
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mid' => 'Mid',
            'status' => '状态',
            'money' => '单个红包金额',
            'rest_money' => '剩余金额',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'version' => 'Version',
            'info' => '记录',
            'type' => '类型',
            'send_num' => '发放个数',
            'code' => 'Code',
            'password' => 'Password',
        ];
    }
    
    public function getMember(){
        return $this->hasOne(Member::class, ['id'=>'mid'])->select("username,avatarUrl");
    }
    
    public function optimisticLock(){
        return "version";
    }
    
    public function setReceived($received){
        $this->_received = $received;
    }
    
    public function getReceived(){
        $num = 0;
        if (empty($this->info)) {
            $this->setReceived(0);
        }else{
            $info = Json::decode($this->info);
            foreach ($info as $v){
                if ($v['type']==1) {
                    $num++;
                }
            }
        }
        $this->setReceived($num);
        return $this->_received;
    }
}
