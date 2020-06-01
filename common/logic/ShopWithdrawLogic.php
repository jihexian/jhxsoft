<?php

namespace common\logic;
use common\helpers\Tools;
use common\models\Plugin;
use common\models\Shop;
use common\models\Member;
use common\models\ShopPay;
use common\models\ShopUser;
use yii\db\StaleObjectException;
use yii\base\Exception;
use common\models\ShopWithdraw;
use common\models\ShopAccoutLog;
use yii;
class ShopWithdrawLogic{

    /**
     * 提现申请
     * @param number $money
     * @param integer $m_id
     * @param string $client
     * @param number $card_id
     * @return number[]|string[]|number[]|mixed[]
     */
    public function apply($money,$m_id,$client='wxMini',$card_id=0){
        $member=Member::findOne(['id'=>$m_id]);
        $user=ShopUser::findOne(['m_id'=>$m_id,'level'=>0]);
        if(empty($user)){
            return ['status'=>0,'msg'=>'没有权限'];
        }
        $shop=$user['shop'];
        if($shop['money']<$money){
            return ['status'=>0,'msg'=>'余额不足'];
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model=new ShopWithdraw();
            $model->setScenario('create');
            if($client=='wxMini'&&$member['xcx_openid']){
                $payment=Plugin::find()->where(['id'=>$client,'status'=>1])->one();
                if(empty($payment)){
                    return [
                        'status' => 0,
                        'msg' => '请先到后台插件控制台开启微信小程序支付功能'
                    ];
                }
                $model->bank='微信支付';
                $model->account=$member['xcx_openid'];
                $model->name=$member['username'];
                $model->payment_code=$payment['id'];  
            }elseif($client='weixin'&&$member['wx_openid']){
                $payment=Plugin::find()->where(['id'=>$client,'status'=>1])->one();
                if(empty($payment)){
                    return [
                        'status' => 0,
                        'msg' => '请先到后台插件控制台开启微信小程序支付功能'
                    ];
                }
                $model->bank='微信支付';
                $model->account=$member['wx_openid'];
                $model->name=$payment['name'];
                $model->payment_code=$payment['id'];  
            }else{
                $pay=ShopPay::findOne(['id'=>$card_id,'status'=>1,'shop_id'=>$shop['id']]);
                if(empty($pay)){
                    return ['status'=>0,'mgs'=>'提现帐号不存在'];
                }
                $model->account=$pay['account'];
                $model->bank=$pay['bank'];
                $model->name=$pay['name'];
            }
            $model->order_no=Tools::get_order_no();
            $model->money=$money;
            $model->shop_id=$shop['id'];
            $model->apply_id=$m_id;
            $model->save();
            if($model->hasErrors()){
                $transaction->rollBack();
                return ['status'=>0,'mgs'=>current($model->getFirstErrors())];
            }

            //店铺收入流水
            $sa= new ShopAccoutLog();
            $sa->shop_id=$shop['id'];
            $sa->type=3;//提现
            $sa->comment='提现申请';
            $sa->change_money=-$money;
            $sa->money=$shop['money'];
            $sa->loadDefaultValues();
            $sa->save();
            if($sa->hasErrors()){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>current($sa->getFirstErrors())];
            }
            //减去店铺余额
            $shop->money= bcsub( $shop->money , $money,2);
            $shop->save();
            if($shop->hasErrors()){
                $transaction->rollBack();
                return ['status'=>0,'mgs'=>'操作失败'];
            }
            $transaction->commit();
            return ['status'=>1,'mgs'=>'操作成功'];
        }catch(StaleObjectException $e){
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'请不要重复操作'];
        } catch (Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
        }
    }
    /**
     * 提现不通过
     */
    public function refuse($id,$mark,$version=0){
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $withdraw=ShopWithdraw::findOne(['id'=>$id]);
            $withdraw->setScenario('update');
            if($withdraw['status']!=0){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'请不要重复处理'];
            }
            $withdraw->status=2;//拒绝
            $withdraw->mark=$mark;
            $withdraw->save();
            $shop=Shop::find()->where(['id'=>$withdraw['shop_id']])->one();
            //店铺收入流水
            $sa= new ShopAccoutLog();
            $sa->shop_id=$withdraw['shop_id'];
            $sa->type=4;
            $sa->change_money=$withdraw['money'];
            $sa->money=$shop['money'];
            $sa->comment='提现失败，资金退回';
            $sa->loadDefaultValues();
            $sa->save();
            if($sa->hasErrors()||$withdraw->hasErrors()){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'失败'];
            }
            //减去店铺余额
            $shop->money= bcadd( $shop->money , $withdraw['money'],2);
            $shop->version=$version; 
            $shop->save();
            if($shop->hasErrors()){
                $transaction->rollBack();
                return ['status'=>0,'mgs'=>'操作失败'];
            }
            $transaction->commit();
            return ['status'=>1,'mgs'=>'操作成功'];
        }catch(StaleObjectException $e){
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'请不要重复操作'];
        } catch (Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
        }
    }
    
    /**
     * @desc 使用微信或支付宝接口付款给客户
     * @param int $with_id
     */
    public function apiTranfer($id,$mark){
        $transaction=yii::$app->db->beginTransaction();
        try {
            $data=ShopWithdraw::findOne($id);
            if($data->status!=0){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'订单已经处理过'];
            }
            switch($data['payment_code']){
                case 'weixin':      $payment=new \plugins\weixin\Weixin();break;
                case 'wxMini':      $payment=new \plugins\wxMini\WxMini();break;
                case 'alipayMobile':$payment=new \plugins\alipayMobile\AlipayMoblie();break;
                default:break;
            }
            
            if($data['payment_code']=='weixin'||$data['payment_code']=='wxMini'){ //微信支付
                $weixin=array(
                    'amount'=>$data->money,
                    'order_no'=>$data->order_no,
                    'openid'=>$data->account,
                    'desc'=>'用户提现'.$data->money.'元',
                );
                $flag=$payment->transfer($weixin);
                if($flag['status']!=1){
                    $transaction->rollBack();
                    return ['status'=>0,'msg'=>$flag['msg']];
                }
                
                
            }elseif($data['payment_code']=='alipayMobile'){                 //余额支付
                $alipay=array();
                //  $flag=$payment->payment_refund($alipay);
                
            }
            $data->scenario='update';
            $data->status=1;
            $data->mark=$mark; 
            $data->pay_time=$flag['payment_time'];
            $data->transaction_id=$flag['payment_no'];
            $data->save();
            if($data->hasErrors()){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>current($data->getErrors())[0]];
            }
            $transaction->commit();
            return ['status'=>1,'msg'=>'操作成功'];
        }catch(StaleObjectException $e){
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'请不要频繁操作'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
            
        }
        
    }
    
    public function personTranfer($id,$mark){
        $transaction=yii::$app->db->beginTransaction();
        try {
            $data=ShopWithdraw::findOne($id);
            if($data->status!=0){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'订单已经处理过'];
            }
            $data->scenario='update';
            $data->status=1;
            $data->pay_time=time();
            $data->mark=$mark;
            $data->save();
            if($data->hasErrors()){
                $transaction->rollBack();
                return ['status'=>0,'msg'=>current($data->getErrors())[0]];
            }
            $transaction->commit();
            return ['status'=>1,'msg'=>'操作成功'];
        }catch(StaleObjectException $e){
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'请不要频繁操作'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'操作失败'];
            
        }
    }

    

}