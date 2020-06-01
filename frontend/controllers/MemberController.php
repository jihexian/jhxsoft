<?php
/**
 * 
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年11月16日 下午3:04:21
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace frontend\controllers;
use Yii;
use yii\data\ActiveDataProvider;
use frontend\common\controllers\Controller;
use common\models\Member;
use common\models\Shop;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\Plugin;
use common\models\Recharge;
use common\models\Collection;
use common\models\CollectionSearch;
use common\models\AccountLogSearch;
use common\models\CollectionShopSearch;
use common\models\ShopUser;
use common\models\SmsLog;
use frontend\models\RegisterForm;
use yii\web\Response;
use yii\widgets\ActiveForm;
use frontend\models\ResetPasswordForm;
use frontend\models\BindMobileForm;
use yii\helpers\ArrayHelper;
use yii\rest\Serializer;
use common\helpers\Tools;
use frontend\models\ResetPayPasswordForm;
use common\models\ShopCategory;
use frontend\models\UnbindMobileForm;

use frontend\models\BindInfoForm;

use common\models\WithdrawalSearch;
use common\models\Withdrawal;
use common\logic\WithdrawalLogic;
use function GuzzleHttp\json_encode;
use common\models\RegisterShop;


/**
 * Member controller.
 */
class MemberController extends Controller
{
//     public function init(){
//         if (empty(Yii::$app->user->identity->mobile))
//         parent::init();
//     }
    
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [                   
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['reset-password'],
                        'roles' => ['?','@'],
                    ],
                ],
            ],
        ];
    }
    /**
     * 我的主页.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $memberId=Yii::$app->user->id;
        $member = Member::findOne(['id'=>$memberId]);
        $user=ShopUser::find()->where(['m_id'=>$memberId])->one();
        $shop=Shop::find()->where(['id'=>$user['shop_id']])->one();
        return $this->render('index',[           
            'item'=>$member,
            'shop'=>$shop,

        ]);
    }
    
    public function actionResetPayPassword(){
        $m_id=Yii::$app->user->id;
        $data=Member::find()->where(['id'=>$m_id])->asArray()->one();
        $model = new ResetPayPasswordForm();
        if(\Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }
                if ($user = $model->resetpaypassword()) {
                    return $this->goHome();
                }
            }
        }
        return $this->render('reset_pay_password', ['model' => $model,'data'=>$data]);
    }
    
    
    
    public function actionAnquan(){
        return $this->render('anquan');
    }
    /**
     * @desc 申请店铺
     * @return string
     */
    public function actionApply(){
        $model=ShopCategory::find()->where(['status'=>1])->orderBy('sort asc')->all();
        return $this->render('apply',[
            'model'=>$model,
        ]);
    }
    public function actionApplyPost(){
        $data=yii::$app->request->post();
        $shop=Shop::findOne(['member_id'=>yii::$app->user->id,'mobile'=>$data['mobile']]);
        $truncation=yii::$app->db->beginTransaction();
        try {
            if(!empty($shop)){
                if(!$shop->delete()){
                    $truncation->rollBack();
                    return json_encode(['status'=>0,'msg'=>'操作失败']);
                }
            }
            $shopcategory=ShopCategory::find()->where(['name'=>\Yii::$app->request->post('category_id')])->one();
            $data['member_id']=yii::$app->user->id;
            $data['category_id']=$shopcategory['id'];
            $register=new RegisterShop();
            if(!$register->load($data,'')||!$register->signup()){
                $truncation->rollBack();
                return json_encode(current($register->getFirstErrors()));
            }
            $truncation->commit();
            return json_encode(['status'=>1,'msg'=>'申请成功']);
            
        } catch (\Exception $e) {
            $truncation->rollBack();
            return json_encode(['status'=>0,'msg'=>'操作失败']);
        }
    }
     
    private function checksms($mobile,$code){
        $sms = SmsLog::find()->where(['mobile'=>$mobile,'scene'=>1,'code'=>$code])->one();
        if (empty($sms)) {
            return ['status'=>0,'msg'=>'验证码错误！'];
        }else{
            if ($sms->created_at>time()+60*10*1000) {
                return ['status'=>0,'msg'=>'验证码过期，请重新获取！'];
                
            }else{
                return ['status'=>1,'msg'=>'成功'];
            }
        }
    }
  
    
