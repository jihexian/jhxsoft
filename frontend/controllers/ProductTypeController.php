<?php

namespace frontend\controllers;

use Yii;

use frontend\common\controllers\Controller;
use yii\data\ActiveDataProvider;
use common\models\ProductType;

/**
 * ProductType controller.
 */
class ProductTypeController extends Controller
{
	
     /**
     * 类目列表页.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $query = ProductType::find()->with('sons')->where(['parent_id'=>0]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' =>50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'parent_id' => SORT_ASC,
                    'sort' => SORT_ASC
                ]
            ]
        ]);
        $types = $dataProvider->getModels();
        

        return $this->render('index',[
            'types'=>$types,
        ]);
    }
    
    
    
}
