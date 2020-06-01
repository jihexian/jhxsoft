<?php
/**
 * 用户申请店铺
 * Author wsyone wsyone@faxmail.com
 * Time:2019年9月11日下午3:18:17
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace api\modules\v1\models;
use common\helpers\Util;
use common\models\Shop;
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
    public $member_id;
    public $verifyCode;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'string', 'min' => 2, 'max' => 255],
            ['name', 'unique', 'targetClass' => '\common\models\shop', 'message' => '店铺名称已存在'],
            ['mobile', 'filter', 'filter' => 'trim'],
            [['mobile'], 'match', 'pattern' => '/^1[0-9]{10}$/'],
            ['mobile', 'unique', 'targetClass' => '\common\models\shop', 'message' => '手机号码已存在'],
            ['password', 'filter', 'filter' => 'trim'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['category_id','member_id'],'integer'],
            [['name','address','mobile','license','category_id','member_id','verifyCode'],'required'],
            ['verifyCode','checkSms'],
     
            [['address','license',],'string','max'=>255],
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
        ];
    }
    /**
     * Signs shop.
     *
     */
    public function signup()
    {
        if ($this->validate()) {
            $shop = new Shop();
            $shop->name = $this->name;
            $shop->mobile=$this->mobile;
            $shop->address = $this->address;
            $shop->category_id=$this->category_id;
            $shop->license=$this->license;
            $shop->member_id=$this->member_id;
            $shop->password_hash=Yii::$app->security->generatePasswordHash($this->password);
            if ($shop->save()) {
                return $shop;  
            }
            //     yii::error($shop->errors);
        }
        return;
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
