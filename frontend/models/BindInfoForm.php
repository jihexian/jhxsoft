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


class BindInfoForm extends Model
{
    public $mobile;
    public $verifyCode;
    public $username;
    public $company;
    private $_user;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // mobile and password are both required
            [['mobile','verifyCode'], 'required'],
            ['verifyCode', 'checkSms'],
          //  ['mobile', 'checkUnique'],
            ['username', 'nameUnique'],
            //检查用户名是否重复
            ['mobile','match','pattern'=>'/^1\d{10}$/','message'=>'请填写正确手机号！'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'password' => '密码',
            'company'=>'公司',
            'username'=>'用户名',
            
       
        ];
    }  
/*     public function checkUnique(){
        if (empty($this->getErrors('verifyCode'))) {
            $member = Member::find()->where(['mobile'=>$this->mobile])->one();
            if(!empty($member)){
                $this->addError('mobile','手机号已被使用！');
            }
        }
    } */
    public function nameUnique(){
        if (empty($this->getErrors('verifyCode'))) {
            $member = Member::find()->where(['username'=>$this->username])->one();
            if(!empty($member)){
                $this->addError('username','用户名已被使用！');
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
/*     public function bindInfo($mid)
    {
        if ($this->validate()) {
            $member = Member::findOne($mid);
            $member->mobile = $this->mobile;
            $member->company=$this->company;
            if(!empty($this->username)){
                $member->username=$this->username;
            }
            //$member->password = Util::encrypt($this->password);
            if ($member->save()) {
                return $member;
            }
        }
        
        return;
    } */
    
    public function bindInfo($mid,$wx_openid){
        if ($this->validate()) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $member = Member::find()->where(['and',['mobile'=>$this->mobile],['<>','id',$mid]])->one();
            $user=member::findOne(['id'=>$mid]);
         if(empty($wx_openid)||!strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')){
                $transaction->rollBack();
                return ['status'=>0,'msg' =>'错误'];
            } 
            if(!empty($member)){
             
                $member->wx_openid=$wx_openid;
                $member->save();
                if($member->hasErrors()){
                    $transaction->rollBack();
                    return ['status'=>0,'msg' =>current($member->getFirstErrors())];
                }
                //删除账号
                $user->delete();
                if($user->hasErrors()){
                    $transaction->rollBack();
                    return ['status'=>0,'msg' =>current($user->getFirstErrors())];
                }
                yii::$app->session->remove('regInfo');
                Yii::$app->getUser()->login($member) ;   
            }else{
                $user->mobile = $this->mobile;
                $user->company=$this->company;
                $user->mobile_validated=1;
                if(!empty($this->username)){
                    $user->username=$this->username;
                }
                $user->save();
                if($user->hasErrors()){
                    $transaction->rollBack();
                    return ['status'=>0,'msg' =>current($user->getFirstErrors())];
                }
                //$member->password = Util::encrypt($this->password);
            }
            $transaction->commit();
            return ['status'=>1,'msg' =>'成功' ];
            
        }catch (\Exception $e){
            $transaction->rollBack();
            return ['status'=>0,'msg' =>$e->getMessage()];
        }
    }
    }

}
