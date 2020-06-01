<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com
 * Time:2019年8月29日上午11:24:05
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace frontend\models;

use yii\base\Model;
use Yii;
use common\helpers\Util;
use common\models\SmsLog;


class BindRegisterForm extends Model
{
    public $mobile;
    public $password;
    public $verifyCode;
    private $_user;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // mobile and password are both required
            [['mobile', 'password'], 'required'],
            //检查用户名是否重复
            ['mobile','match','pattern'=>'/^1\d{10}$/','message'=>'请填写正确手机号！'],
            ['password', 'validatePassword'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'password' => '密码',
        ];
    }
    
   
    /**
     *新注册绑定
     *
     * @return Member|null the saved model or null if saving fails
     */
    public function bindRegister($openid)
    {
        if ($this->validate()) {
            $member = Member::find()->where(['mobile'=>$this->mobile])->one();            
            //设置微信号
            $member->wx_openid = $openid;                
            if ($member->save()) {
                return $member;
            }
        }
        
        return false;
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array  $params    the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '帐号密码错误');
            }
        }
    }
    
    /**
     * Finds user by [[mobile]].
     *
     * @return Member|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Member::find()->where(['mobile'=>$this->mobile])->one();
        }
        
        return $this->_user;
    }
}
