<?php
/*
 * Created by PhpStorm.
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-09-03 17:43
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace api\modules\v1\controllers;
use api\common\controllers\Controller;
use api\modules\v1\models\BindMobileForm;
use common\models\WithdrawalSearch;
use api\modules\v1\models\Member;
use api\modules\v1\models\UnbindMobileForm;
use common\logic\CouponLogic;
use common\models\AccountLogSearch;
use common\models\Order;
use common\models\ShopUser;
use common\modules\coupon\models\CouponItem;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use api\modules\v1\models\ResetPayPasswordForm;
use common\models\Shop;
class MemberController extends Controller
{
    public function behaviors()
    {
       return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => QueryParamAuth::className(),
                'tokenParam' => 'token',
                'optional' => [
                  
                    
                ]
            ]
        ]);
    }

    /**
     *签到
     */
    public function actionSign(){
    //TODO
     return ['status'=>1,'msg'=>'签到成功'];
    }
    /**
     * 账户流水列表
     */
    public function actionAccountLog(){  
        $data=Yii::$app->request->post();
    	$data['member_id']=Yii::$app->user->id;
    	$searchModel = new AccountLogSearch();    	
    	$dataProvider = $searchModel->search($data);  	
    	return $dataProvider;
    }
    /**
     * 账户提现记录
     */
    public function actionWithdrawalLog(){
        $data=Yii::$app->request->post();
        $data['m_id']=Yii::$app->user->id;
        $searchModel = new WithdrawalSearch();
        $dataProvider = $searchModel->search($data);
        return $dataProvider;
    }

   /**
    *  获取用户账户基本概况，积分、余额、优惠券数量
    *
    */
    public function actionIndex(){
        $memberId=Yii::$app->user->id;
        $member = Member::findOne($memberId);
        $data=array();
        $data['score']=$member['score'];
        $data['user_money']=$member['user_money'];
        $data['set_pay_pwd']=$member['pay_pwd']!=''?1:0;
        $data['set_mobile']=$member['mobile']!=''?1:0;
        $now=time();
        $data['coupon_num'] = CouponItem::find()->andWhere(['mid'=>$memberId,'use_time'=>null])->joinWith('coupon',false,'RIGHT JOIN')->onCondition(['status'=>1])->andOnCondition(['>','use_end',$now])->count();
        $data['num']['ready_pay']=Order::find()->where(['status'=>1,'m_id'=>$memberId,'is_del'=>0])->count();
        $data['num']['ready_shipping']=Order::find()->where(['status'=>2,'m_id'=>$memberId,'is_del'=>0])->count();
        $data['num']['ready_confirm']=Order::find()->where(['status'=>3,'m_id'=>$memberId,'is_del'=>0])->count();
        $data['num']['ready_evalute']=Order::find()->where(['status'=>4,'m_id'=>$memberId,'is_del'=>0])->count();
        $user=ShopUser::find()->alias('u')->joinWith(['shop s'],true,'LEFT JOIN')->where(['m_id'=>yii::$app->user->id,'s.status'=>1,'u.status'=>1])->one();
        $data['has_shop']=!empty($user)?1:0;
        $data['is_distribut']=$member['is_distribut'];
        return ['item'=>$data];
    }
    
    
    public function actionInfo(){
    	$memberId=Yii::$app->user->id;
    	$member = Member::findOne($memberId);
    	$exchange=Yii::$app->config->get('site_credits_exchange');
    	if($member['score']){
    	    $intergal_money=$member['score']/$exchange;
    	}else{
    	    $intergal_money=0;
    	}
    	$info=Yii::$app->config->get('site_credits_exchange').'积分可兑换1块钱';
    	return ['item'=>$member,'info'=>$info];
    }
    public function actionScore(){
        $memberId=Yii::$app->user->id;
        $member = Member::findOne($memberId);
        $exchange=Yii::$app->config->get('site_credits_exchange');
        if($member['score']){
            $intergal_money=$member['score']/$exchange;
        }else{
            $intergal_money=0;
        }
        $intergal_money>0?$status=1:$status=0;
        return ['intergal_money'=>$intergal_money,'score'=>$member['score'],'status'=>$status];
    }
    /**
     * 优惠券列表
     */
    public function actionCoupons(){
        $mid = Yii::$app->user->id;
        $params = Yii::$app->request->post();
        $couponLogic = new CouponLogic();
        $lists = $couponLogic->getCoupons($mid, $params);
        return $lists;
    }
    /**
     * 优惠券详情
     * @return
     */
    public function actionItemInfo(){
        if (Yii::$app->request->isPost) {
            $id=Yii::$app->request->post('id');
            $mid = Yii::$app->user->id;
            $couponItem = CouponItem::find()->alias('a')->joinWith('coupon')->where(['mid'=>$mid,'a.id'=>$id])->one();
            $couponItem->password = Yii::$app->security->decryptByPassword(base64_decode($couponItem->password),$couponItem->code);
            if (empty($couponItem)) {
                return ['status'=>0,'msg'=>'参数错误'];
            }else{
                return ['status'=>1,'msg'=>$couponItem];
            }
        }
    }
    /**
     * 绑定手机号
     * @return 
     */
    public function actionBindMobile(){
        if (Yii::$app->request->isPost) {
            $params = Yii::$app->request->post();
            $member = Yii::$app->user->identity;
            if (!empty($member->mobile)) {
                return ['status'=>0,'msg'=>'该用户已绑定手机号，请先解绑！'];
            }
            $mid = Yii::$app->user->id;
            $model = new BindMobileForm();
            if ($model->load($params,'')) {                
                return $model->bindMobile($mid);
            }else{
                return ['status'=>0,'msg'=>current($model->getFirstErrors())];
            }
        }
    }
   
    /**
     * 绑定手机号
     * @return
     */
    public function actionUnbindMobile(){
        if (Yii::$app->request->isPost) {
            $mid = $mid = Yii::$app->user->id;
            $member = Yii::$app->user->identity;
            if (empty($member->mobile)) {
                return ['status'=>0,'msg'=>'该用户未绑定手机号，请先绑定手机号！'];
            }
            $model = new UnbindMobileForm();
            if ($model->load(Yii::$app->request->post(),'')) {               
                return $model->unbindMobile($mid);                
            }else{
                return ['status'=>0,'msg'=>current($model->getFirstErrors())];
            }
        }
    }
    /**
     * 
     * 重置支付密码
     */
    public function actionResetPaypwd(){
        if (Yii::$app->request->isPost) {
            $mid = $mid = Yii::$app->user->id;
            $member = Yii::$app->user->identity;
            if (empty($member->mobile)) {
                return ['status'=>0,'msg'=>'该用户未绑定手机号，请先绑定手机号！'];
            }
            $model = new ResetPayPasswordForm();
            if ($model->load(Yii::$app->request->post(),'')) {
                return $model->resetpaypassword($mid);
            }else{
                return ['status'=>0,'msg'=>current($model->getFirstErrors())];
            }
        }
    }
    
    
    
}