<?php
/**
 * 
 * Author vamper 944969253@qq.com
 * Time:2018年11月30日 下午4:03:39
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\logic;
use common\models\CardItem;
use Yii;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\helpers\Json;

class CardLogic{
    /**
     * 充值卡充值
     * @param  $mid
     * @param  $params
     * @return 
     */
    public function recharge($mid,$params){
        $tx = Yii::$app->db->beginTransaction();
        try {
            $cardItem = CardItem::find()->where(['card_no'=>$params['card_no']])->one();
            if (empty($cardItem)) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'充值卡不存在'];
            }
            if (!$cardItem->validatePassword($params['password'])) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'卡密错误！'];
            }
            if (!empty($cardItem['use_time'])) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'充值卡已被使用，充值失败！'];
            }            
            $accountLogic = new AccountLogic();
            $changeParams = array();        
            $card = $cardItem->card;
            if ($card['status']!=1) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'该充值卡已被禁用，充值失败！'];
            }
            $changeParams['money'] = $card['money'];  
            $info = array();
            $info['card_item'][] = $cardItem['card_no'];
            $info = Json::encode($info);
            
            $result = $accountLogic->changeAccount($mid, $changeParams, 2,$info,'充值卡充值');
            if ($result['status']!=1) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>'系统错误，充值失败！'];
            }
            $cardItem->use_time = time();
            $cardItemInfo = array();
            $cardItemInfo['account_log'][] = $result['data']['id'];
            $cardItemInfo = Json::encode($cardItemInfo);
            $cardItem['info'] = $cardItemInfo;
            
            if (!$cardItem->save()) {
                $tx->rollBack();
                return ['status'=>0,'msg'=>current($cardItem->getErrors())];
            }
            $tx->commit();
            return ['status'=>1,'msg'=>'充值成功!'];
            
        }catch (StaleObjectException $e) {
            $tx->rollBack();
            return ['status'=>0,'msg'=>'充值卡已被使用，充值失败！'];
        }catch (Exception $e) {
            $tx->rollBack();
            return ['status'=>0,'msg'=>'系统错误，充值失败！'];
        }
        
    }

    
}
