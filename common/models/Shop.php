<?php

namespace common\models;

use common\behaviors\PositionBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\OptimisticLockBehavior;
use yii\db\AfterSaveEvent;
//use yii\behaviors\OptimisticLockBehavior;


/**
 * This is the model class for table "{{%shop}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property string $image
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property string $address
 * @property string $lng
 * @property string $lat
 * @property string $description
 * @property string $license
 * @property string $idcard
 * @property integer $type
 * @property integer $village_id
 * @property int $is_village
 * @property integer $category_id
 * @property string $percent
 * @property string $total_amount
 * @property string $money
 * @property string $version
 */
class Shop extends \yii\db\ActiveRecord
{
        
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop}}';
    }
    

    
   public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['category_id','type','tel', 'name','address','license','idcard','vrlink','map','lat','lng','business_hours'];
        $scenarios['edit']  = ['name','category_id','type', 'status','apply_status','tel','address','score','money','map','business_hours','lat','lng'];
        $scenarios['refuse']  = ['apply_status','comment'];
        return $scenarios;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort'], 'integer'],
            [['total_amount','percent','money'], 'number'],
            [['name', 'logo', 'image', 'address', 'license', 'idcard','vrlink'], 'string', 'max' => 255],
            [['name'],'unique'],
            [['status', 'type','village_id','is_village','category_id','apply_status','score'], 'integer'],
            [['description'], 'string', 'max' => 500],
            [['business_hours','map'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 15],
            [['lng','lat'], 'number'],
            ['name', 'unique', 'message' => '店铺名重复，请重新输入'],
            [['name','address','category_id'],'required','on'=>['create','edit']],
            [['comment'],'required','on'=>'refuse'],
       /*   ['percent','default','value'=>0.03],
            ['percent','compare','compareValue'=>0.03,'operator'=>'>='],
            ['percent','compare','compareValue'=>1,'operator'=>'<'], */
        ];
    }

    //post的时候过滤掉要过滤掉的信息
    public function  fields(){
        $fields = parent::fields();
        if(\Yii::$app->request->isPost){
            unset($fields['updated_at']);
        }
        return $fields;
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '店铺名称',
            'logo' => 'Logo',
            'image' => '图片',
            'created_at' => '创建日期',
            'updated_at' => '更新日期',
            'status' => '状态',
            'address' => '店铺地址',
            'mobile' => '手机号码',
            'description' => '描述',
            'license' => '营业执照',
            'idcard' => '身份证',
            'type' => '店铺类型',
            'village_id'=>'扶贫id',
            'is_village' => '扶贫商家',
            'category_id'=>'行业',
             'percent'=>'扶贫提成点',
            'total_amount'=>'总提成金额',
            'money'=>'店铺余额',
            'vrlink'=>'vr链接',
            'apply_status'=>'审核状态',
            'tel'=>'联系电话',
            'comment'=>'驳回原因',
            'score'=>'积分总额',
            'sort'=>'排序',
            'lat'=>'地图经度',
            'lng'=>'地图纬度',
            'map'=>'地图经纬度',
            'business_hours'=>'营业时间'
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
    	$behaviors = [
    		TimestampBehavior::className(),
    	    'positionBehavior' => [
    	        'class' => PositionBehavior::className(),
    	        'positionAttribute' => 'sort',
    	        'groupAttributes' => [
    	            'category_id'
    	        ],
    	    ],
    	];
    	return $behaviors;
    }


    public function getShopPay(){
        return $this->hasMany(ShopPay::className(), ['shop_id'=>'id']);
    }
    public function getCategory()
    {
        return $this->hasOne(ShopCategory::className(), ['id'=>'category_id']);
    }
    public function getShopcommissionlog(){
        return $this->hasMany(ShopCommissionLog::className(), ['shop_id'=>'id']);
    }

    public static function lists($module = null)
    {
        //$list = Yii::$app->cache->get(['productTypeList', $module]);
        $list = false;
        if ($list === false) {
            $query = static::find();
            $list = $query->orderBy(['sort' => SORT_ASC])->asArray()->all();
            //Yii::$app->cache->set(['productTypeList', $module], $list, 0, new TagDependency(['tags' => ['productTypeList']]));
        }
        return $list;
    }

    //显示状态
    public function renderStatus(){
        $statusList = [
                '1'=>'自营',
                '2' => '合作社',
                '3' => '企业',
                '4'=>'个人'
        ];
        return $statusList[$this->type];
    }
    public function applyStatus($status){
        switch($status){
            case 0:$apply_status='待审核';break;
            case 1:$apply_status='已通过';break;
            case 2:$apply_status='驳回重改';break;
            default:$apply_status='未知状态';break;     
        }
        return $apply_status;
    }
    
    public function beforeSave($insert)
    {
        if (!empty($this->map)) {
        
                $str=explode(',', $this->map);
                $this->lat=isset($str[0])?$str[0]:'';
                $this->lng=isset($str[1])?$str[1]:'';
        }
        return parent::beforeSave($insert);
    }
   
}
