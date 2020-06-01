<?php

namespace common\models;


use common\modules\user\models\User;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use common\behaviors\CheckShopBehavior;
use common\models\query\ProductCommentQuery;


/**
 * This is the model class for table "{{%product_comment}}".
 *
 * @property integer $comment_id
 * @property integer $goods_id
 * @property integer $member_id
 * @property string $content
 * @property integer $pid
 * @property integer $reply_member_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $image
 * @property integer $order_sku_id
 * @property string $order_no
 * @property integer $total_stars
 * @property integer $des_stars
 * @property integer $delivery_stars
 * @property integer $service_stars
 * @property integer $appraise
 * @property integer $reply_status
 * @property integer $user_id
 * @property integer $is_nickname
 * @property integer $shop_id
 */
class ProductComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_comment}}';
    }

    
    

    /**
     * {@inheritdoc}
     */
    public function behaviors(){
    	$behaviors = [
    			TimestampBehavior::className(),
    			CheckShopBehavior::className(),
    	];
    
    	return $behaviors;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['member_id', 'pid', 'reply_member_id', 'order_sku_id', 'total_stars', 'des_stars', 'delivery_stars', 'service_stars', 'user_id','goods_id','shop_id'], 'integer'],
            [['content', 'image'], 'string'],
            ['goods_id','required','message'=>'不能为空！'],
            //['shop_id','required','message'=>'不能为空！'],
            //['member_id','required','message'=>'用户id不能为空！'],
                ['total_stars','default', 'value' => 5],
                ['des_stars','default', 'value' => 5],
                ['delivery_stars','default', 'value' => 5],
                ['service_stars','default', 'value' => 5],
            ['total_stars','required','message'=>'不能为空！'],
            ['des_stars','required','message'=>'不能为空！'],
            ['delivery_stars','required','message'=>'不能为空！'],
            ['service_stars','required','message'=>'不能为空！'],
            [['status'], 'in', 'range' => [0,1]],
            [['order_no'], 'string', 'max' => 32],
            [['appraise'], 'in', 'range' => [1,2,3]],
        		['goods_id', 'exist', 'targetClass' => Product::className(), 'targetAttribute' => 'product_id'],
                //['shop_id', 'exist', 'targetClass' => Shop::className(), 'targetAttribute' => 'id'],
            	[['member_id','reply_member_id'], 'exist', 'targetClass' => Member::className(), 'targetAttribute' => 'id'],
                //['pid', 'exist', 'targetClass' => ProductComment::className(), 'targetAttribute' => 'comment_id','skipOnEmpty' => true,],
                [['total_stars', 'des_stars', 'delivery_stars', 'service_stars'], 'in', 'range' => [0,1,2,3,4,5]],
                [['reply_status','is_nickname'], 'in', 'range' => [0,1]],
               //[['user_id'], 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'id'],
                [['order_sku_id'], 'exist', 'targetClass' => OrderSku::className(), 'targetAttribute' => 'id'],
        		[['total_stars','des_stars','delivery_stars','service_stars'], 'default', 'value' =>5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comment_id' => 'ID',
            'content' => '评价内容',
        	'member_id'=>'用户ID',
        	'created_at'=>'评价时间',
        	'order_sku_id'=>'商品ID',
            'status' => '是否显示',
        	'reply_status' => '是否回复',
            'total_stars' => '总评',
            'des_stars' => '描述评分',
            'delivery_stars' => '物流评分',
            'service_stars' => '服务评分',
        	'order_no'=>'订单编号',
        	'appraise'=>'评价值',
        	'image'=>'图片',
        	'replys'=>'评价回复'
        ];
    }

    public function getOrderSku(){
        return $this->hasOne(OrderSku::className(), ['id' => 'order_sku_id']);
    }

    public function getMember(){
        return $this->hasOne(Member::className(),['id'=>'member_id']);
    }
    public function getUser(){
        if ($this->shop_id==1) {
            return $this->hasOne(User::className(),['id'=>'user_id']);
        }else{
            return $this->hasOne(Member::className(),['id'=>'user_id']);
        }
    	
    }
    public function getProduct(){
    	return $this->hasOne(Product::className(),['product_id'=>'goods_id']);
    }


     //获取回复列表

    public function getReplys(){
    	return $this->hasMany(self::className(), ['pid' => 'comment_id'])->from(self::tableName() . ' replys');
//     	$list = static::findAll(['pid'=>$this->comment_id]);
//     	return $list;
    }
    

    public function getParent()
    {
    	return $this->hasOne(self::className(), ['comment_id' => 'pid']);
    }


    public function renderStatus(){
    	$statusList = $this->getStatusList();
    	return $statusList[$this->status];
    }
    
    public static function getStatusList()
    {
    	return [
    			0 => '隐藏',
    			1 => '显示',    			
    	];
    }
    public function renderAppraise(){
    	$statusList = $this->getAppraiseList();
    	return $statusList[$this->appraise];
    }
    
    public static function getAppraiseList()
    {
    	return [
    			1 => '差评',
    			2 => '中评',
    			3 => '好评',
    	];
    }
    public function beforeValidate() {
    	//处理图片信息
    	$images = $this->image;
    	if(is_array($images)) {
    		$images = array_filter($images);
    		ArrayHelper::multisort($images,'order');
    		$this->image = json_encode($images);
    	}    
    	return parent::beforeValidate();    
    }
    public function afterFind(){
    	//处理图片信息
    	if (strlen($this->image)>0){
    		$images =  $this->image;
    		$this->image = json_decode($images,true);
    	}
    	return parent::afterFind();
    }
    public function renderReplyStatus(){
    	$replyList = $this->getReplyStatusList();
    	return $replyList[$this->reply_status];
    }
    
    public static function getReplyStatusList()
    {
    	return [
    			0 => '未回复',
    			1 => '已回复',
    	];
    }
    public static function find(){
        return new ProductCommentQuery(get_called_class());
    }

}
