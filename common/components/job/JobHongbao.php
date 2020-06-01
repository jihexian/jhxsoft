<?php
namespace common\components\job;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\helpers\Json;
use common\logic\AccountLogic;
use common\models\Hongbao;
use frontend\models\Member;
use Yii;

class JobHongbao extends BaseObject implements \yii\queue\JobInterface
{
    public $id;    
    
    public function execute($queue)
    {
        $this->back($this->id);
    }
        
    private function back($id){
        $tx = Yii::$app->db->beginTransaction();
        try {
            $hongbao = Hongbao::findOne($id);
            $sender = Member::findOne($hongbao->mid);
            $accountLogic = new AccountLogic();
            $now = time();
            if ($hongbao->created_at + 86400 < $now  && $hongbao->status != 0) {
                //处理红包回退逻辑
                $money = $hongbao['rest_money'];
                $info = array();
                $info['hongbao']['id'] = $hongbao->id;
                $info['hongbao']['username'] = $sender->getAttribute("username");
                $info = Json::encode($info);
                $changeParams = array();
                $changeParams['money'] = $money;
                $resultAccountLogic = $accountLogic->changeAccount($hongbao->mid, $changeParams, 10,$info,'红包退回');
                //accountlog记录
                if ($resultAccountLogic['status']!=1) {
                    $tx->rollBack();
                    //return ['status'=>0,'msg'=>'系统错误，请重试！'];
                }
                //修改红包：rest_money和info
                $hongbao->rest_money = 0;
                $hongbao->status=0;
                
                $info = array();
                if (isset($hongbao->info)&& !empty($hongbao->info)) {
                    $info = json_decode($hongbao->info);
                }
                $currentInfo = array();
                $currentInfo['mid'] = $hongbao->mid;
                $currentInfo['username'] = $sender->getAttribute('username');
                $currentInfo['type']= 2;
                $currentInfo['money'] = $money;
                $currentInfo['created_at'] = time();
                array_push($info, $currentInfo);
                $info = Json::encode($info);
                $hongbao->info = $info;
                if (!$hongbao->save()) {
                    $tx->rollBack();
                    //return ['status'=>0,'msg'=>current($hongbao->getFirstErrors())];
                }                
            }
            $tx->commit();
        }catch (StaleObjectException $e){
            $tx->rollBack();
            //return ['status'=>0,'msg'=>'系统错误，请重试！'];
        }catch (Exception $e) {
            $tx->rollBack();
            //return ['status'=>0,'msg'=>'系统错误，请重试！'];
        }
        
    }
    
    public function getDelay(){
        
        $hongbao = Hongbao::findOne($this->id);
        $now = time();
        $delay = $hongbao->created_at + 86410 - $now;
        return $delay>0? $delay : 1 ;
        
    }
}