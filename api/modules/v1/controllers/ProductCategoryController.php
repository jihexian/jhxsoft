<?php

namespace api\modules\v1\controllers;

use yii;
use api\common\controllers\Controller;
use yii\data\ActiveDataProvider;
use common\helpers\Tree;
use api\modules\v1\models\ProductCategory;

class ProductCategoryController extends Controller
{


	/**
	 * 商品分类列表
	 * @return \yii\data\ActiveDataProvider
	 */
    /*public function actionIndex()
    {
    	$items = ProductCategory::getDropDownList(Tree::build(ProductCategory::find()->orderBy(['parent_id' => SORT_ASC, 'sort' => SORT_ASC])->asArray()->all(),'category_id','parent_id'));  
    	return ['items'=>$items]; 
    } */
    public function actionIndex($num=4)
    {
        $shop_id=Yii::$app->request->post('shop_id');
    	$query = ProductCategory::find()->where(['parent_id'=>0,'status'=>1,'shop_id'=>$shop_id]);
    	$provider= new ActiveDataProvider([
    			'query' => $query,
    			'pagination' => [
    					'pageSize' =>$num,
    			],
    			'sort' => [
    					'defaultOrder' => [
    						'parent_id' => SORT_ASC,
    						'sort' => SORT_ASC
    					]
    			]
    	]);
    	$data=$provider->getModels();
    	return  array_merge(['items'=>$data],['show_type'=>1]);
    	
    }
	public function cateSort($data,$pid=0,$level=0) {
        static $arr = array();
        foreach($data as $k => $v) {
            if($v['parent_id'] == $pid) {
                $arr[$k] = $v;
                $arr[$k]['level'] = $level + 1;
                $this->cateSort($data,$v['category_id'],$level+1);
            }
        }
        return $arr;
    }
}
