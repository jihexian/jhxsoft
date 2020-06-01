<?php

namespace frontend\controllers;
use yii\filters\AccessControl;
use frontend\common\controllers\Controller;
use common\models\Member;
use common\models\Shop;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;


/**
 * Distribut controller.
 */
class RankController extends Controller
{
	
 
    
    /**
     * 排行榜首页
     */
    public function actionIndex()
    {
        $data['num']=20;
        $query = Shop::find()->alias('s')->joinWith('villagecommissionlog as v')
        ->select(['s.*','sum(v.percentage) as total'])
        ->having(['>','total',0])
        ->groupBy('s.name');
        $dataProvider= new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                        'pageSize' => $data['num'],
                ],
                'sort' => [
                        'defaultOrder' => [
                                'total'=>SORT_DESC,
                        ],
                ]
        ]);
        $model=$dataProvider->getModels();
        return $this->render('index',['model'=>$model]);
    }
    public function actionShopAjax(){
        $data['num']=20;
        $query = Shop::find()->alias('s')->joinWith('villagecommissionlog as v')
        ->select(['s.logo','s.name','sum(v.percentage) as total'])
        ->having(['>','total',0])
        ->groupBy('s.id');
        $dataProvider= new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                        'pageSize' => $data['num'],
                ],
                'sort' => [
                        'defaultOrder' => [
                                'total'=>SORT_DESC,
                        ],
                ]
        ]);
        $model=$dataProvider->getModels();
        //查询总页数
        $pagecount=$dataProvider->getTotalCount();
        $pagecount=ceil($pagecount/$data['num']);
        return Json::encode(['items'=>$model,'pages'=>$pagecount]);
    }

    public function actionMemberAjax(){
        $data['num']=20;
        $query = Member::find()->alias('m')->joinWith('villagecommissionlog as v')
        ->select(['m.username','m.avatar','sum(v.percentage) as total'])
        ->having(['>','total',0])
        ->groupBy('m.id');
        $dataProvider= new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                        'pageSize' => $data['num'],
                ],
                'sort' => [
                        'defaultOrder' => [
                                'total'=>SORT_DESC,
                        ],
                ]
        ]);
        $model=$dataProvider->getModels();
        foreach ($model as $v){
            $arr=$this->ch2arr($v['username']);
            $v['username']=reset($arr).'**'.end($arr);
        }
        //查询总页数
        $pagecount=$dataProvider->getTotalCount();
        $pagecount=ceil($pagecount/$data['num']);
        return Json::encode(['items'=>$model,'pages'=>$pagecount]);
    }
    function ch2arr($str)
    {
        $length = mb_strlen($str, 'utf-8');
        $array = [];
        for ($i=0; $i<$length; $i++)
            $array[] = mb_substr($str, $i, 1, 'utf-8');
            return $array;
    }
}
