<?php

namespace common\models;

use Yii;
use common\behaviors\PositionBehavior;
use common\behaviors\CacheInvalidateBehavior;
use yii\caching\TagDependency;
use common\behaviors\CheckShopBehavior;

/**
 * This is the model class for table "{{%product_type}}".
 *
 * @property integer $type_id
 * @property integer $parent_id
 * @property integer $shop_id
 * @property string $type_name
 * @property string $remark
 * @property integer $is_system
 * @property string $keyword
 * @property string $discription
 * @property string $seo_content
 * @property integer $status
 * @property string $image
 */
class ProductType extends \yii\db\ActiveRecord
{
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'shop_id', 'sort', 'is_system'], 'integer'],
        	['parent_id', 'default', 'value' => 0],
            [['type_name'], 'string', 'max' => 50],
        	[['type_name'],'required','message'=>'分类名称必填'],
        	[['type_name'], 'unique', 'message' =>'分类名称已存在'],
            [['remark'], 'string', 'max' => 200],
        	[['sort'], 'default', 'value' => 0],
            [['keyword', 'discription', 'seo_content'], 'string', 'max' => 255],
        	[['image'], 'string', 'max' => 255],
        	['status', 'default', 'value' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_id' => Yii::t('common', 'ID'),
            'parent_id' => Yii::t('common', '上级分类'),
        	'ptitle' => '上级分类', // 非表字段,方便后台显示
            'shop_id' => Yii::t('common', '店铺id'),
            'type_name' => Yii::t('common', '类目名称'),
            'remark' => Yii::t('common', '备注'),
            'sort' => '排序',
            'is_system' => Yii::t('common', '系统内置'),
            'keyword' => Yii::t('common', 'SEO关键词'),
            'discription' => Yii::t('common', '描述'),
            'seo_content' => Yii::t('common', 'SEO内容'),
        	'status' => '显示',
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
    					'groupAttributes' => [
    							'parent_id'
    					],
    			],
    			[
    					'class' => CacheInvalidateBehavior::className(),
    					'tags' => [
    							'productTypeList'
    					]
    
    			],
    	       CheckShopBehavior::className(),
    	];
    }
    public static function getDropDownList($tree = [], &$result = [], $deep = 0, $separator = '--')
    {
    	$deep++;
    	foreach($tree as $list) {
    		$result[$list['type_id']] = str_repeat($separator, $deep-1) . $list['type_name'];
    		if (isset($list['children'])) {
    			self::getDropDownList($list['children'], $result, $deep);
    		}
    	}
    	return $result;
    }
    public static function lists($module = null)
    {
    	//$list = Yii::$app->cache->get(['productTypeList', $module]);
    	$list = false;
    	if ($list === false) {
    		$query = static::find()->where(['status'=>1]);
    		$list = $query->orderBy(['sort' => SORT_ASC])->asArray()->all();
    		//Yii::$app->cache->set(['productTypeList', $module], $list, 0, new TagDependency(['tags' => ['productTypeList']]));
    	}
    	return $list;
    }
    /**
     * 获取分类名
     */
    public function getPtitle()
    {
    	return static::find()->select('type_name')->where(['type_id' => $this->parent_id])->scalar();
    }
    
    public function getProductAttributes()
    {
    	/**
    	 * 第一个参数为要关联的字表模型类名称，
    	 *第二个参数指定 通过子表的 id 去关联主表的 id 字段
    	 */
    	return $this->hasMany(Attribute::className(), ['type_id' => 'type_id']);
    }
    
    public function beforeDelete(){
    	
//     	if ($this->is_system){
//     		$this->addErrors(['type_id'=>'系统默认类目不可删除！']);
//     		return false;
//     	}
    	$subTypeCount = $this->find()->where(['parent_id'=>$this->type_id])->count();
    	if($subTypeCount>0){
    	    $this->addErrors(['type_id'=>'该类目下有子分类，请先删除子类目！']);
    	    return false;
    	}
    	
    	$productCount = Product::find()->where(['type_id'=>$this->type_id])->count();
    	if($productCount>0){
    	    $this->addErrors(['type_id'=>'请先移除该类目下的所有商品，包括已删除的商品！']);
    	    return false;
    	}
    	return parent::beforeDelete();
    }
    /**
            * 获取所有子类目.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSons()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'type_id']);
    }
   
    /**
     * 获取最顶级分类id
     * @param int $type_id
     * @return int $num
     */
    public static function topId($type_id){
        $query = static::find()->where(['type_id'=>$type_id])->one();
        if(!empty($query)){
            if($query['parent_id']!=0){
                $num=self::topId($query['parent_id']);
            }else{
                $num= $query['type_id'];
            }
        }else{
            $num= $type_id;
        }
     return $num;
    }
  
}
