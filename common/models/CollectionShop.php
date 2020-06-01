<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%collectionshop}}".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property integer $member_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class CollectionShop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%collectionshop}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'member_id','created_at','updated_at'], 'integer'],
            ['member_id', 'exist', 'targetClass' => Member::className(), 'targetAttribute' => 'id'],
            ['shop_id', 'exist', 'targetClass' => Shop::className(), 'targetAttribute' => 'id'],
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'member_id' => 'Member ID',
            'created_at' => 'Created At',
        ];
    }
    public function getShop(){
        return $this->hasOne(Shop::className(),['id'=>'shop_id']);
    }
}
