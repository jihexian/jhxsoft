<?php

namespace common\models;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use common\behaviors\SoftDeleteBehavior;
use common\modules\user\models\User;
/**
 * This is the model class for table "{{%service}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $mid
 * @property integer $shop_id
 * @property string $sku_id
 * @property integer $type
 * @property string $company
 * @property string $delivery_no
 * @property string $mark
 * @property integer $created_at
 * @property integer $apply_status
 * @property integer $updated_at
 * @property integer $user_id
 * @property integer $receive_status
 * @property integer $status
 * @property string $amount
 */
class Service extends \yii\db\ActiveRecord
{
    
    public function behaviors(){
        return [
                
                [
                        'class'=>TimestampBehavior::className(),
                      
                ],
                
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
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%service}}';
    }
    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
             
                'orderSku',
               
        ]);
   
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['order_id', 'user_id','mid','shop_id','refund_type','delivery_time','sku_id'], 'integer'],
            [['amount'], 'number'],
            [['delivery_no'], 'string', 'max' => 255],
            [['type', 'apply_status', 'receive_status', 'status'], 'integer', 'max' => 3],
            [['company'], 'string', 'max' => 50],
            [['name'], 'string', 'max' =>20],
            [['mark','message'], 'string', 'max' => 245],
            ['mobile','required','message'=>'电话不能为空！'],
            ['mobile','match','pattern'=>'/^1[0-9]{10}$/','message'=>'手机号码必须为1开头的11位纯数字'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '序号',
            'order_id' => '订单id',
            'sku_id' => 'Sku ID',
            'type' => '类型',
            'company' => '快速公司',
            'delivery_no' => '快速单号',
            'delivery_time'=>'寄送时间',
            'mark' => '用户备注',
            'created_at' => '申请时间',
            'apply_status' => '审核状态',
            'updated_at' => '操作时间',
            'user_id' => '操作管理员',
            'receive_status' => '收货状态',
            'status' => '状态',
            'amount' => '申请退款金额',
            'name'=>'联系人',
            'mobile'=>'电话号码',
            'refund_type'=>'退款方式',
        ];
    }
    public function getOrderSku()
    {
        return $this->hasOne(OrderSku::className(), ['id' => 'sku_id']);
    }

    public function getShop(){
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }
    
    public function getOrder(){
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
    
    public function getMember(){
        return $this->hasOne(Member::className(), ['id'=>'mid']);
    }
    
    public function renderStatus(){
        switch($this->status){
            case 0: $msg='未处理';break;
            case 1:$msg='处理完成';break;
            case 2:$msg='处理中';break;
            default:$msg='未知状态';break;
        }
        return $msg;
    }
    
    public function getType(){
        switch($this->type){
            case 1: $msg='退货';break;
            case 2:$msg='换货';break;
            case 3:$msg='维修';break;
            default:$msg='退货';break;
        }
        return $msg;
    }
    
    
    public function getGoodsName(){
         $data=$this->orderSku;
          
        return $data['goods_name'];
    }

    public function getMemberName(){
        $data=$this->member;
        
        return $data['username'];
    }
    
    
    public function getShippingCompany() {
        return $this->hasOne(ShippingCompany::className(), ['code'=>'company']);
    }
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }
  
    
 
    
}
