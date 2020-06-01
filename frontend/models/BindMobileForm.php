<?php
/**
 * Author wsyone wsyone@faxmail.com
 * Time:2019年8月29日上午11:23:53
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace frontend\models;

use yii\base\Model;
use Yii;
use common\models\SmsLog;
use common\helpers\Util;


class BindMobileForm extends Model
{
    public $mobile;
    public $password;
    public $verifyPassword;
    public $verifyCode;
    private $_user;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // mobile and password are both required
            [['mobile','verifyCode'], 'required'],
            //[['mobile', 'password','verifyPassword','verifyCode'], 'required'],
            ['verifyCode', 'checkSms'],
            ['mobile', 'checkUnique'],
            //检查用户名是否重复
            ['mobile','match','pattern'=>'/^1\d{10}$/','message'=>'请填写正确手机号！'],
            ['password', 'string', 'length' => [6, 22],'message'=>'密码请输入长度为6-22位字符'],
            ['verifyPassword', 'compare', 'compareAttribute'=>'password', 'message'=>'确认密码不一致!'],
            
        ];
    }
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'password' => '密码',
            'verifyPassword'=>'确认密码',
            'verifyCode' => '验证码',
        ];
    }
    
    public function checkUnique(){
        if (empty($this->getErrors('verifyCode'))) {
            $member = Member::find()->where(['mobile'=>$this->mobile])->one();
            if(!empty($member)){
                $this->addError('mobile','手机号已被注册！');
            }
        }
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
    /**
     * 
     *
     * @return Member|null the saved model or null if saving fails
     */
    public function bindMobile($mid)
    {
        if ($this->validate()) {
            $member = Member::findOne($mid);
            $member->mobile = $this->mobile;
            //$member->password = Util::encrypt($this->password);
            if ($member->save()) {
                return $member;
            }
        }
        
        return;
    }
    
    public function unbindMobile($mid){
        if ($this->validate()) {
            $member = Member::findOne($mid);
            $member->mobile = null;
            //$member->password = Util::encrypt($this->password);
            if ($member->save()) {
                return $member;
            }
        }
        
        return;
    }
}
