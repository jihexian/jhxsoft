<?php
namespace api\modules\v1\controllers;
use yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\filters\auth\QueryParamAuth;
use common\helpers\Tools;
use common\models\Member;
use common\models\Shop;
use common\models\ShopCommissionLog;
use common\models\ShopWithdraw;
use api\common\controllers\Controller;
use api\modules\v1\models\Order;
use common\models\ShopAccoutLogSearch;
use api\modules\v1\models\OrderSearch;
use yii\web\HttpException;
use plugins\wxMini\WxMini;
use common\models\ShopRecharge;
use common\logic\OrderLogic;
use common\logic\CommentLogic;
use common\logic\ShopWithdrawLogic;
use common\models\OrderLog;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\ShopUser;
use Hashids\Hashids;
class SellerController extends  Controller
{
 
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => QueryParamAuth::className(),
                'tokenParam' => 'token',
                'optional' => [
                    'index',
                ]
            ]
        ]);
    }
    

    public function actionInfo(){
      //今日营业额
        $shop=$this->checkShop();
        $shop_id=$shop->id;
        $total=Order::find()->where(['payment_status'=>1,'status'=>[2,3,4,5],'shop_id'=>$shop_id])->andFilterCompare('paytime', strtotime(date('Y-m-d')), '>=')->andFilterCompare('paytime', (strtotime(date('Y-m-d')) + 86400), '<')->sum('pay_amount');
        $order_num=Order::find()->andWhere(['payment_status'=>1,'status'=>[2,3,4,5],'shop_id'=>$shop_id])->andFilterCompare('paytime', strtotime(date('Y-m-d')), '>=')->andFilterCompare('paytime', (strtotime(date('Y-m-d')) + 86400), '<')->count();
        
        return [
            'status'=>1,
            'item'=>[
                'total'=>$total?$total:0.00,
                'order_num'=>$order_num,
                'shop'=>['name'=>$shop->name,'logo'=>$shop->logo,'address'=>$shop->address,'score'=>$shop->score]
            ]];
    }

    /**
     * 扫描查看订单详情
     * @return array[]|\yii\db\ActiveRecord[][]
     */
    public function actionCheck(){
        $shop=$this->checkShop();
        $data=array();
        $data['shop_id']=$shop->id;
        $searchModel = new OrderSearch();
        $order_no=Yii::$app->request->post('order_no');
        if (stripos($order_no,'pn_') !== false){  //组合支付处理
            $data['parent_sn']=$order_no;
        }else{
            $data['order_no']=$order_no;
        }
        $dataProvider = $searchModel->search($data);
        return $dataProvider;
    }
   /**
    * 核销电子票
    * @return number[]|string[]
    */
    
 /* 获取该赠送的学分$score
    判断商家学分是否$shop->score>$score
    如果不大于$score，报错，提示商家充值
    如果大于，更改订单状态 */
    public function actionCheckOut(){
        $shop=$this->checkShop();
        $shop_id=$shop->id;
        $id=yii::$app->request->post('order_id');
        $transaction=yii::$app->db->beginTransaction();
        try {
            $order=Order::findOne(['id'=>$id,'shop_id'=>$shop_id]);
            $now=time();
            if($now<$order['data']['use_start_time']||$now>$order['data']['use_end_time']){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'已不在使用时间范围内'];
            }
            if(empty($order)||$order->status==4){  
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'没有权限'];
            }
            $order->status=4;
            $order->save();
            if($order->hasErrors()){ 
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'操作失败'];
            }
            //不用评价，默认好评
            foreach ($order['orderSku'] as $vo){
                $data=array();
                $data['uid']=$order->m_id;
                $data['order_sku_id']=$vo['id'];
                $data['total_stars']=5;
                $comment=new CommentLogic();
                $su= $comment->addComment($data);
                if($su['status']!=1){
                    $transaction->rollBack();
                    return ['status'=>0,'msg'=>$su['msg']];
                }
            }    
            $user=ShopUser::findOne(['m_id'=>yii::$app->user->id,'shop_id'=>$shop->id]);
               
                if($user){
                $log=new OrderLog();
                $log->order_no=$order['order_no'];
                $log->order_status=5;
                $log->shipping_status=1;
                $log->pay_status=1;
                $log->status_desc='工作人员'.$user['username'].'核销了订单';
                $log->user_id=$user['id'];
                $log->action_user=$user['username'];
                $log->shop_id=$shop_id;
                $log->save();
                if($log->hasErrors()){
                    $transaction->rollBack();
                    return ['status'=>0,'msg'=>'操作失败'];
                }
             }
         /* $score=0;
            foreach($order['orderSku'] as $vo){
                $score+=$vo['product']['score'];
            }
            if($shop->score<$score){
                $transaction->rollBack();
                  return ['status'=>0,'msg'=>'账户积分不足，请先充值'];
            }
            $shop->score-=$score;
            $shop->save();
            if($shop->hasErrors()){ 
                $transaction->rollBack();
                 return ['status'=>0,'msg'=>current($shop->getFirstErrors())];
            } */
            $transaction->commit();  
            return ['status'=>1,'msg'=>'核销成功'];
        }catch (Exception $e) {  
            $transaction->rollBack();
            return ['status'=>0,'msg'=>$e->getMessage()];
        }
        


    }

    /**
     * 店铺资金概况
     * @return string
     */
    public function actionRecord(){
        $shop=$this->checkShop();
        $id=$shop->id;
        //总营业额
        $total=Order::find()->where(['payment_status'=>1,'status'=>[2,3,4,5],'shop_id'=>$id])->sum('pay_amount');
        //待结算
        $waiting=Order::find()->where(['payment_status'=>1,'status'=>[2,3,4],'shop_id'=>$id])->sum('pay_amount');
        //店铺提现
        $finish=ShopWithdraw::find()->where(['shop_id'=>$id,'status'=>1])->sum('money');
        $ready=ShopWithdraw::find()->where(['shop_id'=>$id,'status'=>0])->sum('money');
        //平台服务费
        $service=ShopCommissionLog::find()->where(['shop_id'=>$id])->sum('percentage');
        //店铺流水
        $shopAccout=new ShopAccoutLogSearch();
        $data=yii::$app->request->post();
        $data['ShopAccoutLogSearch']['shop_id']=$id;
        $dataProvider=$shopAccout->search($data);
        return array_merge(Yii::createObject($this->serializer)->serialize($dataProvider),[
            'total'=>$total?$total:0.00,
            'waiting'=>$waiting?$waiting:0.00,
            'finish'=>$finish?$finish:0.00,
            'ready'=>$ready?$ready:0.00,
            'withdrawable'=>isset($shop['money'])?$shop['money']:0.00,
            'service'=>$service]); 
    }
    /**
     * 店铺管理模块订单列表
     * @return \yii\data\ActiveDataProvider
     */
    public function actionOrder(){
        $shop=$this->checkShop();
        $order=new OrderSearch();
        $data=yii::$app->request->post();
        $data['shop_id']=$shop->id;
        $dataProvider=$order->search($data);
        return $dataProvider;
    }
    /**
     * 订单详情
     * @return number[]|\yii\db\ActiveRecord[]|array[]|NULL[]|number[]
     */
    public  function actionOrderInfo(){
        $shop=$this->checkShop();
        $orderId = Yii::$app->request->post('order_id');
        $data=Order::find()->alias('o')->where(['o.shop_id'=>$shop->id,'o.id'=>$orderId])->joinWith('orderSku')->asArray()->one();
        if(!empty($data)){
            return ['item'=>$data,'status'=>1];
        }else{
            return ['status'=>0];
        } 
    }
    
    /**
     * 实现获取充值积分比列
     * @return number[]
     */
    public function actionExchangeScore(){
        return ['item'=>yii::$app->config->get('site_credits_exchange')];
    }
    /**
     * 会员充值
     * @throws \Exception
     * @return number[]|mixed[]|number[]|number[]|string[]
     */
    public function actionRecharge ()
    {
        $shop=$this->checkShop();
        $money = Yii::$app->request->post('money');
        if (empty($money)) {
            throw new \Exception('金额不能为空');
        }  
        $recharge = new ShopRecharge();
        $recharge->pay_amount = $money;
        $recharge->score=$money*yii::$app->config->get('site_credits_exchange');
        $recharge->shop_id=$shop->id;
        $recharge->payment_code = 'wxMini'; // 'payment_code'=>'wxMini','payment_name'=>'微信小程序支付'
        $recharge->payment_name = '微信小程序支付';
        $recharge->order_no = 'jf_' . Tools::get_order_no(); 
        if (! $recharge->save()) {
            return [
                'status' => 0,
                'msg' => current($recharge->getFirstErrors())
            ];
        }
        // 获取订单总金额
        $con = array();
        $con['pay_amount'] = $recharge->pay_amount;
        $con['order_no'] = $recharge->order_no;
        $token = Yii::$app->request->post('token');
        $user = Member::find()->where([
            'access_token' => $token
        ])->one();
        if ($user['xcx_openid'] &&
            strstr($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
                $pay = new WxMini();
                $notifyUrl = getenv('SITE_URL') . Url::to([
                    'response/notify'
                ]);
                $data = $pay->xcx($con, $user['xcx_openid'], $notifyUrl);
                $data = json_decode($data);
                return [
                    'status' => 1,
                    'items' => $data,
                    'is_cash' => 1
                ];
            } else {
                return [
                    'status' => 0,
                    'msg' => '未知错误',
                    'open_id' => $user['xcx_openid']
                ];
            }
    }
    
   /**
    * type:1、订单 2到店支付 3提现 4积分
    * @return \yii\data\ActiveDataProvider
    */
    public function actionShopAccount(){
        $shop= $this->checkShop();
        $data=array();
        $data['type']=yii::$app->request->post('type');
        $data['shop_id']=$shop->id;
        $model=new ShopAccoutLogSearch();
        $dataPrvidate=$model->search($data);
        return $dataPrvidate;
        
    }
    /**
     * 余额提现
     * @return number[]|string[]
     */
    public function actionWithdrawal ()
    {
        $shop= $this->checkShop();
        $money = yii::$app->request->post('money', 0);
        $m_id = yii::$app->user->id;
        $card_id = yii::$app->request->post('card_id', 0);
        $client = yii::$app->request->post('client', 'wxMini');
        if (empty($money)) {
            return [
                'status' => 0,
                'msg' => '金额不能为0'
            ];
        }
        $logic = new ShopWithdrawLogic();
        $data = $logic->apply($money, $m_id,$client,$card_id);
        return $data;  
    }
    
    /**
     * 店铺员工列表 
     * @return string
     */
    public function actionUser(){
        $shop= $this->checkShop();
        $query=ShopUser::find()->where(['shop_id'=>$shop['id']]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ],
        
        ]);
        return $dataProvider;
    }
    /**
     * 添加员工
     * @return number[]|string[]
     */
    public function actionUserAdd(){
        $shop= $this->checkShop();
        $model= new ShopUser();
        $model->setScenario('register');
        $model->shop_id=$shop['id'];
        if ($model->load(Yii::$app->request->post(),'')&&$model->create()) {
              return ['status'=>1,'msg'=>'操作成功'];
        }else{
            return ['status'=>0,'msg'=>current($model->getFirstErrors())];
        }
    }
    /**
     * 店员资料
     * @return number[]|\api\modules\v1\models\ShopUser[]|NULL[]
     */
    public function actionUserInfo(){
        $shop= $this->checkShop();
        $id=yii::$app->request->post('id');
        $user=ShopUser::findOne(['id'=>$id,'shop_id'=>$shop['id']]);
        return ['status'=>1,'item'=>$user];
    }
    /**
     * 删除店员
     * @return number[]|string[]
     */
    public function actionUserDel(){
        $shop= $this->checkShop();
        $id=yii::$app->request->post('id');
        $user=ShopUser::findOne(['id'=>$id,'shop_id'=>$shop['id']]);
        if($user['m_id']==yii::$app->user->id){
            return ['status'=>0,'msg'=>'不能删除自己'];
        }
        if($user->delete()){
            return ['status'=>1,'msg'=>'删除成功'];
        }else{
            return ['status'=>0,'msg'=>'删除失败'];
        }
    }
    /**
     * 重置密码
     */
    public function actionResetPassword()
    {
        $shop= $this->checkShop();
        $id=yii::$app->request->post('id');
        $model =ShopUser::findOne(['id'=>$id]);
        $model->scenario = 'resetPassword';
        if($model->load(Yii::$app->request->post(),'') && $model->save()){
            return ['status'=>1,'msg'=>'密码修改成功'];
        }else{
            return ['status'=>0,'msg'=>'操作失败'];
        } 
    }
    
    /**
     * 生成店员绑定邀请码
     * @return number[]|string[]
     */
    public function actionInvitation(){
        $id=yii::$app->request->post('id');
        $hashids=new Hashids('Jihexiandjdjdjfy77784',8);
        $code=$hashids->encode($id);
        return ['status'=>1,'item'=>$code];
    }
    
   /**
    * 店员绑定店铺账号接口
    * @return number[]|string[]
    */
    public function actionAccept(){
        $code=yii::$app->request->post('code');
        $hashids=new Hashids('Jihexiandjdjdjfy77784',8);
        $id=$hashids->decode($code);
        $m_id=Yii::$app->user->id;
        $user=ShopUser::findOne(['id'=>$id]);
        if(empty($user)||$user['m_id']){
            return ['status'=>0,'msg'=>'请码已失效'];
        }
        $user->m_id=$m_id;
        if($user->save()){
            return ['status'=>1,'msg'=>'绑定成功'];   
        }else{
            return ['status'=>0,'msg'=>current($user->getFirstErrors())];
        }
        
    }
    
    
    private function checkShop(){
        $m_id=yii::$app->user->id;
        $user=ShopUser::find()->alias('u')->joinWith(['shop s'],true,'LEFT JOIN')->where(['m_id'=>$m_id,'s.status'=>1,'u.status'=>1])->one();
        $shop=$user['shop'];
        if(!empty($shop)){
            return $shop;
        }else{
            throw new HttpException(400,'没有权限');
        }
    }
  



    
}
?>