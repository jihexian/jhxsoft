<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com
 * Time:2019年9月11日上午11:58:36
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace api\modules\v1\models;

use common\helpers\Util;
use yii\base\Model;


class UnbindMobileForm extends Model
{
    public $mobile;
    public $verifyCode;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['mobile','match','pattern'=>'/^1\d{10}$/','message'=>'请填写正确手机号！'],
            [['mobile','verifyCode'], 'required'],            
            ['verifyCode', 'checkSms'],
            ['mobile', 'checkMobile'],
            
            
            
        ];
    }
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'verifyCode' => '验证码',
        ];
    }
    
  
    public function checkSms(){
        $result = Util::checkSms($this->mobile, 8, $this->verifyCode);
        if ($result['status']!=1) {
            $this->addError('verifyCode',$result['msg']);
        }
    }

    public function checkMobile(){
        $member = Member::find()->where(['mobile'=>$this->mobile])->one();
        if (empty($member)) {
            $this->addError('mobile','请用绑定手机进行解绑!');
        }
    }
    
    public function unbindMobile($mid){
        if ($this->validate()) {
            $member = Member::find()->where(['id'=>$mid])->one();           
            $member->mobile = null;
            if ($member->save()) {
                return ['status'=>1,'msg'=>'解绑成功!'];
            }else{
                return ['status'=>0,'msg'=>current($member->getFirstErrors())];
            }
        }else{
            return ['status'=>0,'msg'=>current($this->getFirstErrors())];
            
        }
    }
}
