<?php

namespace common\models;

use Yii;
use yii\caching\TagDependency;
use common\behaviors\PositionBehavior;
use common\behaviors\CacheInvalidateBehavior;
use common\models\Product;
use common\behaviors\SoftDeleteBehavior;
use common\behaviors\CheckShopBehavior;

/**
 * This is the model class for table "{{%product_category}}".
 *
 * @property integer $category_id
 * @property integer $shop_id
 * @property string $cat_name
 * @property integer $parent_id
 * @property string $image
 * @property integer $status
 * @property integer $is_system
 */
class ProductCategory extends \yii\db\ActiveRecord
{
	const STATUS_DRAFT = 0;
	const STATUS_ACTIVE = 1;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id','sort','parent_id'], 'integer'],
        	['parent_id', 'default', 'value' => 0],
        	[['sort'], 'default', 'value' => 0],
            [['cat_name'], 'string', 'max' => 50],
        	[['cat_name'],'required','message'=>'分类名称必填'],
        	//[['cat_name'], 'unique', 'message' =>'分类名称已存在'],有可能子分类名称会重复
            [['image'], 'string', 'max' => 255],
            [['status'], 'integer'],
        	['status', 'default', 'value' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
       
        return [
        		'category_id' => 'ID',
        		'cat_name' => '分类名称',
        		'parent_id' => '上级分类',
        		'ptitle' => '上级分类', // 非表字段,方便后台显示
        		'image' => '分类图片',
        		'sort' => '排序',        		
                'status' => '显示',
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
    	return [    	
    	        CheckShopBehavior::className(),
    			'positionBehavior' => [
    					'class' => PositionBehavior::className(),
    					'positionAttribute' => 'sort',
    					'groupAttributes' => [
    							'parent_id',
    					        'shop_id'
    					],
    			],
//     			[
//     			'class' => SoftDeleteBehavior::className(),
//     			'softDeleteAttributeValues' => [
//     					'status' => 0
//     			],
//     			'restoreAttributeValues' => [
//     					'status' => 1
//     			],
//     			],
    			[
    					'class' => CacheInvalidateBehavior::className(),
    					'tags' => [
    							'productCategoryList'
    					]
    
    			],
    	       
    	];
    }
    
    /**
     * 获取所有子分类.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSons()
    {
    	return $this->hasMany(self::className(), ['parent_id' => 'category_id'])->where(['status' => 1]);
    }
    
    public static function getDropDownList($tree = [], &$result = [], $deep = 0, $separator = '--')
    {
    	$deep++;
    	foreach($tree as $list) {
    		$result[$list['category_id']] = str_repeat($separator, $deep-1) . $list['cat_name'];
    		if (isset($list['children'])) {
    			self::getDropDownList($list['children'], $result, $deep);
    		}
    	}
    	return $result;
    }
    public static function lists($module = null,$shopId=null)
    {
    	//$list = Yii::$app->cache->get(['productCategoryList', $module]);
    	$list = false;
    	if ($list === false) {
    		$query = static::find()->where(['status'=>1]);
    		if ($shopId!=null){
    			$query->andFilterWhere(['shop_id'=>$shopId]);
    		}
    	
    		$list = $query->orderBy(['sort' => SORT_ASC])->asArray()->all();
    		//Yii::$app->cache->set(['productCategoryList', $module], $list, 0, new TagDependency(['tags' => ['productCategoryList']]));
    	}
    	return $list;
    }
    
    /**
     * 获取分类名
     */
    public function getPtitle()
    {
    	return static::find()->select('cat_name')->where(['category_id' => $this->parent_id])->scalar();
    }
    
    public function beforeDelete(){
    	if($this->is_system==1){    		
    		$this->addErrors(['category_id'=>'系统内置分类不可删除！']);
    		return false;    		
    	}
    	$subCategoryCount = $this->find()->where(['parent_id'=>$this->category_id])->count();    
    	if($subCategoryCount>0){
    		$this->addErrors(['category_id'=>'该分类下有子分类，请先删除子分类！']);
    		return false;
    	}    
			
    	$productCount = Product::find()->where(['cat_id'=>$this->category_id])->andWhere(['<>','status',0])->count();
    	if($productCount>0){
    		$this->addErrors(['category_id'=>'请先移除该分类下的所有商品，包括已删除的商品！']);
    		return false;
    	}
    	return parent::beforeDelete();
    }
    
    /*通过父级ID获取全部的子ID*/
    public function getChilds($pid){
    	$childs=(new \yii\db\Query())->select("category_id")->from(self::tableName())->where(['parent_id'=> $pid,'status'=>1])->all();
    	return $childs;
    }
}
