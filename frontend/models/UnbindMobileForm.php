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


class UnbindMobileForm extends Model
{
    public $mobile;
    //public $password;
    //public $verifyPassword;
    public $verifyCode;
    private $_user;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // mobile and verifyCode are both required
            [['mobile','verifyCode'], 'required'],
            ['verifyCode', 'checkSms'],
            //检查用户名是否重复
            ['mobile','match','pattern'=>'/^1\d{10}$/','message'=>'请填写正确手机号！'],
            
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

    
    public function unbindMobile($mid){
        if ($this->validate()) {
            $member = Member::findOne($mid);
            $member->mobile = null;
            if ($member->save()) {
                return $member;
            }
        }
        
        return;
    }
}
