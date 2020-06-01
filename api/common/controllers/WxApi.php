<?php
/**
 * Created by notepad++.
 * Author: 小黑
 * DateTime: 2018/4/9
 * Description:
 */
namespace api\common\controllers;
use Yii;
class WxApi
{
   //小程序openid，由腾讯提供
   private $xcxOpenid;
   //小程序session_key,用户数据进行加密签名的密钥，由腾讯提供
   private $session_key;
  
   //前台通过wx.login()传过来code,使用code和腾讯换取用户的openid
   public function getOpenid($code){
	   
	   $appid =Yii::$app->config->get('xcx_appid');     
	   $appsecret =Yii::$app->config->get('xcx_appSecret');  
	   $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$appsecret."&grant_type=authorization_code&js_code=".$code;
	   $rs=$this->sendCurl($url);
	   //判断是否获取成功
	   if(isset($rs['errcode']))
	   {
		  $data['msg']=$rs['errmsg'];
		  $data['status']=false;
	   }else{
		   $session = Yii::$app->session;
		   $this->xcxOpenid=$rs['openid'];
		   $this->session_key=$rs['session_key'];
		   //记录session
		   $session['xcxOpenid'] =$this->xcxOpenid;
		   $session['xcxSessionKey'] =$this->session_key;
		   $data['msg']="获取openid成功";
		   $data['status']=true;
	   }
	  return $data;
	}
	//发送curl，返回远程服务器返回的json数据
	private function sendCurl($url){
	   $curl = curl_init();    
	   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);    
	   curl_setopt($curl, CURLOPT_TIMEOUT, 500);    
	   // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。    
	   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);    
	   curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);    
	   curl_setopt($curl, CURLOPT_URL, $url);    
	   $res = curl_exec($curl);    
	   curl_close($curl);    
	   $json_obj = json_decode($res,true);
	   return $json_obj;
	}
}