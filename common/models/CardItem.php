<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "{{%card_item}}".
 *
 * @property integer $id
 * @property integer $card_id
 * @property integer $use_time
 * @property integer $mid
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $version
 * @property string $info
 * @property string $password
 * @property string $card_no
 */
class CardItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%card_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_id', 'use_time', 'mid', 'version'], 'integer'],
            [['info'], 'string'],
            [['password', 'card_no'], 'string', 'max' => 255],
            ['card_no','unique','message'=>'卡号重复']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'card_id' => '卡券名称',
            'use_time' => '使用时间',
            'mid' => '拥有者',
            'created_at' => '生成时间',
            'updated_at' => '更新时间',
            'version' => 'Version',
            'info' => 'Info',
            'password' => '密码',
            'card_no' => '卡号',
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
    public function validatePassword($password){
        $decryptedData = Yii::$app->security->decryptByPassword(base64_decode($this->password),$this->card_no);
        if ($decryptedData == $password) {
            return true;
        }else{
            return false;
        }
    }
    
    public function getCard(){
        return $this->hasOne(Card::class, ['id'=>'card_id']);
    }
    public function optimisticLock(){
        return "version";
    }
    public function getMember(){
        return $this->hasOne(Member::className(), ['id'=>'mid']);
    }
}
