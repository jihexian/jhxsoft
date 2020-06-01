<?php

namespace api\modules\v1\controllers;

use common\models\Member;
use common\models\Distribut;
use common\models\DistributLog;
use common\models\DistributLogSearch;
use yii\helpers\Json;
use common\models\DistributSearch;
use yii\helpers\ArrayHelper;
use Endroid\QrCode\QrCode;
use yii\filters\auth\QueryParamAuth;
use api\common\controllers\Controller;
use Yii;
use common\models\SmsLog;
use common\logic\SmsLogic;
use common\logic\DistributeLogic;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\BindMobileForm;

/**
 * Distribut controller.
 */
class DistributController extends Controller
{
	
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
                [
                        'class' => QueryParamAuth::className(),
                        'tokenParam' => 'token',
                        'optional' => [
                                'index',
                                'list'
                        ],
                ]
        ]);
    }
    
    /**
     * 分销商首页
     */
    public function actionIndex()
    {
        $mid=Yii::$app->user->id;
        //$mid=1;
        //查询用户累计金鹅
        $member=Member::find()->where(['id'=>$mid])->select('distribut_money')->one();
        //查询用户下级代理数
        $distribut=Distribut::find()->where(['pid'=>$mid])->all();
        $num=count($distribut);
        
        //查询分销记录
        $data=[];
        $data['pid']=$mid;
        $data['status']=yii::$app->request->post('status',1);
        $data['num']=10;
        $searchModel = new DistributLogSearch();
        $dataProvider = $searchModel->search($data);
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $dataProvider->setSort($sort);
        $model=$dataProvider->getModels();
        
        //查询总页数
        $pagecount=$dataProvider->getTotalCount();
        $pagecount=ceil($pagecount/$data['num']);
        
        if(yii::$app->request->isPost){
            return ['model'=>$dataProvider,'pages'=>$pagecount];
        }
        
//         print_r($model1);

       $model1=DistributLog::find()
        ->where(['and',['pid'=>$mid],['status'=>1]])
        ->count();
       $model2=DistributLog::find()
                ->where(['and',['pid'=>$mid],['status'=>2]])
                ->count();
       $model3=DistributLog::find()
                ->where(['and',['pid'=>$mid],['status'=>3]])
                ->count();
        $lognum=[
                '1'=>$model1,
                '2'=>$model2,
                '3'=>$model3,
        ];
        $money=0;
        foreach ($model as $v){
            $money+=$v['change_money'];
        }
        
        return [
                'member'=>$member,
                'num'=>$num,
                'model'=>$model,
                'money'=>$money,
                'lognum'=>$lognum,
                'pages'=>$pagecount,
        ];
    }
    
    public function actionAjax()
    {
        $data=\Yii::$app->request->post();
        $data['pid']=\Yii::$app->user->id;
        $data['num']=10;
        $searchModel = new DistributLogSearch();
        $dataProvider = $searchModel->search($data);
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $dataProvider->setSort($sort);
        $model=$dataProvider->getModels();
        $member=[];
        //查询总页数
        $pagecount=$dataProvider->getTotalCount();
        $pagecount=ceil($pagecount/$data['num']);
        foreach ($model as $k=>$v){
            $member[$k]['mobile']=substr($v['member']['mobile'],-4);
            $member[$k]['time']=date('Y-m-d H:m:s',$v['updated_at']);
        }
        return Json::encode(['items'=>$model,'member'=>$member,'pages'=>$pagecount]);
    }
    
    /**
     * 代理列表
     */
    public  function actionList()
    {
        //查询用户下级代理
        $data=\Yii::$app->request->post();
        $data['pid']=\Yii::$app->user->id;
        $searchModel = new DistributSearch();
        $dataProvider = $searchModel->search($data);
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $dataProvider->setSort($sort);
        $model=$dataProvider->getModels();
        return ['model'=>$model];
    }
    /**
     * 分享页面
     */
    public function actionShare(){
        $url=env('SITE_URL').'/?pid='.\Yii::$app->user->id;
        return ['url'=>$url];
    }
    
    /**
     * 二维码
     */
    public function actionQrcode($url) {
        $qrCode = new QrCode($url);
        header('Content-Type: '.$qrCode->getContentType());
        return $qrCode->writeString();
    }
    
    
    /**
     * 注册成为分销商
     */
 /*    public function actionRegister(){
        
       if (Yii::$app->request->isPost) {
           $user = Yii::$app->user->identity;
           $mobile = $user->mobile;
           $code = Yii::$app->request->post('code',0);
           $smsLogic = new SmsLogic();           
           $result = $smsLogic->validateSms($mobile, $code, 9);
           if ($result['status']!=1) {
               return $result;
           }else{
               $user->is_distribut = 1;
               if ($user->save()) {
                   return ['status'=>1,'msg'=>'开通成功！'];
               }else{ return ['status'=>0,'msg'=>'开通失败！'];
                   
               }               
           }
       }
    } */
    
    
    /**
     * 注册成为分销商
     */
    public function actionRegister(){
      
        if (Yii::$app->request->isPost) {
            $params = Yii::$app->request->post();
            $mid = Yii::$app->user->id;
            $model = new BindMobileForm();
            if ($model->load($params,'')) {
                return $model->bindMobile($mid,1);
            }else{
                return ['status'=>0,'msg'=>current($model->getFirstErrors())];
            }
        }
            
        
    }
    
    /**
     * 绑定用户关系
     * @return 
     */
    public function actionBind(){
        if (Yii::$app->request->isPost) {
            $user = Yii::$app->user->identity;
            $pid = Yii::$app->request->post('pid');
            $logic = new DistributeLogic();
            $result = $logic->bind($pid, $user->id);
            return $result;
        }
    }
    /**
     * 获取我的分销会员或者我的分销商
     */
    public function actionSubMember() {
        if (Yii::$app->request->isPost) {
            $user = Yii::$app->user->identity;
            $is_distribut = Yii::$app->request->post('is_distribut',0);//0为会员1为分销商
            $num = Yii::$app->request->post('num',10);
            $query = Distribut::find()->joinWith('member',false)
            ->where(['pid'=>$user->id,'yj_distribut.level'=>1])  //当cid为数组时会启用in筛选条件
            ->andWhere(['is_distribut'=>$is_distribut]);
            //$q = $query->createCommand()->getRawSql();
            //echo $q;
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_DESC
                    ]
                ],
                'pagination' => [
                    'pageSize' =>$num,
                    'validatePage'=>false,
                ],
            ]);
            return $dataProvider;
        }
    }
    /**
     * 获取当日收益
     */
    public function actionDayMoney(){
        if (Yii::$app->request->isPost) {
            $user = Yii::$app->user->identity;
            $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
            $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            $sum = DistributLog::find()->where(['pid'=>$user->id])
                ->andWhere(['<>','status',3])->andWhere(['>=','created_at',$beginToday])
                ->andWhere(['<=','created_at',$endToday])->sum("change_money");
            return ['status'=>1,'data'=>empty($sum)? 0.00:$sum];
        }
        
    }
    /**
     * 获取冻结收益
     */
    public function actionFrozenMoney(){
        if (Yii::$app->request->isPost) {
            $user = Yii::$app->user->identity;            
            $sum = DistributLog::find()->where(['pid'=>$user->id])
            ->andWhere(['status'=>2])->sum("change_money");
            
            return ['status'=>1,'data'=>empty($sum)? 0.00:$sum];
        }
        
    }
    
    /**
     * 获取收益记录
     */
    public function actionLog(){
        if (Yii::$app->request->isPost) {            
            $user = Yii::$app->user->identity;
            $status = intval(Yii::$app->request->post('status',0));//0为全部 1已获得 2在路上 3失败
            $num = Yii::$app->request->post('num',10);
            $query = DistributLog::find()->joinWith('subMember',false)
            ->where(['pid'=>$user->id])
            ->andFilterWhere(['yj_distribut_log.status'=>$status==0? null:$status]);            
            //$q = $query->createCommand()->getRawSql();
            //echo $q;
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_DESC
                    ]
                ],
                'pagination' => [
                    'pageSize' =>$num,
                    'validatePage'=>false,
                ],
            ]);
            return $dataProvider;
        }
        
    }
}