/*     public function actionApply(){
        if(Yii::$app->request->post()){
            $model=new ShopApply();
            $model->load(Yii::$app->request->post());
            $model->save();
            if($model->hasErrors()) {
                throw new \Exception('操作失败');
            }
  
        }else{
            $model=new ShopApply();
            
            return $this->render('apply',[
                'model'=>$model,
            ]);
        }
    } */ 
    /**
     *@desc 积分流水
     */
    public function actionScoreLog(){
        $memberId=Yii::$app->user->id;
        $member = Member::findOne(['id'=>$memberId]);
        $data=Yii::$app->request->post();
        $data['member_id']=$memberId;
        $data['change_type']=1;
        $data['num']=10;
        $searchModel = new AccountLogSearch();
        $dataProvider = $searchModel->search($data);  
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $sort->defaultOrder = array('id'=>SORT_DESC);
        $dataProvider->setSort($sort);
        $log=$dataProvider->getModels();
        if(Yii::$app->request->isPost){
            $serializer = new Serializer();
            $pagecount=$dataProvider->getTotalCount();
            $pagecount=ceil($pagecount/$data['num']);
            return Json::encode(ArrayHelper::merge(['items'=>$serializer->serialize($dataProvider)], ['pagecount'=>$pagecount]));
        }else{
            return $this->render('score_log',[
                    'score'=>$member['score'],
                    'log'=>$log,
            ]);
        } 
    }
    
    /**
     * 钱包
     * @return string
     */ 
    public function actionWallet(){

       $m_id = Yii::$app->user->id;
       $member = Member::findOne(['id'=>$m_id]);  
        
         return $this->render('wallet',[
            'money'=>$member['user_money'],     
        ]); 
    }
    
    /**
     * 提现
     */
    
    public function actionWithdrawal(){

        if(yii::$app->request->isPost){
            $data=array();
            $wx_openid=Yii::$app->session->get('wx_openid');
            $post=yii::$app->request->post();
            if (!empty($wx_openid) && strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
                $client='weixin';
                $payment=Plugin::find()->where(['id'=>'weixin','status'=>1])->one();
                if(empty($payment)){
                    return yii::$app->session->set('error','请先到后台插件控制台开启微信支付功能');
                }
            }else{
                $client='normal';
                $payment=array();
                $data['bank_name']=$post['Withdrawal']['bank_name'];
                $data['bank_card']=$post['Withdrawal']['bank_card'];
                $data['realname']=$post['Withdrawal']['realname'];
            }
            
            $money=$post['Withdrawal']['pay_amount'];
            $logic = new WithdrawalLogic();
            $mid = yii::$app->user->id;
            $data = $logic->apply($money, $mid, $client,0,$data);
            if($data['status']==0){
               yii::$app->session->set('error',$data['msg']);
               return $this->goBack();
                
            }else{
                yii::$app->session->set('success','申请成功，等待管理员审核');
                return $this->redirect(['member/wallet']);
            }
  
        }else{
            $m_id = Yii::$app->user->id;
            $model=new Withdrawal();
            $model->setScenario('bank_create');
            $member = Member::findOne(['id'=>$m_id]);
            $wx_openid=Yii::$app->session->get('wx_openid');
            $searchModel = new WithdrawalSearch();
            $con['num']=8;
            $con['m_id']=$m_id;
            $dataProvider = $searchModel->search($con);
            $log=$dataProvider->getModels();
     
            if (!empty($wx_openid) && strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
                return $this->render('withdrawal',[
                        'member'=>$member,
                        'model'=>$model,
                        'log'=>$log,
                ]);
            }else{
                return $this->render('withdrawal_normal',[
                        'member'=>$member,
                        'model'=>$model,
                        'log'=>$log,
                ]);
            }  
        }
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
    
    public function actionRecharge(){
        $model=new Recharge();
        $payment=Plugin::find()->select(['id','name'])->indexBy(['name'])->where(['and',['type'=>'payment','status'=>1,'scene'=>2],['!=','id','money']])->asArray()->all();
        
        if(yii::$app->request->isPost){
            $data=yii::$app->request->post();
            $model->load($data);
            $model->m_id=yii::$app->user->id;
            $model->order_no='re_'.Tools::get_order_no();
            if($model->save()){
                $this->redirect(['payment/recharge','payment_code'=>$model->payment_code,'id'=>$model->id]);
            }
        }
        return $this->render('recharge',[
                'model'=>$model,
                'payment'=>$payment
                
        ]);   
    }
    
    /**
      *@desc 现金流水
     */
    public function actionMoneyLog(){
        $memberId=Yii::$app->user->id;
        $member = Member::findOne(['id'=>$memberId]);
        $data=Yii::$app->request->post();
        $data['member_id']=$memberId;
        $data['num']=10;
        $data['change_type']=2; 
        $searchModel = new AccountLogSearch();
        $dataProvider = $searchModel->search($data);
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $sort->defaultOrder = array('id'=>SORT_DESC);
        $dataProvider->setSort($sort);
        $log=$dataProvider->getModels();
        if(Yii::$app->request->isPost){
            $serializer = new Serializer();
            $pagecount=$dataProvider->getTotalCount();
            $pagecount=ceil($pagecount/$data['num']);
            return Json::encode(ArrayHelper::merge(['items'=>$serializer->serialize($dataProvider)], ['pagecount'=>$pagecount]));
        }else{
            return $this->render('money_log',[
                    'money'=>$member['user_money'],
                    'log'=>$log,
            ]);
        } 
    } 
    /**
     * 商品收藏
     */
    public function actionCollectionProduct(){ 
        $data = Yii::$app->request->post();
        $member_id = Yii::$app->user->id;
//         $member_id = 1;
        $data['member_id'] = $member_id;
        $searchModel = new CollectionSearch();
        $dataProvider = $searchModel->search($data);
        $collections = $dataProvider->getModels();
//         foreach ($collections as $key=>$vo){
//             $product=$vo['product'];
//             $product->imagesAddPrefix();
          
//         }
//         print_r($collections);
        return $this->render('collection_product',[
            'collections'=>$collections,
        ]);
    }
    /**
     * 店铺收藏
     */
    public function actionCollectionShop(){
        $data = Yii::$app->request->post();
        $member_id = Yii::$app->user->id;
//         $member_id=1;
        $data['member_id'] = $member_id;
        $searchModel=new CollectionShopSearch();
        $dataProvider=$searchModel->search($data);
        
        $model=$dataProvider->getModels();
//         print_r($model);
        return $this->render('collection_shop',[
            'model'=>$model,
        ]);
    }
    /**
     * 密码重置
     */
    public function actionResetPassword(){
        $model = new ResetPasswordForm();
        if(\Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }
                if ($user = $model->resetpassword()) {
                        return $this->goHome();
                }
            }
        }
        return $this->render('reset_password', ['model' => $model]);
    }
    
 
    public function actionBindMobile(){
        $mid = Yii::$app->user->id;
        $model = new BindMobileForm();
        if(\Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }
                if ($user = $model->bindMobile($mid)) {
                    return $this->goHome();
                }
            }
        }
        return $this->render('bind-mobile', ['model' => $model]);
    }
    public function actionUnbindMobile(){
        $mid = Yii::$app->user->id;
        $model = new UnbindMobileForm();
        if(\Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }
                if ($user = $model->unbindMobile($mid)) {
                    return $this->goHome();
                }
            }
        }
        return $this->render('unbind-mobile', ['model' => $model]);
    }
   
    public function actionBindInfo(){
        
        $model = new BindInfoForm();
        $mid=yii::$app->user->id;
        $wx_openid=yii::$app->session->get('wx_openid');
        if(\Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post(),'')) {
               $flag=$model->bindInfo($mid,$wx_openid);
                if ($flag['status']==1) {
                        Yii::$app->response->format = 'json';
                        return ['status'=>1,'msg' =>'成功' ];  
                }else{
                        if (Yii::$app->request->isAjax) {
                            Yii::$app->response->format = 'json';
                            return ['status'=>0,'msg' =>current($model->getFirstErrors())];
                        }
              
                }
            }
        }
     
    } 


    public function actionLogout(){
        Yii::$app->user->logout();  
        return $this->goHome();
        
    }
   

    /**
     * 个人资料
     */
    public function actionInfo(){
        $mid = Yii::$app->user->id;
        if(\Yii::$app->request->isPost){
            if (Yii::$app->request->isAjax) {
                $data=Yii::$app->request->post();
                $member=Member::find()->andWhere(['id'=>$mid])->one();
                $member->load($data,'');
                $member->save();
                return Json::encode(['status'=>1,'msg'=>'更改成功']);
            }
        }
        $model=Member::find()
                ->andWhere(['id'=>$mid])
                ->asArray()
                ->one();
        return $this->render('info',[
                'model'=>$model,
        ]);
    }
    /**
     * 设置
     */
    public function actionAccount(){
        return $this->render('account');
    }
    /**
     * 优惠券列表
     */
    public function actionCoupons(){
        $mid = Yii::$app->user->id;
        
    }
    /**
     * 生成小程序指定页面带参数的二维码
     * @param $access_token String 授权token
     * @param $path String 生成的页面路径
     * @param $savePath String 生成二维码后保存的路径，绝对路径
     * @param $width int 二维码的宽度（正方所以只需宽度）
     * @param $uid int 如果如果是生成分销商的，可以传入uid来标记图片名
     * @return array 返回包含保存后的二维码名称（不包含路径）
     */
    private function createQrCode($access_token,$path = '',$savePath = '',$width = 300,$uid = 1){
        if(!$access_token || !$path || !$savePath){
            return false;
        }        
        // $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$access_token;
 
        // $data = array('path'=>'weixinmao_cars/pages/carDetails/carDetails', 'scene'=>'goodsNo='.$car_id,"width"=> 300);
        $url="https://api.weixin.qq.com/wxa/getwxacode?access_token=".$access_token;
        $data =array('path'=> $path,"width"=> $width);
        $data = json_encode($data);
        $result = $this -> httpRequest($url, $data);

        if(!isset($result['errcode'])){
            $path = $savePath;
            if (!file_exists($path)) {
                mkdir($path);
            }
            $name = 'qrcode'.'_'.$uid.'_'.time();
            file_put_contents($path.$name.'.jpg',$result);
            $arr = array('code'=>1,'msg'=>$name.'.jpg');
        }else{
            $arr = array('code'=>0,'msg'=>'生成二维码失败');
        }
        
        return($arr);
    }


    /**
     * 拼团
     */
    public function actionPintuan(){
        return $this->render('pintuan',[
            'status'=>isset($data['status'])?$data['status']:'',
        ]);
    }

}
