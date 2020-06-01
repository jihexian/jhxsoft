<?php

namespace common\models;

use yii\helpers\ArrayHelper;
use common\helpers\Util;
use yii\behaviors\TimestampBehavior;
use common\behaviors\PromotionBehavior;
use common\behaviors\CheckShopBehavior;
use common\models\query\ProductQuery;
use common\behaviors\SoftDeleteBehavior;
use common\models\RegionLocal;
use Yii;
use api\modules\v1\models\ProductCategory;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property string $product_id
 * @property string $name
 * @property integer $model_id
 * @property integer $cat_id
 * @property integer $type_id
 * @property integer $brand_id
 * @property integer $up_time
 * @property integer $down_time
 * @property integer $create_at
 * @property integer $update_at
 * @property string $image
 * @property string $unit
 * @property integer $status
 * @property integer $visit
 * @property integer $favorite
 * @property integer $sortnum
 * @property integer $comments
 * @property integer $sale
 * @property integer $shop_id
 * @property string $max_price
 * @property string $min_price
 * @property integer $stock
 * @property integer $self_lift
 * @property integer $express
 * @property integer $city_wide
 * @property string $content
 * @property integer $markdown
 * @property integer $is_free
 * @property integer $is_index_show
 * @property integer $shipping_id
 * @property integer $prom_id
 * @property integer $prom_type
 * @property string $distribute_money
 * @property integer $city_id
 * @property integer $district_id
 * @property integer $town_id
 * @property integer $province_id
 * @property integer $village_id
 */
class Product extends \yii\db\ActiveRecord
{
    public $_percent;
    public $_fupin;
    
