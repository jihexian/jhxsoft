<?php
namespace plugins\money;
use yii\helpers\Url;
use common\models\Member;
class Money{
    public $config;
    public function __construct(){
        $plugin=new Plugin();
        $this->config=$plugin->getConfig();
        
    }
    /**
               * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $config_value    支付方式信息
     */
    function get_code($order, $config_value)
    {
       
        
        $url = getenv('SITE_URL').Url::to('Payment/returnUrl',array('pay_code'=> $this->config['id']));
        return "<script>location.href='".$url."';</script>";
    }  
    
    public function payment_refund($data){
      
    }
}