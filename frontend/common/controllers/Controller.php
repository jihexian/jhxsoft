<?php
/**
 * User: Vamper
 * Date: 20181107
 */

namespace frontend\common\controllers;
use yii;
use common\components\weixin\BaseWechat;
use yii\helpers\Url;
use common\models\Member;
class Controller extends \yii\web\Controller
{
    
    public function init(){
        parent::init();
        $is_distribut = yii::$app->session->get('is_distribut');
        //已登录，判断是不是分销商
        if(!\Yii::$app->user->isGuest&&!isset($is_distribut)){
            $mid=Yii::$app->user->id;
            $member=Member::find()->andWhere(['id'=>$mid])->asArray()->one();
            $is_distribut=$member['is_distribut']==1?TRUE:FALSE;
            yii::$app->session->set('is_distribut',$is_distribut);
        }
        //没登录且通过分享链接进入，把pid存到session
        $pid=yii::$app->request->get('pid','');
        if (!empty($pid)&&\Yii::$app->user->isGuest) {
            yii::$app->session->set('pid', $pid);
            //判断pid是否有用户存在
            $member=Member::find()->where(['id'=>$pid])->one();
            if(empty($member))
                yii::$app->session->remove('pid');
        }
        if($this->is_weixin()){
            if (Yii::$app->user->isGuest){
                $wechat = new BaseWechat();
                $url = Yii::$app->params['domain'].'/'.Url::to('wx/deal-code');
                $str = $wechat->getOauthRedirect($url);
                $this->redirect($str);
            }
        }else{
//             if (Yii::$app->user->isGuest){
//                 $member= \frontend\models\Member::findOne(3);
//                 Yii::$app->user->login($member);      
//             }
            
        }
    }
    
    //判断是否是微信端
    private function is_weixin(){
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }
    
}