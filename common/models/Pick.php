<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%pick}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $area_id
 * @property string $info
 * @property string $master
 * @property string $tel
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $sort
 */
class Pick extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pick}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'province_id', 'city_id', 'area_id', 'info','map','tel'], 'required'],
            [['sort'], 'integer'],
            [['name'], 'string', 'max' => 80],
            [['province_id', 'city_id', 'area_id','status','is_free'], 'number'],
            [['info'], 'string', 'max' => 245],
            [['master'], 'string', 'max' => 45],
            [['map'], 'string', 'max' => 50],
            [['tel'], 'string', 'max' => 15],
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        
        ];
    }
    


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', '站点名称'),
            'province_id' => Yii::t('backend', '省份'),
            'city_id' => Yii::t('backend', '城市'),
            'area_id' => Yii::t('backend', '区域'),
            'info' => Yii::t('backend', '详细地址'),
            'master' => Yii::t('backend', '负责人'),
            'tel' => Yii::t('backend', '站点电话'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'sort' => Yii::t('backend', 'Sort'),
            'map'=>Yii::t('backend', '经纬度'),
            'is_free'=>Yii::t('backend', '是否免邮'),
        ];
    }
    
    public function getProvince(){
        return $this->hasOne(Region::className(),['id'=>'province_id']);
    }
    
    public function getCity(){
        return $this->hasOne(Region::className(),['id'=>'city_id']);
    }
    
    public function getArea(){
        return $this->hasOne(Region::className(),['id'=>'area_id']);
    }
    
}
