<?php
/**
 * 自提点
 * 
 * Author wsyone wsyone@faxmail.com
 * Time:2019年11月25日上午11:51:50
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace api\modules\v1\controllers;
use api\common\controllers\Controller;
use common\models\PickSearch;
use yii;
class PickController extends  Controller{
    
    public function actionIndex(){
        
        $data = Yii::$app->request->post();
        $searchModel = new PickSearch();
        $dataProvider = $searchModel->search($data);
        
        return $dataProvider;
    }
    
}