<?php
namespace common\modules\config\models;

use Yii;
use yii\base\Model;
use yii\caching\TagDependency;
use yii\helpers\Json;

class BaseSmsConfigForm extends Model
{

    public $accessKeyId;

    public $accessKeySecret;
    
    public $mobile;

    public function rules()
    {
        return [
            // accessKeyId
            ['accessKeyId', 'required'],            
            // accessKeySecret
            ['accessKeySecret', 'required'],    
            
            ['mobile', 'required'],
            ['mobile','match','pattern'=>'/^1[0-9]{10}$/','message'=>'手机号码必须为1开头的11位纯数字'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'accessKeyId' => 'accessKeyId',
            'accessKeySecret' => 'accessKeySecret',
            'mobile' => '测试手机号',
            
        ];
    }

    public function loadDefaultValues()
    {
       
        $config = Config::find()->where(['name' => 'sms_base'])->one();
        if (!empty($config['value'])) {
            $value = Json::decode($config['value']);
            $this->accessKeyId = $value['accessKeyId'];
            $this->accessKeySecret = $value['accessKeySecret'];
            $this->mobile = $value['mobile'];            
        }
        
            
    }


    public function save($runValidation = true, $attributeNames = null)
    {
        if ($runValidation && !$this->validate($attributeNames)) {
            return false;
        }
        $config = Config::find()->where(['name' => 'sms_base'])->one();
        $arr = array();
        $arr['accessKeyId'] = $this->accessKeyId;
        $arr['accessKeySecret'] = $this->accessKeySecret;
        $arr['mobile'] = $this->mobile;
        $config['value'] = Json::encode($arr);
        if ($config->save()) {
            TagDependency::invalidate(\Yii::$app->cache,  Yii::$app->config->cacheTag);
            $yiiConfig = Yii::$app->config;
            $yiiConfig->set('accessKeyId', $this->accessKeyId);
            $yiiConfig->set('accessKeySecret', $this->accessKeySecret);
            $yiiConfig->set('mobile', $this->mobile);
            return true;
        }else{
            return false;
        }

    }
}