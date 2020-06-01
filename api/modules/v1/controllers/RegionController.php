<?php

namespace api\modules\v1\controllers;

use yii;
use api\common\controllers\Controller;
use yii\data\ActiveDataProvider;
use common\helpers\Tree;
use common\models\ProductSearch;
use common\models\Product;
use common\models\CategoryModel;
use yii\helpers\ArrayHelper;
use common\models\Region;

class RegionController extends Controller
{


	/**
	 * 
	 * @return \yii\data\ActiveDataProvider
	 */
    public function actionTree()
    {
        $items = Region::getTree(); 
       
        return ['items'=>$items];
    }
    
    public function actionCity(){
        $id=yii::$app->request->post('id');
        $data=Region::find()->where(['level'=>2,'parent_id'=>$id])->select(['id','name'])->orderBy(['sort' => SORT_ASC, 'id' => SORT_ASC])->cache(365*30*24*3600)->all();
        return ['items'=>$data];
    }
    
    public function actionArea(){
        $id=yii::$app->request->post('id');
        $data=Region::find()->where(['level'=>3,'parent_id'=>$id])->select(['id','name'])->orderBy(['sort' => SORT_ASC, 'id' => SORT_ASC])->cache(365*30*24*3600)->all();
        return ['items'=>$data];
        
    }
}
