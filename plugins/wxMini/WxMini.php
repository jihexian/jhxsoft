<?php
/**
 * 
 * 微信小程序支付
 * Author wsyone wsyone@faxmail.com 
 * Time:2019年5月16日下午7:14:46
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace plugins\wxMini;
use yii\helpers\Url;
use plugins\wxMini\Plugin;
use Yii;
class WxMini
{
    /**
     * 析构流函数
     */
    public function  __construct()
    {
        $plugin=new Plugin();
        $config=$plugin->getConfig();
        $excelpath = dirname(Yii::$app->basePath).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.'wxMini'.DIRECTORY_SEPARATOR;
        require_once($excelpath."lib/WxPay.Api.php"); // 微信扫码支付demo 中的文件         
        require_once($excelpath."example/WxPay.NativePay.php");
        require_once($excelpath."example/WxPay.JsApiPay.php");
       // require_once($excelpath."lib/WxPay.Notify.php");
        \WxPayConfig::$appid =$config['appid']; // * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）       
        \WxPayConfig::$appsecret = $config['appsecert']; // 公众帐号secert（仅JSAPI支付的时候需要配置)，   
        \WxPayConfig::$mchid = $config['mchid']; // * MCHID：商户号（必须配置，开户邮件中可查看）
        \WxPayConfig::$smchid =''; // * SMCHID：服务商商户号（必须配置，开户邮件中可查看）
        \WxPayConfig::$key =$config['key']; // KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
        \WxPayConfig::$sslcert=$config['apiclient_cert'];
        \WxPayConfig::$sslkey=$config['apiclient_key'];
    }    
    /**
     * 服务器点对点响应操作给支付接口方调用
     * 
     */
    function response()
    {                        
        require_once("example/notify.php");  
        $notify = new \PayNotifyCallBack();
        $notify->Handle(false);       
    }
    
    /**
     * 页面跳转响应操作给支付接口方调用
     */
    function respond2()
    {
        // 微信扫码支付这里没有页面返回
    }


   /**
   * 小程序支付
   */

    function xcx($order,$openId,$notifyUrl){
         $tools = new \JsApiPay();
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("支付订单：".$order['order_no']);
        $input->SetAttach("weixin");
        $input->SetOut_trade_no($order['order_no']);
        $input->SetTotal_fee($order['pay_amount']*100); 
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("wx_pay");
        $input->SetNotify_url($notifyUrl);
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order2 = \WxPayApi::unifiedOrder($input);
        $jsApiParameters = $tools->GetJsApiParameters($order2);
        return $jsApiParameters;
        // return json_encode($order2);
    }

    
    /**
     * @desc 微信企业付款
     * @param array $data
     * @return number[]|string[]
     */
    function transfer($data){
        /*code_8提现在线转账业务逻辑代码*/
        //CA证书及支付信息
        $wxchat=array();
        $wxchat['appid'] = \WxPayConfig::$appid;
        $wxchat['mchid'] =  \WxPayConfig::$mchid;
        $wxchat['api_cert'] = \WxPayConfig::$sslcert;
        $wxchat['api_key'] =\WxPayConfig::$sslkey;
        
        $webdata = array(
                'mch_appid' => \WxPayConfig::$appid,
                'mchid'     => \WxPayConfig::$mchid,
                'nonce_str' => md5(time()),
                //'device_info' => '1000',
                'partner_trade_no'=> $data['order_no'], //商户订单号，需要唯一
                'openid' => $data['openid'],//转账用户的openid
                'check_name'=> 'NO_CHECK', //OPTION_CHECK不强制校验真实姓名, FORCE_CHECK：强制 NO_CHECK：
                //'re_user_name' => 'jorsh', //收款人用户姓名
                'amount' => $data['amount'] * 100, //付款金额单位为分
                'desc'   => empty($data['desc'])? '企业付款转账' : $data['desc'],
                'spbill_create_ip' => yii::$app->request->userIP,
        );
        $tarr=array();
        foreach ($webdata as $k => $v) {
            $tarr[] =$k.'='.$v;
        }
        sort($tarr);
        $sign = implode($tarr, '&');
        $sign .= '&key='.\WxPayConfig::$key;
        $webdata['sign']=strtoupper(md5($sign));
        $wget = $this->array2xml($webdata);
        $pay_url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $res = $this->http_post($pay_url, $wget, $wxchat);
        if(!$res){
            return array('status'=>0, 'msg'=>"Can't connect the server" );
        }
        $content = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
        if(strval($content->return_code) == 'FAIL'){
            return array('status'=>0, 'msg'=>strval($content->return_msg));
        }
        if(strval($content->result_code) == 'FAIL'){
            return array('status'=>0, 'msg'=>strval($content->err_code).strval($content->err_code_des));
        }
        $rdata = array(
                'status'=>1,
                'mch_appid'        => strval($content->mch_appid),
                'mchid'            => strval($content->mchid),
                'device_info'      => strval($content->device_info),
                'nonce_str'        => strval($content->nonce_str),
                'result_code'      => strval($content->result_code),
                'partner_trade_no' => strval($content->partner_trade_no),
                'payment_no'       => strval($content->payment_no),
                'payment_time'     => strval($content->payment_time),
        );
        return $rdata;
        /*code_8提现在线转账业务逻辑代码*/
    }
    
    /**
     * 将一个数组转换为 XML 结构的字符串
     * @param array $arr 要转换的数组
     * @param int $level 节点层级, 1 为 Root.
     * @return string XML 结构的字符串
     */
    function array2xml($arr, $level = 1) {
        $s = $level == 1 ? "<xml>" : '';
        foreach($arr as $tagname => $value) {
            if (is_numeric($tagname)) {
                $tagname = $value['TagName'];
                unset($value['TagName']);
            }
            if(!is_array($value)) {
                $s .= "<{$tagname}>".(!is_numeric($value) ? '<![CDATA[' : '').$value.(!is_numeric($value) ? ']]>' : '')."</{$tagname}>";
            } else {
                $s .= "<{$tagname}>" . $this->array2xml($value, $level + 1)."</{$tagname}>";
            }
        }
        $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
        return $level == 1 ? $s."</xml>" : $s;
    }
    
    function http_post($url, $param, $wxchat) {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        if (is_string($param)) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach ($param as $key => $val) {
                $aPOST[] = $key . "=" . urlencode($val);
            }
            $strPOST = join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        if($wxchat){
            curl_setopt($oCurl,CURLOPT_SSLCERT,$wxchat['api_cert']);
            curl_setopt($oCurl,CURLOPT_SSLKEY,$wxchat['api_key']);
            //curl_setopt($oCurl,CURLOPT_CAINFO,$wxchat['api_ca']);
        }
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }
    
     // 微信订单退款原路退回
    public function payment_refund($data){
    /*code_4支付原路退回逻辑*/
        if(!empty($data["transaction_id"])){
            $input = new \WxPayRefund();
            $input->SetTransaction_id($data["transaction_id"]);
            $input->SetTotal_fee($data["total_fee"]*100);
            $input->SetRefund_fee($data["refund_fee"]*100);
            $input->SetOut_refund_no($data["out_refund_no"]);
            $input->SetOp_user_id(\WxPayConfig::$mchid);
            return \WxPayApi::refund($input);
        }else{
            return false;
        }
     /*code_4支付原路退回逻辑*/
    }
    

  
}