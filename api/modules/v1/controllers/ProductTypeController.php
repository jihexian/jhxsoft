<?php
/**
 * 类目接口
 * Author wsyone wsyone@faxmail.com
 * Time:2019年9月17日上午9:20:38
 * Copyright:广西几何线科技有限公司
 * site:https://www.jihexian.com
 */
namespace api\modules\v1\controllers;
use api\common\controllers\Controller;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\ProductType;

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
        $query = ProductType::find()->where(['parent_id'=>0,'status'=>1]);
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
        $data = $dataProvider->getModels();
        return  array_merge(['items'=>$data],['show_type'=>1]);
       
    }
    
    
    
}
