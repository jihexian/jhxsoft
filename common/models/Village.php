<?php
/**
 * 网点管理
 * 
 * Author wsyone wsyone@faxmail.com 
 * Time:2019年8月1日下午3:25:09
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

/**
 * This is the model class for table "{{%village}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $contact
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $money
 * @property integer $count
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $sort
 * @property integer $status
 */
class Village extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%village}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'address', 'phone', 'contact', 'province_id', 'city_id', 'district_id'], 'required'],
            [['province_id', 'city_id', 'district_id', 'money', 'count', 'sort','created_at','updated_at'], 'integer'],
            [['code'], 'string', 'max' => 100],
            [['name', 'address'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 30],
            [['contact'], 'string', 'max' => 20],
                [['status'], 'in', 'range' => [0,1]],
            [['code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '村点编号',
            'name' => '村点名称',
            'address' => '地址',
            'phone' => '电话',
            'contact' => '联系人',
            'province_id' => '省ID',
            'city_id' => '市ID',
            'district_id' => '区ID',
            'money' => '受帮扶金额',
            'count' => '受帮扶次数',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
    /**
     *
     * {@inheritDoc}
     *
     *
     */
    public function attributes ()
    {
        $attributes = parent::attributes();
        $attributes[] = 'total';
        
        return $attributes;
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [
                [
                        'class' => TimestampBehavior::className(),
                        'createdAtAttribute' => 'created_at',
                        
                        'updatedAtAttribute' => 'updated_at'
                ],
        ];
        return $behaviors;
        
    }
    public static function getCityList($pid)
    {
        $model = Region::find()->where(['parent_id'=>$pid])->asArray()->all();
        return ArrayHelper::map($model, 'id', 'name');
    }
  
   
    public function getVillagecommissionlog(){
        return $this->hasMany(VillageCommissionLog::className(), ['village_id'=>'id']);
    }
}
