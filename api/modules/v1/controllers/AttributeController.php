<?php

namespace api\modules\v1\controllers;

use yii;
use api\common\controllers\Controller;
use yii\data\ActiveDataProvider;
use common\models\Attribute;

class AttributeController extends Controller
{


    public function actionIndex()
    {
    	$typeId = Yii::$app->request->post('typeId');
    	$query = Attribute::find()->where(['type_id' => $typeId])->joinWith('attributeValue');  
    	//$commandQuery = clone $query;
    	//var_dump($typeId);

    	//var_dump($commandQuery->createCommand()->getRawSql());
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,    			    			
    			'sort' => [
    				'defaultOrder' => [
    					'attribute_id' => SORT_DESC
    				]
    			]
    	]);
    	//$models = $dataProvider->getModels();
    	//var_dump($models);
    	return $dataProvider;
    } 

    
}
