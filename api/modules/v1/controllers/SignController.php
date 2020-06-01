<?php
namespace api\modules\v1\controllers;
use api\common\controllers\Controller;
use common\models\Member;
use common\models\Sign;
use common\models\SignInfo;
use common\logic\AccountLogic;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii;
class SignController extends Controller
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
   /**
    * 用户签到情况
    * @return number[][]|NULL[][]|mixed[][]
    */
    public function actionIndex(){
        $sign = Sign::findToday()->andWhere(['user_id' => Yii::$app->user->id])->one();
        if(!empty($sign)){
           $is_sign=1;
        }else{
            $is_sign=0;
        }
        $signInfo = SignInfo::find()->andWhere(['user_id' => Yii::$app->user->id])->one();
        if(!empty($signInfo)){
            $continue_times=$signInfo->continue_times;
        }else{
            $continue_times=0;
        }
        $data=array();
        $start=1;
        if($continue_times>1){
            $start=$continue_times-1;
        }
       
        for($i=0;$i<6;$i++){
            if( $start<7&& $start>0){
                $score=yii::$app->config->get('score');
            }
            if($start>5){
                $score=yii::$app->config->get('score')+5;
            }
            $data[$i]['day']=$start;
            $data[$i]['num']=$score;
            $data[$i]['name']='第'.$start.'天';
            $data[$i]['checked']=$start<=$continue_times?true:false;
            $start++;
        }
        $score=Member::findOne(['id'=>yii::$app->user->id])->getAttribute('score');
        return ['item'=>['is_sign'=>$is_sign,'continue_times'=>$continue_times,'score'=>$score],'data'=>$data];
        
    }
    
    /**
     * 规则
     */
    public function actionText(){
        
    }
    
    /**
     * 用户每天签到
     * @return number[]|string[]
     */
    public function actionSign()
    {
        /**
         * @var Sign $sign
         * @var SignInfo $signInfo
         */
        $sign = Sign::findToday()->andWhere(['user_id' => Yii::$app->user->id])->one();
        $signInfo = SignInfo::find()->andWhere(['user_id' => Yii::$app->user->id])->one();
        // 没签的签，签了就不管了，正常不会签了还有请求过来
        $transaction=yii::$app->db->beginTransaction();
        try {
            if (empty($sign)) {
                $sign = new Sign();
                $sign->user_id = Yii::$app->user->id;
                $sign->sign_at = time();
                $sign->save();
                if (empty($signInfo)) {
                    $signInfo = new SignInfo();
                    $signInfo->last_sign_at = $sign->sign_at;
                    $signInfo->user_id = $sign->user_id;
                    $signInfo->times = 1;
                    $signInfo->continue_times = 1;
                    $signInfo->save();
                } else {
                    // 如果上次签到是昨天,连续签到
                    if (date('Ymd', $signInfo->last_sign_at) == date('Ymd', time() - 60 * 60 *24)) {
                        $signInfo->continue_times += 1;
                    } else {
                        $signInfo->continue_times = 1;
                    }
                    $signInfo->last_sign_at = time();
                    $signInfo->times += 1;
                    $signInfo->save();
                }
              
                $member=Member::findOne(['id'=>yii::$app->user->id]);
                if($signInfo->continue_times<7&&$signInfo->continue_times>0){
                    $score=yii::$app->config->get('score')+($signInfo->continue_times-1)*2;
                }    
                if($signInfo->continue_times>6){
                    $score=yii::$app->config->get('score')+5*2;
                }
                $accountLogic = new AccountLogic();
                $changeParams = array();
                $changeParams['score'] = $score;
                $account_status=$accountLogic->changeAccount(Yii::$app->user->id, $changeParams, 3,'','签到：增加'.$score.'积分' );  
                if($sign->hasErrors()||$signInfo->hasErrors()||$account_status['status']!=1){
                    $transaction->rollBack();
                    return ['status'=>0,'msg'=>'签到失败'];
                }
   
                $transaction->commit();
                return ['status'=>1,'msg'=>'签到成功','score'=>$score];
            }else{
                $transaction->rollBack();
                return ['status'=>0,'msg'=>'今天您已经签过了'];
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['status'=>0,'msg'=>'签到失败'];
        }
     
    }
}
?>