<?php
/**
 * Created by PhpStorm.
 * Author: yidashi
 * DateTime: 2017/3/8 17:46
 * Description:
 */

namespace api\modules\v1\models;

use common\helpers\Util;
use yii\base\Model;


class BindMobileForm extends Model
{
    public $mobile;
    public $verifyCode;
    public $name;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['mobile','match','pattern'=>'/^1\d{10}$/','message'=>'请填写正确手机号！'],
            [['mobile','verifyCode'], 'required'],
            ['verifyCode', 'checkSms'],
            ['mobile', 'checkUnique'],
            ['name','safe']
     
        ];
    }
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'verifyCode' => '验证码',
            'name'=>'姓名',
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
        
        $result = Util::checkSms($this->mobile, 1, $this->verifyCode);
        if ($result['status']!=1) {
            $this->addError('verifyCode',$result['msg']);
        }
    }

    public function bindMobile($mid,$distribut=0)
    {
        if ($this->validate()) {
            $member = Member::findOne($mid);
            $member->mobile = $this->mobile;
            if(!empty($this->name)){
               $member->name=$this->name;
            }
            if($distribut==1){
                $member->is_distribut=1;
            }
            if ($member->save()) {
                return ['status'=>1,'msg'=>'操作成功!'];
            }else{
                return ['status'=>0,'msg'=>current($member->getFirstErrors())];
            }
        }else{
            return ['status'=>0,'msg'=>current($this->getFirstErrors())];
            
        }
        
    }
   
}
