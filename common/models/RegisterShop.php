<?php
/**
 * 用户申请店铺
 * Author wsyone wsyone@faxmail.com
 * Time:2019年9月11日下午3:18:17
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\models;
use common\helpers\Util;
use common\models\Shop;
use common\models\ShopUser;
use yii\base\Model;
use yii;
/**
 * Signup form.
 */
class RegisterShop extends Model
{
    public $name;
    public $address;
    public $category_id;
    public $mobile;
    public $password;
    public $license;
    public $m_id;
    public $verifyCode;
    public $lat;
    public $lng;
    public $map;
    public $business_hours;
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['category_id', 'name','address','mobile','password','license','m_id','verifyCode','map','lat','lng','business_hours'];
        $scenarios['edit']  = ['category_id', 'address','password','license','m_id','verifyCode','map','lat','lng','business_hours'];
        return $scenarios;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'string', 'min' => 2, 'max' => 255],
            ['name', 'unique', 'targetClass' => '\common\models\Shop', 'message' => '店铺名称已存在','on'=>['create']],
            ['mobile', 'filter', 'filter' => 'trim'],
            [['mobile'], 'match', 'pattern' => '/^1[0-9]{10}$/'],
            ['mobile', 'unique', 'targetClass' => '\common\models\ShopUser', 'message' => '手机号码已存在','on'=>['create']],
            ['password', 'filter', 'filter' => 'trim'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['category_id','m_id'],'integer'],
            ['category_id', 'exist', 'targetClass' =>ShopCategory::className(), 'targetAttribute' => 'id'],
            [['name','address','mobile','license','category_id','verifyCode'],'required'],
            //['verifyCode','checkSms'],
            [['address','license',],'string','max'=>255],
            [['lng','lat'], 'number'],
            [['business_hours','map'], 'string', 'max' => 100],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'name' => '店铺名称',
            'address' => '店铺地址',
            'category_id' => '行业id',
            'mobile' => '手机号码',
            'password' => '密码',
            'license' => '营业执照',
            'lat'=>'经度',
            'lng'=>'纬度',
            'business_hours'=>'营业时间',
            'm_id'=>'用户'
       ];
    }
    /**
     * Signs shop.
     *
     */
    public function signup()
    {
        if ($this->validate()) {
          $truncateion=Yii::$app->db->beginTransaction();
            try {
                $shop = new Shop();
                $shop->setScenario('create');
                $shop->name = $this->name;
                $shop->address = $this->address;
                $shop->category_id=$this->category_id;
                $shop->license=$this->license;
                $shop->lat=$this->lat;
                $shop->lng=$this->lng;
                $shop->map=$this->map;
                $shop->business_hours=$this->business_hours;
                $shop->save();
                if($shop->hasErrors()){
                    $truncateion->rollBack();
                    return false;
                }
                $user=new ShopUser();
                $user->setScenario('create');
                $user->shop_id=$shop->id;
                $user->mobile=$this->mobile;
                $user->m_id=$this->m_id;
                $user->password=$this->password;
                $user->create();  
                if($user->hasErrors()){
                    $truncateion->rollBack();
                    return false;
                }
                $truncateion->commit();
                return true;
            } catch (\Exception $e) {
                return false;
            }  
        }
    }
    
    
    /**
     * Signs shop.
     *
     */
    public function edit($shop_id,$user_id)
    {
        if ($this->validate()) {
            $truncateion=Yii::$app->db->beginTransaction();
            try {
                $shop=Shop::findOne(['id'=>$shop_id]);
                $shop->setScenario('create');
           //     $shop->name = $this->name;
                $shop->address = $this->address;
                $shop->category_id=$this->category_id;
                $shop->license=$this->license;
                $shop->lat=$this->lat;
                $shop->lng=$this->lng;
                $shop->map=$this->map;
                $shop->business_hours=$this->business_hours;
                $shop->status=0;
                $shop->save();
                if($shop->hasErrors()){
                    $truncateion->rollBack();
                    return false;
                }
                $user=ShopUser::findOne(['id'=>$user_id]);
                $user->setScenario('update');
                //$user->username=$this->mobile;
                $user->shop_id=$shop->id;
            //    $user->mobile=$this->mobile;
                $user->m_id=$this->m_id;
                $user->email=$this->mobile.'@mobile.com';
                $user->password_hash=Yii::$app->security->generatePasswordHash($this->password);
                $user->generateAuthKey();
                $user->save();
                if($user->hasErrors()){
                    $truncateion->rollBack();
                    return false;
                }
                $truncateion->commit();
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
    }
    
    /**
     *检查手机验证码
     */
    public function checkSms(){  
        $result = Util::checkSms($this->mobile, 1, $this->verifyCode);
        if ($result['status']!=1) {
            $this->addError('verifyCode',$result['msg']);
        }
    }
    /**
     * 
     */
    public function checkMobile(){
        $count=Shop::find()->where(['mobile'=>$this->mobile])->count();
        if ($count>0) {
            $this->addError('verifyCode','该手机号已经被使用了');
        }
    }
  

}
