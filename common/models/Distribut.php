<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%distribut}}".
 *
 * @property integer $Id
 * @property integer $level
 * @property integer $pid
 * @property integer $cid
 * @property integer $created_at
 * @property integer $updated_at
 */
class Distribut extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%distribut}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'pid', 'cid'], 'integer'],
            [['pid','cid','level'],'required']
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
            
            'member',//加入后，可不用asArray带出信息
        ]);
        
        
    }


    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'level' => Yii::t('backend', '分销等级'),
            'pid' => Yii::t('backend', '上级iD'),
            'cid' => Yii::t('backend', '下级id'),
            'num' => Yii::t('backend', 'Num'),
            'amount' => Yii::t('backend', '总金额'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
        ];
    }
    
    public function getMember(){
        return $this->hasOne(Member::className(), ['id' => 'cid'])->select(['username','avatarUrl']);
    }
}
