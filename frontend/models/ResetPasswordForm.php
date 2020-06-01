<?php
/**
 * Created by PhpStorm.
 * Author: yidashi
 * DateTime: 2017/3/8 17:46
 * Description:
 */

namespace frontend\models;

use yii\base\Model;
use Yii;
use common\models\SmsLog;
use common\helpers\Util;


class ResetPasswordForm extends Model
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
            [['mobile', 'password','verifyPassword','verifyCode'], 'required'],
            ['verifyCode', 'checkSms'],
            ['mobile', 'checkExist'],
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
    
    public function checkExist(){
        if (empty($this->getErrors('verifyCode'))) {
            $member = Member::find()->where(['mobile'=>$this->mobile])->one();
            if(empty($member)){
                $this->addError('mobile','该账号不存在，请先注册！');
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
     * Signs user up.
     *
     * @return Member|null the saved model or null if saving fails
     */
    public function resetpassword()
    {
        if ($this->validate()) {
            $member = Member::find()->andWhere(['mobile'=>$this->mobile])->one();
            $member->password =Util::encrypt($this->password) ;
            if ($member->save()) {
                return $member;
            }
        }
        
        return;
    }
}
