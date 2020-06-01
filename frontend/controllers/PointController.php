<?php 
/**
 *地址管理
 * Author wsyone wsyone@faxmail.com   pengpeng 617191460@qq.com
 * Time:2018年11月21日 下午12:22:52
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace frontend\controllers;
use yii;
use yii\base\Exception;
use frontend\common\controllers\Controller;
use common\models\Address;
use common\models\Pick;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\filters\AccessControl;
use common\models\Region;


class PointController extends Controller{
   
    
    public function behaviors()
    {
        return [
                'access' => [
                        'class' => AccessControl::className(),
                        'rules' => [
                                [
                                        'allow' => true,
                                        'actions' => ['index','add','delete','update','default'],
                                        'roles' => ['@'],
                                ],
                                
                        ],
                        
                ],
        ];
    }
    /**
     * 自提点列表
     */
    public function actionIndex(){
        $type = Yii::$app->request->get('type');
        $id = Yii::$app->request->get('id');
        $city_name=yii::$app->request->get('city','');
        $area_name=yii::$app->request->get('area','');
        $city=Region::find()->where(['name'=>$city_name])->orderBy('id desc')->one();
        $city_id=0;
        $area_id=0;
        if($city){
            $city_id=$city['id'];
        }
        $area=Region::find()->where(['name'=>$area_name,'parent_id'=>$city_id])->one();
        if($area){
            $area_id=$area['id'];
        }
        $query=Pick::find()->where(['city_id'=>$city_id,'area_id'=>$area_id]);
        $data= new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
      return  $this->render('index',[
           'data'=>$data->getModels(),
              'type'=>$type,
              'id'=>$id,
       ]);
       
        
    }   
}
