<?php
/**
 * Created by notepad++.
 * Author: 小黑
 * DateTime: 2018/4/9
 * Description:
 */
namespace api\modules\v1\controllers;

/*微信公众号登录控制器*/

use yii;
use  api\common\controllers\Controller;
use  api\common\controllers\WxApi;
use  api\common\controllers\XcxApi;
use  common\models\Member;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

class WxMemberController extends Controller
{


   /*小程序开始登录入口
	返回前台登录结果
	返回代码描述，101获取openid失败，102新增用户，103老用户，自动登录该账号,104已登录
   */
   private $defaultUsername="公众号用户";
   private $defaultAvatar="../../../imgs/icon/user@default.png";//默认头像地址
   
   public function actionXcxLogin(){
	   $session = Yii::$app->session;
		if(!$this->isLogined())  //判断用户是否已经登录。如果以登录返回用户信息，如果未登录执行登录流程
		{
			$this->setSessionid();  //获取新的session_id用于返回给前端
			$code=Yii::$app->request->post("code",null);
			$xcx=new XcxApi();
			$rs=$xcx->getOpenid($code);//得到获取openid的返回结果,并存到Session
			if($rs['status'])  //如果正确获取到openid，使用openid进行下一步操作
			  $data=$this->ExistMember();
			else
			  $data=['status'=>false,'msg'=>"无法获取openid","code"=>101];
		}else
			$data=['status'=>true,'msg'=>"已登录，无需重新登录","code"=>104];
	   $data['session_id']=$session["session_id"];
	   $data['user']=$session['member'];
	   return $data; /*返回登录结果*/   
   }
   /*后台判断是否登录示例*/
   public function  actionExample(){
	   if($this->isLogined())  //判断用户是否已经登录。如果以登录返回用户信息，如果未登录执行登录流程
	   {
		   /*这里执行登陆后相关业务代码*/
		   $data=['status'=>true,'msg'=>"已登录，无需重新授权","code"=>104,'user'=>$session['member']];
	   }
	   else
		  $data=$this->relogin();
	   return $data; /*返回请求结果*/
   }
   //检查数据库中是否已存在openid,如果存在则登录用户，如果不存在则写入数据库并登录
   public function ExistMember(){
	   $session = Yii::$app->session;
	   if(isset($session['xcxOpenid'])&&!empty($session['xcxOpenid'])){
		    $model = new Member();
			$member=$model->getMemberByopenid($session['xcxOpenid'],'xcx');
			if(!empty($member))
			{
				$this->loginMember($member); //如果以存在用户，直接登录
				$rs=['status'=>true,'msg'=>"成功登录用户","code"=>103];
			}
			else
				$rs=$this->register();  //用户不存在则注册用户
			return $rs;
	   }
   }
   /*登录用户，将登录信息保存到session等登录相关操作*/
   private function loginMember($member){
	   $session = Yii::$app->session;
	   $session['member']=[
		'uid'=>$member['id'],
		'mobile'=>$member['mobile'],
		'username'=>$member['username'],
		'avatarUrl'=>$member['avatarUrl']
	   ];
   }
   /*更新用户信息，账户信息从腾讯微信获取*/
   public function actionXcxUpdateMember(){
	   $session = Yii::$app->session;
	   $member=Yii::$app->request->post("data");
	   $member=json_decode($member,true);
	   $data["username"]=$member["nickName"];
	   $data["sex"]=$member["gender"]==1?"男":"女";
	   $data["province"]=$member["province"];
	   $data["city"]=$member["city"];
	   $data["avatarUrl"]=$member["avatarUrl"];
	   $model = new Member(['scenario' => 'xcx_create']);
	   if ($model::updateAll($data,['xcx_openid'=>$session['xcxOpenid']]))
	   {
			$member=$model->getMemberByopenid($session['xcxOpenid'],'xcx');
			$this->loginMember($member); //更新完成之后，重置session用户信息
		    $rs=['status'=>true,'msg'=>"更新用户数据成功","code"=>106];
	   }else
		    $rs=['status'=>true,'msg'=>"无法更新数据","code"=>107];
	   $rs['session_id']=$session["session_id"];
	   $rs['user']=$session['member'];
	   return $rs;
   }
   /*新增一个新用户*/
   private function register(){
	   $session = Yii::$app->session;
	   $data["username"]=$this->defaultUsername;
	   $data["avatarUrl"]=$this->defaultAvatar;
	   $data["xcx_openid"]=$session['xcxOpenid'];
	   $model = new Member(['scenario' => 'xcx_create']);
       if ($model->load($data,'') && $model->save())
	   {   
		   $id=$model->attributes['id'];
		   $member=$model->getMemberByid($id);
		   $this->loginMember($member); //注册完成之后，直接登录用户
           $rs=['status'=>true,'msg'=>"已成功注册新用户并登录","code"=>102];
	   }
       else
	   {
		  var_dump($model->getErrors());
		  $rs=['status'=>true,'msg'=>"用户信息保存失败","code"=>105];
	   }
	  return $rs;
   }
   /**/
   /*
	判断用户是否已经登录
   */
   public function isLogined(){
	   $session = Yii::$app->session;
	   if(isset($session['member']['uid'])&&!empty($session['member']['uid']))
		    return true;
	   else
		   return false;
   }
   /*如果用户为登陆，将新的session_id返回给前端用户，让前端依据code==99重新执行微信登录
   */
   public function relogin(){
	  $this->setSessionid();
	  $data=['status'=>false,'msg'=>"sessionid无效","code"=>99];
	  return $data;
   }
   /*这里的session_id如果换成token则需要改变token的生成和验证方法。使用session机制则仅验证前后端session是否一致*/
   public function setSessionid(){
	  $session = Yii::$app->session;
	  if(!isset($_SESSION)){
		session_start();
	  }
	  $token=session_id();  //生成token,session机制用session_id代替
	  $session['session_id']=$token;  //将当前session_id返回给前台作为新的凭证
   }
   public function actionTest(){
	   $session = Yii::$app->session;
	   var_dump($session['member']['uid']);
   }
}