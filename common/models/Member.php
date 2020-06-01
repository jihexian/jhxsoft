<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\helpers\Tools;
use common\helpers\Util;
/** 
 * This is the model class for table "{{%member}}". 
 * 
 * @property integer $id
 * @property string $username
 * @property string $mobile
 * @property integer $mobile_validated
 * @property string $password
 * @property string $auth_key
 * @property string $xcx_openid
 * @property string $wx_openid
 * @property string $avatar
 * @property string $avatarUrl
 * @property string $email
 * @property integer $email_validated
 * @property string $sex
 * @property integer $age
 * @property string $province
 * @property string $city
 * @property integer $score
 * @property integer $level
 * @property integer $status
 * @property integer $register_time
 * @property integer $last_login
 * @property string $access_token
 * @property integer $expire_in
 * @property string $oauth_id
 * @property integer $flag
 * @property string $pay_pwd
 * @property string $user_money
 * @property string $frozen_money
 * @property string $distribut_money
 * @property string $underling_number
 * @property string $total_amount
 * @property integer $is_distribut
 * @property string $message_mask
 * @property string $push_id
 * @property integer $is_vip
 * @property string $version
 * @property integer $type
 */ 
class Member extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const SCENARIO_CREATE = 'create';
    public $_name; 
    public static function tableName()
    {
        return '{{%member}}';
    }
    
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();//本行必填，负责没有default场景
        $scenarios[self::SCENARIO_CREATE] =['username','mobile', 'password','sex','age','email','status','level','avatar'];
        $scenarios['register'] =['username','mobile', 'password','sex','age','email','status','level'];
        $scenarios['pwd'] =['password'];
        $scenarios['xcx_create'] =['xcx_openid','username'];
        $scenarios['wx_openid'] =['wx_openid','username'];
        $scenarios['resetPassword'] =['password'];
        $scenarios['update'] =['username','mobile','sex','age','email','avatar','access_token'];
        return $scenarios;
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'register_time',
                'updatedAtAttribute' => 'last_login',
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','password','mobile','email','pay_pwd'],'trim'],
            
            ['username', 'string', 'max' => 75,'message'=>"名字长度在25个汉字以内"],
            ['password','string','min'=>6,'max'=>16,'message'=>'密码长度6-16位','on'=>['register','pwd','create']],
            ['password', 'filter', 'filter' => function($value) {
                // 在此处标准化输入的email                
                return Util::encrypt($value);
            },'on'=>['register','pwd','create']],             
            ['mobile','match','pattern'=>'/^1[0-9]{10}$/','message'=>'手机号码必须为1开头的11位纯数字'],
            ['mobile', 'unique','message'=>'手机号码已经被占用了'],
            ['sex', 'string','min'=>1,'max'=>6,'message'=>'性别为男/女/未知'],
            ['age', 'integer'],
            [['avatarUrl','avatar'], 'string','max'=>200,'message'=>'头像地址最大长度200'],
            ['email','email', 'message' => '请填写有效的邮箱地址'],
            ['xcx_openid','required', 'message' => '小程序openid不能为空','on'=>"xcx_create"],
            ['wx_openid','required', 'message' => '公众号openid不能为空','on'=>"wx_create"],
            ['access_token', 'string','max'=>128,'message'=>'最大长度128'],
            ['oauth_id', 'string','max'=>128,'message'=>'最大长度128'],
            [['pay_pwd'], 'string', 'max' => 250],
            [['name'], 'string', 'max' => 15],
            [['age', 'score', 'user_money', 'frozen_money', 'distribut_money', 'total_amount','type'], 'number'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            
            'id' => Yii::t('common', 'ID'),
            'username' => Yii::t('common', 'Username'),
            'mobile' => '手机号',
            'mobile_validated' => Yii::t('common', 'Mobile Validated'),
            'password' => '密码',
            'auth_key' => Yii::t('common', 'Auth Key'),
            'xcx_openid' => Yii::t('common', 'Xcx Openid'),
            'wx_openid' => Yii::t('common', 'Wx Openid'),
            'avatar' => Yii::t('common', 'Avatar'),
            'avatarUrl' => '头像',
            'email' => Yii::t('common', 'Email'),
            'email_validated' => Yii::t('common', 'Email Validated'),
            'sex' => '性别',
            'age' => '年龄',
            'province' => Yii::t('common', 'Province'),
            'city' => Yii::t('common', 'City'),
            'score' => '积分',
            'level' => '会员等级',
            'status' => Yii::t('common', 'Status'),
            'register_time' => '注册时间',
            'last_login' => '登录时间',
            'access_token' => Yii::t('common', 'Access Token'),
            'expire_in' => Yii::t('common', 'Expire In'),
            'oauth_id' => Yii::t('common', 'Oauth ID'),
            'flag' => Yii::t('common', 'Flag'),
            'pay_pwd' => Yii::t('common', 'Pay Pwd'),
            'user_money' => '余额',
            'frozen_money' => Yii::t('common', 'Frozen Money'),
            'distribut_money' => Yii::t('common', 'Distribut Money'),
            'underling_number' => Yii::t('common', 'Underling Number'),
            'total_amount' => Yii::t('common', 'Total Amount'),
            'is_distribut' => Yii::t('common', '是否为分销商'), 
            'message_mask' => Yii::t('common', 'Message Mask'),
            'push_id' => Yii::t('common', 'Push ID'),
            'is_vip' => Yii::t('common', 'Is Vip'),
             'type'=>'会员级别',
            'name'=>'姓名'
        ];
    }
    
	public function fields()
	{
		$fields = parent::fields();
    	// 去掉一些包含敏感信息的字段
		if(yii::$app->user->id!=$this->id){
		unset($fields['access_token'],$fields['password'], $fields['xcx_openid'],$fields['auth_key'],$fields['wx_openid'],$fields['pay_pwd'],$fields['mobile'],$fields['user_money'],$fields['distribut_money'],$fields['total_amount'],$fields['frozen_money']);
		}
		
    	return $fields;
	}
    public function getMemberLevel()
    {
        // hasOne要求返回两个参数 第一个参数是关联表的类名 第二个参数是两张表的关联关系 
        //memberlevel表的id对应user表的level
        return $this->hasOne(MemberLevel::className(), ['id' => 'level']);
    }

  

    /*将数字状态转成文字*/
    public function status2str($status){
        $str="";
        switch ($status)
        {
            case 1:
              $str="正常";
              break;
            case 0:
              $str="禁用";
              break;
            default:
              $str="未知状态";
        }
        return $str;
    }
	/*通过openid获取会员信息
	$openid 通过接口获取到的oepnid
	$type openid类型，wx是微信openid,xcx是小程序openid
	*/
	public function getMemberByopenid($openid,$type){
		$member=(new \yii\db\Query())->from(self::tableName())->where([$type."_openid" => $openid])->one();
        return $member;
	}
	/*通过ID获取会员信息*/
	public function getMemberByid($id){
		$member=(new \yii\db\Query())->from(self::tableName())->where(['id'=> $id])->one();
        return $member;
	}


    public function getAccessToken(){
        return $this->hasOne(AccessToken::className(), ['uid' => 'id']);
    }

    public function getProductComment(){
        return $this->hasMany(ProductComment::className(),['member_id'=>'id']);
    }
    

    //显示会员类型
    public function renderType(){
        $statusList = [
                '1'=>'个人',
                '2' => '机关单位',
                '3' => '站长',
        ];
        return $statusList[$this->type];
    }

    public function attributes ()
    {
        $attributes = parent::attributes();
        $attributes[] = 'total';
        return $attributes;
    }

    
    public function afterFind(){
        if (strlen($this->username)>0){
            $this->username = Tools::emoji_decode($this->username);
        }
        return parent::afterFind();
    }
    
    
    private  function hideStar($str) { //用户名、邮箱、手机账号中间字符串以*隐藏
        if (strpos($str, '@')) {
            $email_array = explode("@", $str);
            $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀
            $count = 0;
            $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
            $rs = $prevfix . $str;
        } else {
            $pattern = '/(1[34589]{1}[0-9])[0-9]{4}([0-9]{4})/i';
            if (preg_match($pattern, $str)) {
                $rs = preg_replace($pattern, '$1****$2', $str); // substr_replace($name,'****',3,4);
            } else {
                $rs = $str;
            }
            
        }
        return $rs;
    }
    
    
    public function setName($name){
        $this->_name = $name;
    }
    
    public function getName(){
        if($this->username){
            $this->setName($this->hideStar($this->username));
        }
        return $this->_name;
    }

}
