<?php

namespace frontend\controllers;
use yii\filters\AccessControl;
use frontend\common\controllers\Controller;
use common\models\Member;
use common\models\Distribut;
use common\models\DistributLog;
use common\models\DistributLogSearch;
use yii\helpers\Json;
use common\models\DistributSearch;
use yii\rest\Serializer;
use yii\helpers\ArrayHelper;
use Endroid\QrCode\QrCode;

/**
 * Distribut controller.
 */
class DistributController extends Controller
{
	
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
                ],
            ],
        ];
    }
    
    /**
     * 分销商首页
     */
    public function actionIndex()
    {
        $mid=\Yii::$app->user->id;
        
        //查询用户累计金鹅
        $member=Member::find()->where(['id'=>$mid])->one();
        //查询用户下级代理数
        $distribut=Distribut::find()->where(['pid'=>$mid])->all();
        $num=count($distribut);
        
        //查询分销记录
        $data=[];
        $data['pid']=$mid;
        $data['status']=1;
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
             
        //可提现赏金
        $money=0;
        foreach ($model as $v){
            $money+=$v['change_money'];
        }
        
        return $this->render('index',[
                'member'=>$member,
                'num'=>$num,
                'model'=>$model,
                'money'=>$money,
                'lognum'=>$lognum,
                'pages'=>$pagecount,
        ]);
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
            $member[$k]['name']=$v['subMember']['name'];
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
        $data['num']=15;
        $searchModel = new DistributSearch();
        $dataProvider = $searchModel->search($data);
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $dataProvider->setSort($sort);
        $pagecount=$dataProvider->totalCount;
        $pages=ceil($pagecount/$data['num']);
        $model=$dataProvider->getModels();
        return $this->render('list',[
            'model'=>$model,
            'pages'=> $pages
        ]);
    }
    
    public function actionMember()
    {
        $data=\Yii::$app->request->post();
        $data['pid']=\Yii::$app->user->id;
        $data['num']=15;
        $searchModel = new DistributSearch();
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
            $member[$k]['name']=$v['member']['username'];
            $member[$k]['time']=date('Y-m-d',$v['updated_at']);
            $member[$k]['id']=$v['cid'];
        }
        return Json::encode(['items'=>$model,'member'=>$member,'pages'=>$pagecount]);
    }
    /**
     * 分享页面
     */
    public function actionShare(){
        $url=env('SITE_URL').'/?pid='.\Yii::$app->user->id;
        return $this->render('share',[
                'url'=>$url,
        ]);
    }
    
   
    public function actionQrcode($url)
    {
      
        $qrCode = new QrCode($url);
        header('Content-Type: '.$qrCode->getContentType());
        return $qrCode->writeString();
    }
}
