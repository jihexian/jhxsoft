<?php

namespace api\modules\v1\controllers;

use api\common\controllers\Controller;
use common\logic\HongbaoLogic;
use common\models\AccountLog;
use common\models\AccountLogSearch;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\models\Hongbao;
class HongbaoController extends Controller
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
     * 发红包
     * @return
     */
    public function actionSend(){
        if (Yii::$app->request->isPost) {
            $params = Yii::$app->request->post();
            $user = Yii::$app->user->identity;
            $hongbaoLogic = new HongbaoLogic();
            $result = $hongbaoLogic->createHongbao($user->id, $params);
            return $result;
        }
        
    }
    /**
     * 抢红包
     * @return
     */
    public function actionReceive(){
        if (Yii::$app->request->isPost) {
            $params = Yii::$app->request->post();
            $user = Yii::$app->user->identity;
            $hongbaoLogic = new HongbaoLogic();
            $result = $hongbaoLogic->receive($user->id, $params);
            return $result;
        }
        
    }
    /**
     * 红包详情
     * @return
     */
    public function actionDetail(){
        if (Yii::$app->request->isPost) {
            $params = Yii::$app->request->post();
            $user = Yii::$app->user->identity;
            $hongbaoLogic = new HongbaoLogic();
            $result = $hongbaoLogic->Detail($user->id, $params);
            return $result;
        }
        
    }
    
    public function actionSendRecord(){
        if (Yii::$app->request->isPost) {
            $year = Yii::$app->request->post('year',date('Y',time()));
            $times = $this->gettimes($year);
            $user = Yii::$app->user->identity;
            
            $data = array();
            $data['member_id'] = $user->id;
            $data['change_type'] = 4;
            $data['type'] = 9;
            $data['startTime'] = $times['stime'];
            $data['endTime'] = $times['etime'];
            $data['num'] = Yii::$app->request->post('num',10);
            $sum = AccountLog::find()->where(['type'=>9,'member_id'=>$user->id])->andWhere(['<', 'change_money', 0])
                ->andFilterWhere(['>=', 'created_at', $data['startTime']])
                ->andFilterWhere(['<=', 'created_at',  $data['endTime']])->sum('change_money');
            $searchModel = new AccountLogSearch();
            $dataProvider = $searchModel->search($data);
            $models = $dataProvider->getModels();
            foreach ($models as &$v){
                $v = $v->toArray();
                $info = Json::decode($v['info']);
                $id = $info['hongbao']['id'];
                $hongbao = Hongbao::findOne($id)->getAttributes(['send_num','received']);
                $v['hongbao']= $hongbao;
            }
            $dataProvider->setModels($models);
            return ['data'=>$this->serializeData($dataProvider),'sum'=>$sum];
            
        }
    }
    
    public function actionReceiveRecord(){
        
        if (Yii::$app->request->isPost) {
            $year = Yii::$app->request->post('year',date('Y',time()));            
            $times = $this->gettimes($year);            
            $user = Yii::$app->user->identity;
            $data = array();
            $data['member_id'] = $user->id;
            $data['change_type'] = 3;
            $data['type'] = 9;
            $data['startTime'] = $times['stime'];
            $data['endTime'] = $times['etime'];
            $data['num'] = Yii::$app->request->post('num',10);
            $searchModel = new AccountLogSearch();
            $dataProvider = $searchModel->search($data); 
            
            $sum = AccountLog::find()->where(['type'=>9,'member_id'=>$user->id])->andWhere(['>', 'change_money', 0])
                ->andFilterWhere(['>=', 'created_at', $data['startTime']])
                ->andFilterWhere(['<=', 'created_at',  $data['endTime']])->sum('change_money');
            return ['data'=>$this->serializeData($dataProvider),'sum'=>$sum];
        }
    }
    /**
     * 红包退回
     */
    public function actionReturnBack(){
        $params = Yii::$app->request->post();
        $user = Yii::$app->user->identity;
        $hongbaoLogic = new HongbaoLogic();
        $result = $hongbaoLogic->back($user->id, $params);
        return $result;
    }
    private function gettimes($year){
        $smonth = 1;
        $emonth = 12;
        $startTime = $year.'-'.$smonth.'-1 00:00:00';
        $em = $year.'-'.$emonth.'-1 23:59:59';
        $endTime = date('Y-m-t H:i:s',strtotime($em));
    
        return array('stime'=>strtotime($startTime),'etime'=>strtotime($endTime));
    }
   
}
