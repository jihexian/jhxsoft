<?php

namespace common\models;

use common\helpers\Util;
use Yii;
use yii\base\Model;
/**
 * Password reset request form.
 */
class ShopPasswordForm extends Model
{
    public $mobile;
    public $password;
    public $verifyPassword;
    public $verifyCode;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['mobile', 'filter', 'filter' => 'trim'],
            [['mobile','password','verifyPassword','verifyCode'], 'required'],
            ['mobile','match','pattern'=>'/^1\d{10}$/','message'=>'请填写正确手机号！'],
            ['mobile', 'exist',
                'targetClass' => '\common\models\Shop',
                'filter' => ['apply_status' => 1],
                'message' => '手机号不存在',
            ],
           // ['verifyCode', 'checkSms'],
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
    
    public function checkSms(){
        
        $result = Util::checkSms($this->mobile, 7, $this->verifyCode);
        if ($result['status']!=1) {
            $this->addError('verifyCode',$result['msg']);
        }
    }

    
    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $tranction=yii::$app->db->beginTransaction();
        try {
            $user = ShopUser::findOne(['mobile'=>$this->mobile]);
            if(empty($user)){
                $tranction->rollBack();
                return false;
            }
                $user->setPassword($this->password);
                $user->save();
                if($user->hasErrors()){
                    $tranction->rollBack();
                    return false;
                }

                $shop=Shop::findOne(['id'=>$user['shop_id']]);
                if(empty($shop)){
                    $tranction->rollBack();
                    return false;
                }
                $shop->setScenario('pass');
                $shop->password_hash=Yii::$app->security->generatePasswordHash($this->password);
                $shop->save();
                if($shop->hasErrors()){
                    $tranction->rollBack();
                    return false;
                }

            $tranction->commit();
            return true;
        } catch (\Exception $e) {
            $tranction->rollBack();
            return false;
        }
        }
      
    }

  

