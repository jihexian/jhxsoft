<?php

namespace common\models;
use common\modules\attachment\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%shop_apply}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $idcard
 * @property string $license
 * @property integer $status
 * @property integer $type
 * @property string $address
 * @property integer $category_id
 */
class ShopApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_apply}}';
    }
    
    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
            'license' => function ($model) {
            $thumb=ArrayHelper::getValue($model, 'cover.thumbImg', '');
            return empty($thumb)? Yii::$app->params['defaultImg']['default']:$thumb;  //返回缩略图
            },
            'licenseImage'=> function ($model) {
            $origin=ArrayHelper::getValue($model, 'cover.url', '');
            return empty($origin)? Yii::$app->params['defaultImg']['default']: $origin;  //返回缩略图
            },
            
            'idcard' => function ($model) {
            $thumb=ArrayHelper::getValue($model, 'idcard.thumbImg', '');
            return empty($thumb)? Yii::$app->params['defaultImg']['default']:$thumb;  //返回缩略图
            },
            'idcardImage'=> function ($model) {
            $origin=ArrayHelper::getValue($model, 'idcard.url', '');
            return empty($origin)? Yii::$app->params['defaultImg']['default']: $origin;  //返回缩略图
            },
            ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id','type','category_id'], 'integer'],
        	[['name'], 'unique', 'message' => '店铺名已存在，请重新输入'],  
            [['password_hash'], 'required'],
            [['name', 'license', 'password_hash'], 'string', 'max' => 255],
        	[['address'], 'string', 'max' => 500],
            [['status'], 'in', 'range' => [0,1,2]],
            [['type'],'compare', 'compareValue' => 1, 'operator' => '>'],
            [['mobile'], 'match', 'pattern' => '/^1[0-9]{10}$/','message'=>'手机号码格式不正确！'],
            ['mobile', 'checkUnique'],
            [['license','idcard'],'safe'],   
            [['name','address','mobile'],'required'],     
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户ID',
            'name' => '店铺名称',
            'created_at' => '申请时间',
            'updated_at' => 'Updated At',
            'idcard' => '身份证',
            'license' => '营业执照',
            'status' => '状态',
        	'address' => '地址',
            'type' => '类型',
            'category_id'=>'行业id',
            'mobile'=>'电话',
            'password_hash'=>'密码'
       
        ];
    }
    public function getMember(){
    	return $this->hasOne(Member::className(), ['id'=>'member_id']);
    }
    
    public function checkSms(){
        
        $sms = SmsLog::find()->where(['mobile'=>$this->mobile,'scene'=>1,'code'=>$this->verifyCode])->one();
        if (empty($sms)) {
            $this->addError('verifyCode','验证码错误，请重新输入！');
        }else{
            if ($sms->created_at>time()+60*10*1000) {
                $this->addError('verifyCode','验证码过期，请重新获取！');
            }
        }
    }
    
    public function checkUnique(){
     if (empty($this->getErrors('verifyCode'))) {
         $member = Member::find()->where(['and',['mobile'=>$this->mobile],['<>','id',$this->member_id]])->one();
     if(!empty($member)){
     $this->addError('mobile','手机号已被使用！');
     }
     }
     } 
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [
            TimestampBehavior::className(),
            [
                'class' => UploadBehavior::className(),
                'attribute' => 'cover',
                'entity' => __CLASS__
            ],
            [
                'class' => UploadBehavior::className(),
                'attribute' => 'idcard',
                'entity' => __CLASS__
            ],
           
            ];
      
        return $behaviors;
    }
}
