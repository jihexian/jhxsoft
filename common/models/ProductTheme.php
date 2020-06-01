<?php

namespace common\models;

use common\behaviors\PositionBehavior;
use common\behaviors\SoftDeleteBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%product_theme}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $carousels
 * @property string $bgim
 * @property string $image
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $town_id
 * @property integer $province_id
 * @property integer $village_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $sort
 * @property integer $status
 */
class ProductTheme extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_theme}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name','required'],            
            [['carousels'], 'string'],
            [['city_id', 'district_id', 'town_id', 'village_id','province_id','sort'], 'integer','skipOnEmpty' => true,],
            [['name', 'bgim','image'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'positionBehavior' => [
                'class' => PositionBehavior::className(),
                'positionAttribute' => 'sort',                
            ],
			[
    			'class' => SoftDeleteBehavior::className(),
    			'softDeleteAttributeValues' => [
        					'status' => 2
        			],
    			'restoreAttributeValues' => [
        					'status' => 1
    			],
			],
            TimestampBehavior::className(),
            
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '主题名称',
            'carousels' => '幻灯片',
            'bgim' => '背景图',
            'province_id' => '省份',
            'city_id' => '城市',
            'district_id' => '地区',
            'town_id' => '县/镇',
            'village_id' => '村',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'sort' => '排序',
            'status' => '状态',
            'image' => '主图',
        ];
    }
    public function beforeValidate() {
        //处理图片信息
        $images = $this->carousels;
        if(is_array($images)) {
            if (!isset($images[0]['url'])) {
                $this->addError("carousels",'必须上传一个幻灯片');
                return false;
            }
            if (strlen($images[0]['url'])==0) {
                $this->addError("carousels",'必须上传一个幻灯片');
                return false;
            }
            $images = array_filter($images);
            ArrayHelper::multisort($images,'order');
            $this->carousels = json_encode($images);
        }
        
        return parent::beforeValidate();        
    }
    public function afterFind(){
        //处理图片信息
        if (strlen($this->carousels)>0){
            $images =  $this->carousels;
            $this->carousels = json_decode($images,true);
        }
        if (count($this->carousels)<=0) {
            $this->carousels = '[{"url":"'.Yii::$app->params['defaultImg']['default'].'","thumbImg":"'.Yii::$app->params['defaultImg']['default'].'","order":"0"}]';
            $images =  $this->carousels;
            $this->carousels = json_decode($images,true);
        }
        
        
        return parent::afterFind();
    }
    
    
    
    public function getProvince(){
        return $this->hasOne(RegionLocal::class, ['id'=>'province_id']);
    }
    public function getCity(){
        return $this->hasOne(RegionLocal::class, ['id'=>'city_id']);
    }
    public function getDistrict(){
        return $this->hasOne(RegionLocal::class, ['id'=>'district_id']);
    }
    public function getTown(){
        return $this->hasOne(RegionLocal::class, ['id'=>'town_id']);
    }
    public function getVillage(){
        return $this->hasOne(RegionLocal::class, ['id'=>'village_id']);
    }
    
}
