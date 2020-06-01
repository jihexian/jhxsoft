<?php
/**
 * Created by notepad++.
 * Author: 小黑
 * DateTime: 2018/4/9
 * Description:
 */
namespace api\modules\v1\controllers;

/*文章分类接口*/

use yii;
use  api\common\controllers\Controller;
use  api\modules\v1\models\Category;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

class CategoryController extends Controller
{


   public function actionGetOne(){
	  $request = Yii::$app->request;
	  $id = $request->post('id',1);  //幻灯片位置id
	  $model=new Category();
	  $data=$model->getOne($id);
	  return $data;
   }
}