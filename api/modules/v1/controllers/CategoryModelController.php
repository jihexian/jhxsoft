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

class CategoryModelController extends Controller
{


	/**
	 * 模型
	 * @return \yii\data\ActiveDataProvider
	 */
    public function actionDetail()
    {
    	$modelId = Yii::$app->request->post('model_id');
        $items = CategoryModel::find()->where(['model_id'=>$modelId])->with('categoryModelAttr.categoryModelAttrValue')->asArray()->all(); 
        ArrayHelper::multisort($items[0], 'sort');
        ArrayHelper::multisort($items[0]['categoryModelAttr'], 'sort');
        //ArrayHelper::multisort($items[0]['categoryModelAttr']['categoryModelAttrValue'], 'sort');不需要
        return ['items'=>$items];
    } 
    
    public function actionIndex(){
    	$items = CategoryModel::find()->all();
    	return ['items'=>$items];
    }
    
    
}
