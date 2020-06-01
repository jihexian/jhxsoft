<?php
namespace common\modules\config\models;

use Aliyun\Core\Exception\ServerException;
use common\components\alisms\BaseSms;
use Yii;
use yii\base\Model;
use yii\caching\TagDependency;
use yii\helpers\Json;
use common\logic\SmsLogic;

class SmsConfigForm extends Model
{

    public $accessKeyId;

    public $accessKeySecret;    
    
    public $commonTemplateCode;
    
    public $signName;
    
    public $mobile;
    
    public $type;
    
    public $tempText;
    
    private $tempConfig;
   

    public function rules()
    {
        return [
            // accessKeyId
            ['accessKeyId', 'safe'],            
            // accessKeySecret
            ['accessKeySecret', 'safe'], 
            ['tempText', 'safe'],
            
            ['commonTemplateCode', 'required'],  
            ['signName', 'required'],
            
            ['mobile', 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'accessKeyId' => 'accessKeyId',
            'accessKeySecret' => 'accessKeySecret',
            'mobile' => '测试手机号',
            'commonTemplateCode'=>'公共模板',
            'signName'=>'短信签名',
            'tempText'=>'模板示例'
            
        ];
    }

    public function loadDefaultValues()
    {
       
        $config = Config::find()->where(['name' => 'sms_base'])->one();
        if ($this->type==1) {
            $this->tempConfig = Config::find()->where(['name' => 'sms_commom_tmp'])->one();
            $this->tempText = "尊敬的用户，您的验证码为\${code}, 本验证码有效时间为10分钟, 请勿告诉他人.";
        }elseif ($this->type ==2){
            $this->tempConfig = Config::find()->where(['name' => 'sms_order_tmp'])->one();
            $this->tempText = "您有新订单，收货人：\${consignee}，联系方式：\${phone}，请您及时查收.";
        }elseif ($this->type ==3){
            $this->tempConfig = Config::find()->where(['name' => 'sms_delivery_tmp'])->one();
            $this->tempText = "尊敬的\${user_name}用户，您的订单\${order_sn}已发货，收货人\${consignee}，请您及时查收.";
        }
        
        if (!empty($config['value'])) {
            $value = Json::decode($config['value']);
            $this->accessKeyId = $value['accessKeyId'];
            $this->accessKeySecret = $value['accessKeySecret'];
            $this->mobile = $value['mobile'];            
        }
        if (!empty($this->tempConfig['value'])) {
            $commonValue = Json::decode($this->tempConfig['value']);
            $this->commonTemplateCode = $commonValue['commonTemplateCode'];
            $this->signName = $commonValue['signName'];
        }

    }


    public function save($runValidation = true, $attributeNames = null)
    {
        if ($runValidation && !$this->validate($attributeNames)) {
            return false;
        }
        
//         $config = $this->getConfig();
//         TagDependency::invalidate(\Yii::$app->cache,  Yii::$app->config->cacheTag);
//         $config->set('accessKeyId', $this->accessKeyId);
//         $config->set('accessKeySecret', $this->accessKeySecret);   
//         $config->set('commonTemplateCode', $this->commonTemplateCode);   
//         $config->set('mobile', $this->mobile); 
//         $config->set('signName', $this->signName);
        $smsLogic = new SmsLogic();        
        
        try {  
            $baseSms =  new BaseSms();  
            if ($this->type==1) {
                $code = strval(rand(100000, 999999));
                $templateParam = $smsLogic->getTempParams(1, ['code'=>$code]);
            }elseif ($this->type ==2){
                $templateParam = $smsLogic->getTempParams(2, ['phone'=>$this->mobile,'consignee'=>'wsyone']);
            }elseif ($this->type ==3){
               
                $templateParam = $smsLogic->getTempParams(3, ['order_sn'=>'44647987464464','username'=>$this->mobile,'consignee'=>'测试收货人']);
            }
            $result = $baseSms->sendSms($this->mobile, $this->commonTemplateCode,$this->signName, $templateParam);
            if ($result['status']==0) {
                if (strpos('&^!@'.$result['msg'], "签名不合法")>0) {
                    $this->addError('signName','请填写正确的短信签名！');
                }
                if (strpos('&^!@'.$result['msg'], "模板不合法")>0) {
                    $this->addError('commonTemplateCode','请填写正确的模板！');
                }
                return false;
            }else{
                $arr = array();
                $arr['commonTemplateCode'] = $this->commonTemplateCode;
                $arr['signName'] = $this->signName;
                $this->tempConfig->value = Json::encode($arr);
                if ($this->tempConfig->save()) {
                    return true;
                }else{
                    return false;
                }
                
                
            }
        } catch (ServerException $e) {
            if ($e->getErrorCode()=='InvalidAccessKeyId.NotFound') {
                $this->addError('accessKeyId','请填写正确的accessKeyId和accessKeySecret！');
            }
            if ($e->getErrorCode()=='SignatureDoesNotMatch') {
                $this->addError('commonTemplateCode','请填写正确的commonTemplateCode！');
            }
            
            //Yii::$app->session->setFlash($e->getMessage());
            return false;
        }
       

    }
}