	const STATUS_DELETE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DOWN = 2;
    const STATUS_REPLY = 3;
    const STATUS_REFUSE = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }
   

    
    public function scenarios()
    {
        $scenarios = parent::scenarios();//本行必填，不写的话就会报如上错
        $scenarios[ 'seller']= ['model_id', 'cat_id', 'type_id','name','image','unit','content','status','brand_id', 'up_time','down_time', 'create_at', 'update_at', 'visit', 'favorite', 'sortnum', 'comments','prom_id','prom_type', 'sale',  'shop_id','sort','is_top','is_new','is_del','max_price', 'min_price','distribute_money','shipping_id', 'stock','is_free'];
        return $scenarios;
        
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['model_id', 'cat_id', 'type_id', 'brand_id', 'down_time', 'create_at', 'update_at', 'visit', 'favorite', 'sortnum', 'comments','prom_id','prom_type', 'sale', 'shop_id','sort','is_top','is_new','is_del','score'], 'integer'],
            [['max_price', 'min_price','plus_price', 'stock','distribute_money'], 'number'],
        	['cat_id', 'exist', 'targetClass' => ProductCategory::className(), 'targetAttribute' => 'category_id','skipOnEmpty' => false, 'skipOnError' => false],
        	['type_id', 'exist', 'targetClass' => ProductType::className(), 'targetAttribute' => 'type_id'],
        	['model_id', 'exist', 'targetClass' => CategoryModel::className(), 'targetAttribute' => 'model_id'],        		
        	['shipping_id', 'exist', 'targetClass' => Shipping::className(), 'targetAttribute' => 'shipping_id'],
        	[['name'],'required','message'=>'商品名称必填'],
            [['name','date','addr'], 'string', 'max' => 100],
        	['image','string'],	
            [['unit'], 'string', 'max' => 10],
            ['status', 'in', 'range' => [self::STATUS_DELETE, self::STATUS_ACTIVE,self::STATUS_DOWN,self::STATUS_REPLY,self::STATUS_REFUSE]],
        	[['content'], 'string'],
        	[['status','type_id','status','shipping_id'], 'default', 'value' => 1], 
        	[['markdown','prom_type','is_index_show','is_new','is_top'], 'default', 'value' => 0],
        	[['markdown','is_free','hot','is_index_show','is_new','is_top','is_del'], 'in', 'range' => [0,1]],
        	[['prom_type'], 'in', 'range' => [0,1]],
        	['self_lift', 'default', 'value' => self::getDefaultSettings('self_lift')],
        	['express', 'default', 'value' => self::getDefaultSettings('express')],
        	['city_wide', 'default', 'value' => self::getDefaultSettings('city_wide')],
        	['up_time', 'default', 'value' => function(){
        		return date('Y-m-d H:i:s', time());
        	}],
        	['up_time', 'filter', 'filter' => function($value) {
        		return is_numeric($value) ? $value : strtotime($value);
        	}, 'skipOnEmpty' => true],
        	['distribute_money', 'default', 'value' =>0 ],
        	['distribute_money','compare','compareValue'=>0,'operator'=>'>='],
        	['distribute_money', 'compare', 'compareValue' => 50, 'operator' => '<='],
        	[['max_price', 'min_price','plus_price'], 'match', 'pattern' => '/^[0-9]+(.[0-9]{1,2})?$/','message'=>'支付金额最小单位为分'],
        	[['map'], 'string', 'max' => 50],
        	
        ];
    }

    
    public function fields()
    {
    	return ArrayHelper::merge(parent::fields(), [
    			'prom_status',
    			'proming_status',
    			'prom_price',
    			'prom_sku_id',
    			'proms' => function ($model) {
    				return $model->proms;
    			},

    			'start_time'=>function($model){
    			return empty($model->date)?0:strtotime(explode(' - ', $model->date)[0]);
    			},
    			'end_time'=>function($model){
    			 
    			return empty($model->date)?0:strtotime(explode(' - ', $model->date)[1]);
    			},
    			'shop' => function ($model) {
    			return ['address'=>$model->shop->address,'lng'=>$model->shop->lng,'lat'=>$model->shop->lat,'logo'=>$model->shop->logo,'name'=>$model->shop->name,'id'=>$model->shop->id];
    			}
    	]);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

            'product_id' => 'ID',
            'name' => '商品名称',
            'model_id' => '模型',
            'cat_id' => '店铺分类',        		
        	'category' => '分类',
            'type_id' =>'类目',
            'brand_id' => '品牌',
            'up_time' => '',
            'down_time' => '下架时间',
            'create_at' => '添加时间',
            'update_at' => '最后编辑时间',
            'image' => '',
            'unit' => '单位',
            'status' => '状态',
            'visit' => '浏览次数',
            'favorite' =>'收藏次数',
            'sortnum' => '排序',
            'comments' =>'评论次数',
            'sale' => '销量',
            'shop_id' => '商家',
            'max_price' => '最高价格',
            'min_price' =>'最低价格',
            'stock' => '总库存',
        	'hot'=>'热销',
            'is_new'=>'新品',
            'is_top'=>'推荐',
        	'shipping_id'=>'运费模板',
            'first'=>'第一书记',
            'distribute_money'=>'分销金额',
            'is_index_show'=>'首页展示',
             'is_del'=>'删除状态',
            'date'=>'活动时间',
            'score'=>'学分',
            'map'=>'经纬度',
            'addr'=>'活动地址',
            'plus_price'=>'站点价格',
        ];
    }

    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
    	$behaviors = [
    			[
    			'class' => TimestampBehavior::className(),
    			'createdAtAttribute' => 'create_at',

    			'updatedAtAttribute' => 'update_at'
    			],
    			PromotionBehavior::className(),
    	      // 'checkShopBehavior'=>CheckShopBehavior::className(),
    	        [
    	                'class' => SoftDeleteBehavior::className(),
    	                'softDeleteAttributeValues' => [
    	                        'is_del' => 1
    	                ],
    	                'restoreAttributeValues' => [
    	                        'is_del' => 0
    	                ],
    	                'invokeDeleteEvents' => false // 不触发删除相关事件
    	        ]
    	];
    	return $behaviors;

    }
    public function setCategory($attribute, $params)
    {
    	$this->category = ProductCategory::find()->where(['category_id' => $this->$attribute])->select('cat_name')->scalar();
    }

    public function attributeHints()
    {
        return [
            'sort' => '数字越小越排在前面',
        ];
    }

    
    public function  getCategory(){
    	return ProductCategory::find()->select('cat_name')->where(['category_id' => $this->cat_id])->scalar();
    }
    

    
    public static function getStatusList()
    {
    	return [
    			//self::STATUS_DELETE => '删除',
    			self::STATUS_ACTIVE => '正常',
    			self::STATUS_DOWN =>   '下架',
    			self::STATUS_REPLY =>  '申请审核',
    			self::STATUS_REFUSE => '审核失败',
    	];
    }
    /**
     * 根据系统设置值获取默认配置
     * @param $key 
     * @return 
     */
    public static function getDefaultSettings($key){
    	//TODO:$settings应该由用户配置，接口设置
    	$settings = array(
    		'self_lift'=>0,
    		'express'=>1,
    		'city_wide'=>0
    			
    	);
    	return $settings[$key];
    }
    
	//获取该商品的sku信息
    public function getSkus(){        
        return $this->hasMany(Skus::className(), ['product_id' => 'product_id']);
    }

    
    public function getProductType(){
        return $this->hasOne(ProductType::className(), ['type_id'=>'type_id']);
    }

    public function beforeValidate() {
    	//处理图片信息
      
    	$images = $this->image;
    
    	if(is_array($images)) { 
    	    $images=array_filter($images);
    	    if(empty($images)){
    	        $this->addError("image",'必须上传一个商品图片');
    	        return false;
    	    }

    		ArrayHelper::multisort($images,'order');	
    		$this->image = json_encode($images);    		
    	}
    	
    	
    	return parent::beforeValidate(); 

    }
	//显示状态
    public function renderStatus(){
    	$statusList = $this->getStatusList();
    	return $statusList[$this->status];
    }
    //显示热销
    public function renderHot(){
    	$statusList = array(1=>'是',0=>'否');
    	return $statusList[$this->hot];
    }

    public function afterFind(){
    	//处理图片信息
    	if (strlen($this->image)>0){
    		$images =  $this->image;
    		$this->image = json_decode($images,true);
    	}
    	if (count($this->image)<=0) {
    	    $this->image = '[{"url":"'.Yii::$app->params['defaultImg']['default'].'","thumbImg":"'.Yii::$app->params['defaultImg']['default'].'","order":"0"}]';
    	    $images =  $this->image;
    	    $this->image = json_decode($images,true);
    	}
    	
    	if (strlen($this->content)>0){
    	    $this->content = Util::ImagesAddPrefix($this->content,'img src="/storage/upload');
    	}
        return parent::afterFind();
    }


    public static function find(){
       
        return new ProductQuery(get_called_class());
    }
    
    public function getPercent(){
        $shop = $this->shop;
        $this->setPercent($shop['percent']);
        return $this->_percent;
    }
    public function setPercent($persent){
        $this->_percent = ($persent*100);
        
    }
    public function getFupin(){
        $shop = $this->shop;
        $this->setFupin($shop['is_village']);
        return $this->_fupin;
    }
    public function setFupin($fupin){
        $this->_fupin = $fupin;
        
    }
    //关联店铺，一对一
    public function getShop(){
        return $this->hasOne(Shop::className(), ['id'=>'shop_id']);
    }
}
