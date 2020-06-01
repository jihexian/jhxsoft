<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%address}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $userName
 * @property string $postalCode
 * @property string $province_id
 * @property string $city_id
 * @property string $region_id
 * @property string $detailInfo
 * @property string $nationalCode
 * @property string $telNumber
 * @property integer $status
 * @property integer $sort
 * @property integer $is_default
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'userName', 'province_id', 'city_id', 'region_id', 'detailInfo', 'telNumber'], 'required'],
            [['uid', 'status','province_id', 'city_id', 'region_id', 'sort', 'is_default','is_pickup'], 'integer'],
            [['userName'], 'string', 'max' => 10],
            [['postalCode'], 'string', 'max' => 50],
            [['detailInfo'], 'string', 'max' => 255],
            [['nationalCode'], 'string', 'max' => 30],
            ['telNumber','required','message'=>'phone不能为空！'],
            ['telNumber','match','pattern' => '/^(((\\+\\d{2}-)?0\\d{2,3}-\\d{7,8})|((\\+\\d{2}-)?(\\d{2,3}-)?([1]\\d{10})))$/','message'=>'手机号格式不正确！'],
            ['is_default','default','value'=>0]

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'uid' => Yii::t('common', '用户id'),
            'userName' => Yii::t('common', '收件人'),
            'postalCode' => Yii::t('common', '邮政编码'),
            'province_id' => Yii::t('common', '省'),
            'city_id' => Yii::t('common', '市'),
            'region_id' => Yii::t('common', '区'),
            'detailInfo' => Yii::t('common', '详细地址'),
            'nationalCode' => Yii::t('common', '国家码'),
            'telNumber' => Yii::t('common', '电话号码'),
            'status' => Yii::t('common', '状态'),
            'sort' => Yii::t('common', '排序，默认99'),
            'is_default' => Yii::t('common', '是否为默认地址'),
            'is_pickup'=>Yii::t('common','送到提货点')
        ];
    }

    /**
     * @inheritdoc
     * @return AddressQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AddressQuery(get_called_class());
    }
    
    public function getProvince(){
        return $this->hasOne(Region::className(),['id'=>'province_id']);
        
    }
    public function getCity(){
        return $this->hasOne(Region::className(),['id'=>'city_id']);
    }
    public function getCounty(){
        return $this->hasOne(Region::className(),['id'=>'region_id']);
    }
}
