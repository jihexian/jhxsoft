<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com
 * Time:2019年8月29日上午11:24:21
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */

namespace frontend\models;

use yii\base\Model;
use Yii;


class LoginForm extends Model
{
    public $mobile;
    public $password;
    public $rememberMe = true;
    //public $verifyCode;
    private $_user;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // mobile and password are both required
            [['mobile', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            //['verifyCode', 'required'],
            //['verifyCode', 'captcha'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'password' => '密码',
            'rememberMe' => '记住我',
            //'verifyCode'=>'验证码'
        ];
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
            if (!$user) {
                $this->addError($attribute, '帐号不存在');
            } elseif(!$user->validatePassword($this->password)){
                $this->addError($attribute, '帐号密码错误');
            }
        }
    }
    
    /**
     * Logs in a user using the provided mobile and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
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
