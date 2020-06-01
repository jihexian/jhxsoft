<?php
/**
 * 
 * Author vamper 944969253@qq.com
 * Time:2019年2月21日 上午10:43:30
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace common\logic;

use common\models\Member;
use Yii;
use yii\db\StaleObjectException;

class OrderArriveLogic{
//     public function useMoney($con,$m_id,$order_id){
//         $transaction = Yii::$app->db->beginTransaction();
//         try {
//             $member=Member::findOne($m_id);            
//             if($con['pay_amount']>$member['user_money']){
//                 $transaction->rollBack();
//                 return ['status'=>0,'msg'=>'余额不足'];
//             }
//             //更新用户余额
//             $accountLogic = new AccountLogic();
//             $accoutLogicResult = $accountLogic->changeMoney($m_id, $changeMoney, 5,$order_id);
//             if ($accoutLogicResult['status']!=1) {
//                 $transaction->rollBack();
//                 return $accoutLogicResult;
//             }           
//             //更新订单状态            
            
            
            
            
//             if($arr['status']!=1) {                
//                 $transaction->rollBack();
//                 return ['status'=>0,'msg'=>'操作失败'];
//             }
//             $transaction->commit();
//             return ['status'=>1,'msg'=>'操作成功'];            
//         }catch (StaleObjectException $e) {
//             // 解决冲突的代码
//             $transaction->rollBack();
//             throw new \Exception('操作错误');
//         }catch (\Exception $e) {            
//             $transaction->rollBack();
//             throw new \Exception('操作错误');
//         }
//     }

    
    
}
