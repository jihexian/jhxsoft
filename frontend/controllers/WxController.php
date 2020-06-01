<?php

namespace frontend\controllers;

use Yii;


use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\components\weixin\BaseWechat;
use yii\helpers\Json;
use frontend\models\Member;
use yii\base\Exception;
use frontend\models\BindRegisterForm;
use common\logic\DistributeLogic;
use common\helpers\Tools;

/**
 * Site controller.
 */
class WxController extends Controller
{
	
	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
				'error' => [
						'class' => 'yii\web\ErrorAction',
				],
		];
	}
	/**
	 * 回调处理微信返回的code
	 */
	public function actionDealCode(){ 

		$wechat = new BaseWechat();
		$info = $wechat->getOauthAccessToken();
	
		if (empty($info)) {
		    throw new Exception("无效参数");
		}
		$member = Member::find()->where(['wx_openid'=>$info['openid']])->one();		
		Yii::$app->session->set('wx_openid', $info['openid']);
		if (empty($member)){
			//新增用户
		    $regInfo = $wechat->getOauthUserinfo($info['access_token'],$info['openid']);
		
			Yii::$app->session->set('regInfo', json_encode($regInfo));
			//$model = new BindRegisterForm();
			//跳转绑定旧用户页面
			//return $this->render('bind',['model' => $model]);
			return $this->redirect(Url::to(['wx/register']));

		}else{
		    if (Yii::$app->getUser()->login($member)) {
		      $url= Url::previous();
		      return $this->redirect($url);
		    }
		}
		
	}    	
   
	/**
	 *
	 * 绑定手机号
	 */
	public function actionBindRegister(){
	    $regInfo = json_decode(yii::$app->session->get('regInfo'),true);
	    if (empty($regInfo)) {
	        throw new Exception("无效参数");
	    }
	    if(\Yii::$app->request->isPost){
	        $model = new BindRegisterForm();
	        if ($model->load(Yii::$app->request->post())) {
	            if (Yii::$app->request->isAjax) {
	                Yii::$app->response->format = Response::FORMAT_JSON;
	                return ActiveForm::validate($model);
	            }
	            if ($user = $model->bindRegister($regInfo['openid'])) {
	                //重新登陆用户
	                yii::$app->session->remove('regInfo');
	                if (Yii::$app->getUser()->login($user)) {
	                    return $this->goHome();
	                }
	            }
	        }
	    }
	    
	}
	
	/**
	 *
	 *直接注册
	 */
	public function actionRegister(){
	    
	    $regInfo = json_decode(yii::$app->session->get('regInfo'),true);
	
	    if (empty($regInfo)) {
	        throw new Exception("无效参数");
	    }
	    $member = new Member();
	    $member->username =Tools::emoji_encode( $regInfo['nickname']);
	    $member->wx_openid = $regInfo['openid'];
	    $member->avatarUrl = $regInfo['headimgurl'];
	    if ($regInfo['sex']==1){
	        $member->sex = '男';
	    }else{
	        $member->sex = '女';
	    }
	    $flag = $member->save();
	    $pid=yii::$app->request->get('pid',yii::$app->session->get('pid'));
	    if(!empty($pid)){
	        $distribute=new DistributeLogic();
	        $distribute->FristLeader($pid, $member['id']);
	    }
	    if($flag){
	        Yii::$app->user->login($member, 3600 * 6 );
	        yii::$app->session->remove('regInfo');
	        $url= Url::previous();
	        return $this->redirect($url);
	    }else{
	        throw new Exception(current($member->getErrors()));
	    }
	}
	
	public function actionSign(){
	    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	    if($_SERVER["HTTP_REFERER"]){
	        $url = $_SERVER["HTTP_REFERER"];
	    }
	    
	    $wechat = new BaseWechat();
	    return Json::encode($wechat->getJsSign($url));
	}
    
}
