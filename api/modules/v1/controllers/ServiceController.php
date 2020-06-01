<?php

namespace api\modules\v1\controllers;
use api\common\controllers\Controller;
use Yii;
use yii\base\Exception;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use api\modules\v1\models\ServiceSearch;
use common\models\OrderSku;
use api\modules\v1\models\Service;
class ServiceController extends Controller{
  
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
                [
                        'class' => QueryParamAuth::className(),
                        'tokenParam' => 'token',
                        'optional' => [
                                
                        ],
                ]
        ]);
    }
    public function actionIndex(){
        $data = array();
        $data['mid'] = Yii::$app->user->id;
        $data['status']=Yii::$app->request->post('status');
        $data['apply_status']=Yii::$app->request->post('apply_status');
        $data['is_del']=0;
        $searchModel=new ServiceSearch();
        $dataProvider = $searchModel->search($data);
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort=true;
        $dataProvider->setSort($sort);
        //         $model=$dataProvider->getModels();
        return $dataProvider;
    } 
    
    /**
     * $sku_id获取订单信息
     * @param  $sku_id
     */
    public function actionInfo(){
        $id=Yii::$app->request->post('id'); 
        $order_id=yii::$app->request->post('order_id');
        $m_id=yii::$app->user->id;
        $data=OrderSku::find()->alias('s')->joinWith(['order o'])->where(['o.id'=>$order_id,'o.m_id'=>$m_id,'s.id'=>$id])->one();
        return ['item'=>$data];
        
    }
    
    /**
               *  售后进度信息
     * @return \yii\db\ActiveRecord[]|array[]|NULL[]
     */
    public function actionProcess(){
        $id=Yii::$app->request->post('id');
        $mid=yii::$app->user->id;
        $data=Service::find()->where(['id'=>$id,'mid'=>$mid])->one();
        if($data){
            return ['item'=>$data];
        }else{
            return ['errmsg'=>'没有数据'];
        }
       
        
    }
    
}