<?php
namespace common\modules\config\models;

use Yii;
use yii\base\Model;
use yii\caching\TagDependency;
use yii\helpers\Json;

class XcxConfigForm extends Model
{

    public $appid;

    public $appSecret;
    

    public function rules()
    {
        return [
            // appid
            ['appid', 'required'],            
            // appSecret
            ['appSecret', 'required'],    

        ];
    }

    public function attributeLabels()
    {
        return [
            'appid' => 'appid',
            'appSecret' => 'appSecret',
            
        ];
    }

    public function loadDefaultValues()
    {
       
        $config = Config::find()->where(['name' => 'xcx_config'])->one();
        if (!empty($config['value'])) {
            $value = Json::decode($config['value']);
            $this->appid = $value['appid'];
            $this->appSecret = $value['appSecret'];
        }
        
            
    }


    public function save($runValidation = true, $attributeNames = null)
    {
        if ($runValidation && !$this->validate($attributeNames)) {
            return false;
        }
        $config = Config::find()->where(['name' => 'xcx_config'])->one();
        $arr = array();
        $arr['appid'] = $this->appid;
        $arr['appSecret'] = $this->appSecret;
        $config['value'] = Json::encode($arr);
        if ($config->save()) {
            TagDependency::invalidate(\Yii::$app->cache,  Yii::$app->config->cacheTag);
            $yiiConfig = Yii::$app->config;
            $yiiConfig->set('xcx_appid', $this->appid);
            $yiiConfig->set('xcx_appSecret', $this->appSecret);            
            return true;
        }else{
            return false;
        }

    }
}