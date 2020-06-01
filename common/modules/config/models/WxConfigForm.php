<?php
namespace common\modules\config\models;

use Yii;
use yii\base\Model;
use yii\caching\TagDependency;
use yii\helpers\Json;

class WxConfigForm extends Model
{

    public $appid;

    public $appSecret;
    public $token;
    public $encodingAesKey;
    

    public function rules()
    {
        return [
            // appid
            ['appid', 'required'],            
            // appSecret
            ['appSecret', 'required'],
            [['token','encodingAesKey','appid','appSecret'],'safe'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'appid' => 'appid(开发者ID)',
            'appSecret' => 'appSecret(开发者密码)',
            'token'=>'token',
            'encodingAesKey'=>'encodingAesKey(消息加解密密钥)'
            
        ];
    }

    public function loadDefaultValues()
    {
       
        $config = Config::find()->where(['name' => 'wx_config'])->one();
        if (!empty($config['value'])) {
            $value = Json::decode($config['value']);
            $this->appid = $value['appid'];
            $this->appSecret = $value['appSecret'];
            $this->token = $value['token'];
            $this->encodingAesKey = $value['encodingAesKey'];
        }
        
            
    }


    public function save($runValidation = true, $attributeNames = null)
    {
        if ($runValidation && !$this->validate($attributeNames)) {
            return false;
        }
        $config = Config::find()->where(['name' => 'wx_config'])->one();
        $arr = array();
        $arr['appid'] = $this->appid;
        $arr['appSecret'] = $this->appSecret;
        $arr['token'] = $this->token;
        $arr['encodingAesKey'] = $this->encodingAesKey;
        $config['value'] = Json::encode($arr);
        if ($config->save()) {
            TagDependency::invalidate(\Yii::$app->cache,  Yii::$app->config->cacheTag);
            $yiiConfig = Yii::$app->config;
            $yiiConfig->set('wx_appid', $this->appid);
            $yiiConfig->set('wx_appsecret', $this->appSecret); 
            $yiiConfig->set('wx_token', $this->token);
            $yiiConfig->set('wx_encodingAesKey', $this->encodingAesKey);      
            return true;
        }else{
            return false;
        }

    }
}