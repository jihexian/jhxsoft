<?php
/**
 * Author wsyone wsyone@faxmail.com
 * Time:2019年11月14日上午11:16:22
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace api\modules\v1\models;
use common\helpers\Util;
use yii\base\Model;
class ResetPayPasswordForm extends Model
{
    public $mobile;
    public $pay_pwd;
    public $verifyPassword;
    public $verifyCode;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // mobile and password are both required
            [['mobile', 'pay_pwd','verifyPassword','verifyCode'], 'required'],
            ['verifyCode', 'checkSms'],
            ['mobile', 'checkExist'],
            //检查用户名是否重复
            ['mobile','match','pattern'=>'/^1\d{10}$/','message'=>'请填写正确手机号！'],
            ['pay_pwd', 'string', 'length' => [6, 22],'message'=>'密码请输入长度为6-22位字符'],
            ['verifyPassword', 'compare', 'compareAttribute'=>'pay_pwd', 'message'=>'确认密码不一致!'],
            
        ];
    }
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'password' => '支付密码',
            'verifyPassword'=>'确认密码',
            'verifyCode' => '验证码',
        ];
    }
    
    public function checkExist(){
        if (empty($this->getErrors('verifyCode'))) {
            $member = Member::find()->where(['mobile'=>$this->mobile])->one();
            if(empty($member)){
                $this->addError('mobile','请使用绑定手机号进行修改！');
            }
        }
      
    }
    public function checkSms(){
        
        $result = Util::checkSms($this->mobile, 7, $this->verifyCode);
        if ($result['status']!=1) {
            $this->addError('verifyCode',$result['msg']);
        }
    }
    /**
     * Signs user up.
     *
     * @return Member|null the saved model or null if saving fails
     */
    public function resetpaypassword($mid)
    {
        if ($this->validate()) {
            $member = Member::find()->andWhere(['mobile'=>$this->mobile,'id'=>$mid])->one();
            $member->pay_pwd =Util::encrypt($this->pay_pwd) ;
            if ($member->save()) {
                return ['status'=>1,'msg'=>'支付密码设置成功!'];
            }else{
                return ['status'=>0,'msg'=>current($member->getFirstErrors())];
            }
        }else{
            return ['status'=>0,'msg'=>current($this->getFirstErrors())];
            
        }
    }
}
