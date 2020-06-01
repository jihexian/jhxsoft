<?php
/**
 * Created by notepad++.
 * Author: 小黑
 * DateTime: 2018/4/9
 * Description:
 */
namespace api\common\controllers;
use Yii;
use common\helpers\Util;
use common\models\Live;
use yii\helpers\Json;
class XcxApi
{
   //小程序openid，由腾讯提供
   private $xcxOpenid;
   //小程序session_key,用户数据进行加密签名的密钥，由腾讯提供
   private $session_key;
  
   //前台通过wx.login()传过来code,使用code和腾讯换取用户的openid
   public function getOpenid($code){
	   
	   $appid =env('XCX_APPID');     
	   $appsecret =env('XCX_APPSECRET');
	   $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$appsecret."&grant_type=authorization_code&js_code=".$code;
	   $rs=$this->sendCurl($url);
	   //判断是否获取成功
	   if(isset($rs['errcode']))
	   {
		  $data['msg']=$rs['errmsg'];
		  $data['status']=false;
	   }else{
		   $this->xcxOpenid=$rs['openid'];
		   $this->session_key=$rs['session_key'];
		   $data['openid']=$rs['openid'];
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
	/**
	 * 生成小程序码
	 */
	public function getQrcode($page,$scene){
	    //$page = 'pages/product/product';
	    $appid =Yii::$app->config->get('XCX_APPID');
	    $appsecret = Yii::$app->config->get('XCX_APPSECRET');
	    $access_token = $this->getAccessToken($appid, $appsecret);	    
	    $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token;
	   
	    $post_data = array(
	        //"access_token" => $access_token,
	        "scene" => $scene,
	        'page'=>$page,
	        'width'=>'200',
        );
	    
	    $post_data = json_encode($post_data);
	    
	    $data= $this->_requestPost($url,$post_data);	    
	    
	    if (!$data) {
	        return false;
	    } 
	    if (stristr($data, "errcode")) {
	        return false;
	    }
        $dir = Yii::getAlias('@storagePath/upload/')."xcxqrcode/".$page;
        $file = $dir."/".$scene.'.png';
        if(!file_exists($file)){
            Util::create_folders($dir);
            file_put_contents($file, $data);
        }
        //显示获得的数据
        //print_r($data);
        return $file;
	}
	
	public function getAccessToken($appId, $appSecret)
	{
	    $accessToken = Yii::$app->cache->get('xcxAccessToken');
	    if ($accessToken === false) {
	        $accessTokenRes = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$appSecret}");
	        $accessToken = \yii\helpers\Json::decode($accessTokenRes)['access_token'];
	        $expires_in = \yii\helpers\Json::decode($accessTokenRes)['expires_in'];
	        $expires_in = $expires_in - 200;
	        Yii::$app->cache->set('xcxAccessToken', $accessToken,$expires_in);
	    }
	    
	    return $accessToken;
	}
	/**
	 * 直播间列表
	 * @param array $post_data
	 * @return string|mixed
	 */
  	public function getLiveList($post_data=array()){
	    $data = Yii::$app->cache->get('live');
	    if ($data === false) {
	        $appid =env('XCX_APPID');
	        $appsecret = env('XCX_APPSECRET');
	        $access_token = $this->getAccessToken($appid, $appsecret);
	        $url = "http://api.weixin.qq.com/wxa/business/getliveinfo?access_token=".$access_token;
	        $post_data = json_encode($post_data);   
	        $data= $this->_requestPost($url,$post_data);
	        $data=json_decode($data,true);
	        if($data['errcode']==0){
	            Yii::$app->cache->set('live', $data,3*60);
	        }
	    }
	    return $data;
	} 
	
	public function UpdateLive(){
	        $appid =env('XCX_APPID');
	        $appsecret = env('XCX_APPSECRET');
	        $access_token = $this->getAccessToken($appid, $appsecret);
	        $page=1;
	        $num=50;
	        $ids=array();
	        //获取全部直播间列表
	        while (true){
    	        $url = "http://api.weixin.qq.com/wxa/business/getliveinfo?access_token=".$access_token;
    	        $post_data = array(
    	            "start" => ($page-1)*$num,
    	            "limit"=>$num
    	        );
    	        $post_data = json_encode($post_data);
    	        $data= $this->_requestPost($url,$post_data);
    	        $data=json_decode($data,true);
    	        if ($data['errcode'] !== 0) {  //获取失败，报错误信息
    	            if ($data['errcode'] === 1) {
    	                return ['status'=>0,'msg'=>'直播间列表为空'];
    	            } else if ($data['errcode'] === 48001) {
    	                return ['status'=>0,'msg'=>'小程序没有直播权限'];
    	            }
    	            return ['status'=>0,'msg'=>$data['errmsg']];
    	         }
    	         $room=$data['room_info'];
    	         foreach ($room as $vo){
    	             $room=Live::findOne(['roomid'=>$vo['roomid']]);
    	             $ids[]=$vo['roomid'];
    	             if(!$room){                                                        //数据不存在时新增记录
    	               $su=new Live();
    	               $su->load($vo,'');
    	               $su->goods=Json::encode($vo['goods']);
    	               if($vo['live_status']==103){                                 //保存回放记录
    	                   $history=self::getHistory($vo['roomid']);
    	                   isset($history['live_replay'])&&$su->live_replay=Json::encode($history['live_replay']);
    	               }
    	               $su->save();
    	               if($su->hasErrors()){
    	                   return ['status'=>0,'msg'=>current($su->getFirstErrors())];
    	               }    
    	             }else{
    	                 $room->live_status=$vo['live_status'];
    	                 if($vo['live_status']==103&&!$room['live_replay']){  //保存回放记录
    	                    $history=self::getHistory($vo['roomid']);
    	                    $room->live_replay=Json::encode($history['live_replay']);
    	                 }
    	                 $room->save();
    	             }
    	         }
    	         
    	         if ($data['total'] < $page * $num) {
    	            break;
    	        }
    	        $page++;
	        };
	        //删除已经被小程序后台删除的直播间
	        Live::deleteAll(['not in','roomid',$ids]);
	        return ['status'=>1,'msg'=>'同步完成'];
	} 
	
	/**
	 * 直播间列表
	 * @param array $post_data
	 * @return string|mixed
	 */
	public function getHistory($id){
	/*       $data = Yii::$app->cache->get('history'.$id);
	      if($data){ */
        	  $post_data = array(
        	        "action"=>"get_replay",
        	        "room_id"=>$id,
        	        "start" =>0,
        	        "limit"=>100
        	    );
	        $appid =env('XCX_APPID');
	        $appsecret = env('XCX_APPSECRET');
	        $access_token = $this->getAccessToken($appid, $appsecret);
	        $url = "http://api.weixin.qq.com/wxa/business/getliveinfo?access_token=".$access_token;
	        $post_data = json_encode($post_data);
	        $data= $this->_requestPost($url,$post_data);
	        $data=json_decode($data,true);
	      /*   if($data['errcode']==0){
	            Yii::$app->cache->set('history'.$id, $data,10*60);
	        }
	      } */
	      return $data;
	}
	
	/**
	 * 发送GET请求的方法
	 * @param string $url URL
	 * @param bool $ssl 是否为https协议
	 * @return string 响应主体Content
	 */
	protected function _requestPost($url, $data, $ssl=true) {
	    //curl完成
	    $curl = curl_init();
	    //设置curl选项
	    curl_setopt($curl, CURLOPT_URL, $url);//URL
	    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '
    Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0 FirePHP/0.7.4';
	    curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);//user_agent，请求代理信息
	    curl_setopt($curl, CURLOPT_AUTOREFERER, true);//referer头，请求来源
	    curl_setopt($curl, CURLOPT_TIMEOUT, 30);//设置超时时间
	    //SSL相关
	    if ($ssl) {
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//禁用后cURL将终止从服务端进行验证
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//检查服务器SSL证书中是否存在一个公用名(common name)。
	    }
	    // 处理post相关选项
	    curl_setopt($curl, CURLOPT_POST, true);// 是否为POST请求
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);// 处理请求数据
	    // 处理响应结果
	    curl_setopt($curl, CURLOPT_HEADER, false);//是否处理响应头
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//curl_exec()是否返回响应结果
	    
	    // 发出请求
	    $response = curl_exec($curl);
	    if (false === $response) {
	        echo '<br>', curl_error($curl), '<br>';
	        return false;
	    }
	    curl_close($curl);
	    return $response;
	}
}