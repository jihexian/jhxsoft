<?php
/**
 * Created by notepad++.
 * Author: 小黑
 * DateTime: 2018/4/9
 * Description:
 */
namespace api\modules\v1\controllers;


use yii;
use api\common\controllers\Controller;
use api\common\controllers\XcxApi;
use api\modules\v1\models\Member;
use common\models\AccessToken;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use common\models\OrderLog;
use common\logic\OrderLogic;
use common\logic\DistributeLogic;
use common\helpers\Tools;


class XcxMemberController extends Controller
{
	

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => QueryParamAuth::className(),
                'tokenParam' => 'token',
                'optional' => [
                    'xcx-login',
                    'register',
                    'xcx-update-member',
                    'check-token'
                ],
            ]
        ]);
    }

   /*小程序开始登录入口
	返回前台登录结果
	返回代码描述，101获取openid失败，102新增用户，103老用户，自动登录该账号,104已登录
   */
   private $defaultUsername="小程序用户";
   private $defaultAvatar="../../../imgs/icon/user@default.png";//默认头像地址
   private $member;
   private $token;

   public function actionXcxLogin(){
		if(!$member=$this->isLogined())  //判断用户是否已经登录。如果以登录返回用户信息，如果未登录执行登录流程
		{
			$code=Yii::$app->request->post("code",null);
			$xcx=new XcxApi();
			$rs=$xcx->getOpenid($code);//得到获取openid的返回结果
			if($rs['status'])  //如果正确获取到openid，使用openid进行下一步操作
			  $data=$this->ExistMember($rs['openid']);
			else
			  $data=['status'=>false,'msg'=>"无法获取openid","code"=>101];
		}else{
		    $memberData = $member;
		    unset($memberData['access_token']);
			unset($memberData['auth_key']);
			unset($memberData['password']);
			unset($memberData['pay_pwd']);
			unset($memberData['xcx_openid']);
			unset($memberData['wx_openid']);
		    // $memberData['id'] = $member->id;
		    // $memberData['access_token'] = $member->access_token;
		    $data=['status'=>true,'msg'=>"已登录，无需重新登录","code"=>104,'user'=>$memberData];
		}
			
	   $data['token']=$this->token;
	   return $data; /*返回登录结果*/   
   }
   /*后台判断是否登录示例*/
   public function  actionExample(){
	   if($member=$this->isLogined())  //判断用户是否已经登录。如果以登录返回用户信息，如果未登录执行登录流程
	   {
		   /*这里执行登陆后相关业务代码*/
		   $data=['status'=>true,'msg'=>"已登录，无需重新授权","code"=>104,'user'=>$member];
	   }
	   else
	   {
		  $data=$this->relogin();
	   }
	   return $data; /*返回请求结果*/
   }
   //检查数据库中是否已存在openid,如果存在则登录用户，如果不存在则写入数据库并登录
   public function ExistMember($openid){
	   if(!empty($openid)){
		    $model = new Member();
			$member=$model->getMemberByopenid($openid,'xcx');
			unset($member['access_token']);
			unset($member['auth_key']);
			unset($member['password']);
			unset($member['pay_pwd']);
			unset($member['xcx_openid']);
			unset($member['wx_openid']);
			if(!empty($member))
			{
				$this->loginMember($member); //如果以存在用户，直接登录
				$this->member=$member;

				$rs=['status'=>true,'msg'=>"成功登录用户","code"=>103,'user'=>$this->member];
			}
			else
				$rs=$this->register($openid);  //用户不存在则注册用户
			return $rs;
	   }
   }
   /*登录用户，将登录信息保存到session等登录相关操作*/
   private function loginMember($member){
		$accessToken=new Member();
		$this->token=$accessToken->create_token($member['id']); //生成一个新的token值，并关联用户uid
   }
   /*更新用户信息，账户信息从腾讯微信获取*/
   public function actionXcxUpdateMember(){
	   if($old_member=$this->isLogined())
	   {
		   $member=Yii::$app->request->post("data");
		   $distributeOpen = Yii::$app->config->get('distribute_open');//判断分销模块是否开启
		   if (isset($member['data']['pid'])&&$distributeOpen) {
		       $distributLogic = new DistributeLogic();		       
		       $distributLogic->bind($member['data']['pid'], $old_member['id']);
		   }
		   $member=json_decode($member,true);
		   $data["username"]= Tools::emoji_encode($member["nickName"]);
		   $data["sex"]=$member["gender"]==1?"男":"女";
		   $data["province"]=$member["province"];
		   $data["city"]=$member["city"];
		   $data["avatarUrl"]=$member["avatarUrl"];
		   $data['flag']=1;
		   $data['last_login'] =  time();
		   $model = new Member(['scenario' => 'xcx_create']);
		   if ($model::updateAll($data,['xcx_openid'=>$old_member['xcx_openid']]))
		   {
				$new_member=$model->getMemberByopenid($old_member['xcx_openid'],'xcx');
				$rs = ['status'=>true,'msg'=>"更新用户数据成功","code"=>106];
				unset($new_member['access_token']);
				unset($new_member['auth_key']);
				unset($new_member['password']);
				unset($new_member['pay_pwd']);
				unset($new_member['xcx_openid']);
				unset($new_member['wx_openid']);
				
				$rs['user']=$new_member;
		   }else
		   {
				$rs = ['status'=>true,'msg'=>"无法更新数据","code"=>107];
				$rs['user']=$member;
			}
		   return $rs;
	   }
	   else
	   {
		  $data=$this->relogin();
		  return $data;
	   }
		   
   }
   /*新增一个新用户*/
   private function register($openid){
	   $data["username"]=$this->defaultUsername;
	   $data["avatarUrl"]=$this->defaultAvatar;
	   $data["xcx_openid"]=$openid;
	   $model = new Member(['scenario' => 'xcx_create']);
       if ($model->load($data,'') && $model->save())
	   {   
		   $id=$model->attributes['id'];
		   $member=$model->getMemberByid($id);
		   $this->loginMember($member); //注册完成之后，直接登录用户
		   $this->member=$member;
           $rs=['status'=>true,'msg'=>"已成功注册新用户并登录","code"=>102,'user'=>$this->member];
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
   public  function isLogined(){
	   $this->token=Yii::$app->request->post("token");
	   $member=new Member();
	   return $member->auth_token($this->token);
   }
   /*如果用户未登陆，让前端依据errcode==401重新执行微信登录
   */
   public function relogin(){

	  $data=['status'=>false,'msg'=>"token无效,请重新登录","code"=>99,'errcode'=>401];

	  return $data;
   }

    /**
     * @return array|bool|null|yii\db\ActiveRecord
     * 判断token值是否过期
     */
   public function actionCheckToken(){
       $token=Yii::$app->request->get("token");
       $member=new Member();
       $data=$member->auth_token($token);
       if(!$data){
           return   $data=['status'=>false,'msg'=>"token无效,请重新登录","errcode"=>401];
       }else{
           return   $data=['status'=>true,'msg'=>"token有效",'code'=>1];
       }

}

//    public function actionTest(){
//        $this->token=Yii::$app->request->get("token");
//        $member=new Member();
//       if(!$member->auth_token($this->token)){
//           return  $data=['status'=>false,'msg'=>"token无效,请重新登录","code"=>time()];
//       }else{
//           return $member->auth_token($this->token);
//       }

//    }
   

    public function actionQrcode(){
        if (Yii::$app->request->isPost) {
            $page = Yii::$app->request->post('page');
            $scene = Yii::$app->request->post('scene');
            $xcxApi = new XcxApi();
            $result = $xcxApi->getQrcode($page, $scene);
            if ($result) {
                return ['status'=>1,'data'=>Yii::$app->params['domain'].'/'.explode("/web/", $result)[1]];
            }else{
                return ['status'=>0,];
            }
            
            
        }
    }
}